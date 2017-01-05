/*	
 * Copyright IBM Corp. 2015-2016
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Steven R. Loomis
 */

// -- CALLBACKS --

/**
 * Basic Callback used throughout the SDK
 * @callback basicCallback
 * @param {Object} err -  error, or null
 * @param {Object} data - Returned data
 */

var utils = require('./utils.js');
var SwaggerClient = require('swagger-client');
var GpHmac = require('./gp-hmac');
var cfEnvUtil = require('./cfenv-credsbylabel');

/**
 * Construct a g11n-pipeline client. 
 * params.credentials is required unless params.appEnv is supplied.
 * @param {Object} params - configuration params
 * @param {Object} params.appEnv - pass the result of cfEnv.getAppEnv(). Ignored if params.credentials is supplied.
 * @param {Object.<string,string>} params.credentials - Bound credentials as from the CF service broker (overrides appEnv)
 * @param {string} params.credentials.url - service URL. (should end in '/translate')
 * @param {string} params.credentials.userId - service API key.
 * @param {string} params.credentials.password - service API key.
 * @param {string} params.credentials.instanceId - instance ID
 * @returns {Client}
 * @function getClient
 */ 
exports.getClient = function getClient(params) {
  return new Client(params);
};

/**
 * a Regex for matching the service.
 * Usage: var credentials = require('cfEnv')
 *      .getAppEnv().getServiceCreds(gp.serviceRegex);
 * (except that it needs to match by label)
 * @property serviceRegex
 */
exports.serviceRegex = /(gp-|g11n-pipeline).*/;

/**
 * Example credentials
 * @property exampleCredentials
 */
exports.exampleCredentials = {
  url: "Globalization Pipeline URL",
  userId: "User ID",
  password: "secretpassword",
  instanceId: "your Instance ID"
};

var exampleCredentialsString = "credentials: " + JSON.stringify(exports.exampleCredentials);

/**
 * Return a list of missing fields. Special cases the instanceId field.
 * @param obj obj containing fields
 * @param fields array of fields to require
 * @return array of which fields are missing
 */
function isMissingField(obj, fields) {
  var missing = [];
  for(var k in fields) {
    if(fields[k] === 'instanceId' && obj.isAdmin) continue; // skip instanceId for admin instances
    if(!obj[fields[k]]) {
      missing.push(fields[k]);
    }
  }
  return missing;
}

/**
 * Client object for Globalization Pipeline
 * @class Client 
 **/
function Client(options) {
  // parse vcap using cfenv if available
  if(options.appEnv && !options.credentials) {
    options.credentials = cfEnvUtil.getServiceCredsByLabel(options.appEnv, exports.serviceRegex);
  }
  // try again with name
  if(options.appEnv && !options.credentials) {
    options.credentials = options.appEnv.getServiceCreds(exports.serviceRegex);
  }
  this._options = options;
  if ( !this._options.credentials ) {
    throw new Error("g11n-pipeline: missing 'credentials' " + Object.keys(exports.exampleCredentials));
  }
  var missingField = isMissingField(this._options.credentials, Object.keys(exports.exampleCredentials));
  if(missingField.length !== 0) {
    throw new Error("g11n-pipeline: missing credentials fields: \"" + missingField.join(' ') + "\" - expected: " + exampleCredentialsString);
  }
  
  // instanceId optional
  if( this._options.credentials.instanceId ) {
    this._options.serviceInstance = this._options.credentials.instanceId;
  }

  this._options.credentials.url = exports._normalizeUrl(this._options.credentials.url);  
  if ( debugURL ) /*istanbul ignore next*/ console.log('just created a client with ' + JSON.stringify(options));
};

/**
 * Version number of the REST service used. Currently ‘V2’.
 */
Client.prototype.version = version;

/**
 * Internal - list of authorization methods to send to server
 * 
 * @ignore
 */
Client.prototype.authorizations = {};

/**
 * Get the RESTful APIs. Use with ready().
 * This is used to access the low-level REST calls directly.
 * 
 * @return {Object} - Map of API operations, otherwise null if not ready.
 * @ignore
 */
Client.prototype.apis = function apis() {
  if(this.swaggerClient && this.swaggerClient.ready) {
    return this.swaggerClient.apis;
  } else {
    return null;
  }
}

/**
 * Verify that the client is ready before proceeding.
 * 
 * @param {Object} arg - arg option, passed to cb on success or failure
 * @param {Function} cb - callback (called with (null, arg, apis) on success
 * @ignore
 */
Client.prototype.ready = function ready(arg, cb) {
  // TODO: if api is a string, return it as a failure?
  if(this.apis()) {
    if(debugREST) /*istanbul ignore next*/ console.log('.. already had api..');
    // according to http://blog.izs.me/post/59142742143/designing-apis-for-asynchrony 
    // should be async. but these are swagger calls so should always be async.
    cb(null, arg, this.swaggerClient.apis);
  } else {
    var credentials = this._options.credentials;
    var schemaUrl = this._schemaUrl =  this._options.credentials.url + '/rest/swagger.json';
    if(debugREST) /*istanbul ignore next*/ console.log('.. fetching ' + schemaUrl);
    
    var authorizations;
    
    if (this._options.basicAuth) {
      authorizations = {
        "basic-auth": new SwaggerClient.PasswordAuthorization(credentials.userId, credentials.password)
      };
    } else {
      authorizations = {
        "gp-hmac": new GpHmac("gp-hmac", credentials.userId, credentials.password)
      };
    }
    if(debugREST) /*istanbul ignore next*/ console.log(' + authorizations:' + Object.keys(authorizations));
    
    var that = this;
    var swaggerClient = new SwaggerClient({
      url: schemaUrl,
      authorizations: authorizations,
      success: function() {
        if(swaggerClient.ready === true) {
          that.swaggerClient = swaggerClient;
          if(debugREST) /*istanbul ignore next*/ console.log('.. swagger loaded && ready');
          cb(null, arg, that.apis());
        } else {
          if(debugREST) {
            /*istanbul ignore next*/ console.log('.. swagger not ready yet');
          }
          cb(Error('Internal error: Swagger API was not ready in time'), arg);
        }
      },
      failure: function(err) {
        if(debugREST) /*istanbul ignore next*/ console.log(' !! swagger returned an error:'+err);
        // Swagger returned an error
        cb(err, arg);
      }
    });
  }
};

/**
 * Unpack an error object from the REST machinery.
 * @ignore
 */
function RESTError(message, obj) {
  if(obj instanceof Error) {
    return obj;
  }
  if((typeof obj) === "string") {
    // /*istanbul ignore next*/ console.log("Got text " + obj);
    try {
      obj = JSON.parse(obj);
    } catch(e) {} 
  } else {
    // console.dir(obj);
  }
  var e;
  if(obj.status === "ERROR"  && obj.message) {
    e = Error(obj.message);
  } else if(message) {
    e = obj; // Error(obj);
  } else if(obj) {
    e = Error(obj);
  }
  e.obj = obj; // back link
  return e;
}

/**
 * Call a REST function. Verify the results.
 * cb is called with the same context.
 * 
 * This is designed for internal implementation.
 * 
 * @param {Array|String} fn - function name, such as ["service","getServiceInfo"] or "service.getServiceInfo"
 * @param {Object} restArg - args to the REST call
 * @param {Function} cb
 * @ignore
 */
 Client.prototype.restCall = function restCall(fn, restArg, cb) {
   if(!cb || typeof cb !== "function") {
     throw Error("Not a function: " + cb);
   }
   if(typeof(fn)==="string") {
     fn = fn.split('.');
   }
   this.ready({}, function onReady(err, cbArg, apis) {
     if(debugREST) { /*istanbul ignore next*/ console.log('RestCall - onReady: ' + fn + ', err: ' + err +', apis:' + apis );}
     if(err) {
       cb(err);
     } else {
       var base = apis;
       for(var i in fn) {
         if(!base.hasOwnProperty(fn[i])) {
           cb(Error('No REST operation: ' + fn.slice(0,i+1).join('.') + ' in ' + fn.join('.')));
           return;
         } else {
           base = base[fn[i]];
         }
       }
       if(typeof(base) !== 'function') {
           cb(Error('REST specifier is a leaf, not a function: ' +  fn.join('.')));
           return;
       }
       
       // call the REST function
       try {
         base(restArg, function onRestSuccess(resp) {
            if(resp && resp.status === 500) {
              // server returned an internal error
              cb(RESTError('Internal server error', resp.obj || resp));
            } else if (resp && resp.obj && 'SUCCESS' !== resp.obj.status) {
              // REST returned an error
              cb(RESTError('Server returned REST err, status:' + resp.obj.status ), resp.obj || resp);
            } else {
              // SUCCESS!
              cb(null, resp.obj );
            }
          }, function onRestFail(resp) {
            
            if(resp && resp.status === 500) {
              cb(RESTError('Fail: Internal server error ', resp.data || resp));
            } else {
              cb(RESTError(resp.toString(), (resp.obj || resp)));
            }
          });
       } catch(e) {
         cb(e);
       }
     }
   });
};

/**
 * Get the serviceInstance id from a parameter or from the 
 * client's default.
 * 
 * @param {Object} opts - can be a map, or falsy.
 * @param {String} opts.serviceInstance - the service instance
 * @return {String} - the service instance ID if found
 * @ignore
 */
Client.prototype.getServiceInstance = function getServiceInstance(opts) {
  /*if(typeof(opts) === "string" && opts !== "") {
    return opts;
  } else*/ if(opts && opts.serviceInstance && opts.serviceInstance !== "") {
    return opts.serviceInstance;
  } else if(this._options.serviceInstance && this._options.serviceInstance !== "") {
    return this._options.serviceInstance;
  } else {
    return null;
  }
};

/**
 * Get a list of the bundles.
 * Note: This function may be deprecated soon, but won't be removed.
 * This is called by Client.bundles
 * 
 * @param {Object} opts
 * @param {String} opts.serviceInstance - optional service instance
 * @param {Client~bundleListCallback} cb - callback: (err, array-of-ids)
 * 
 * @ignore
 */
Client.prototype.getBundleList = function getBundleList(opts, cb) {
  var serviceInstance = this.getServiceInstance(opts);
  assert(serviceInstance && serviceInstance!== "", "Could not find a service instance");
  
  this.restCall("bundle.getBundleList", 
    {serviceInstanceId: serviceInstance},
    function(err, restData) {
      if(err) {
        cb(err);
      } else {
        cb(null, restData.bundleIds);
      }
    });
};

/**
 * This function returns a map from source language(s) to target language(s).
 * Example: `{ en: ['de', 'ja']}` meaning English translates to German and Japanese.
 * @param {object} [opts={}] - ignored
 * @param {Client~supportedTranslationsCallback} cb (err, map-of-languages)
 */
Client.prototype.supportedTranslations = function supportedTranslations(opts, cb) {
  // make 'opts' optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  this.getServiceInfo(opts, function(err, data) {
    if(err) {
      cb(err);
    } else {
      cb(null, data.supportedTranslation);
    }
  });
};

/**
 * Callback returned by supportedTranslations()
 * @callback Client~supportedTranslationsCallback
 * @param {object} err -  error, or null
 * @param {Object.<string,string[]>} languages - map from source language to array of target languages
 * Example: `{ en: ['de', 'ja']}` meaning English translates to German and Japanese.
 */

/**
 * Get information about this service.
 * At present, no information is returned beyond that expressed by supportedTranslations().
 * @param {object} [opts={}] - ignored argument
 * @param {Client~serviceInfoCallback} cb
 */
Client.prototype.getServiceInfo = function getServiceInfo(opts, cb) {
  // make 'opts' optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  assert(cb !=null, 'cb must not be null');
  this.restCall("service.getServiceInfo", opts, cb);
};

/**
 * Callback used by getServiceInfo()
 * @callback Client~serviceInfoCallback
 * @param {object} err -  error, or null
 * @param {Object} info - detailed information about the service
 * @param {Object.<string,string[]>} info.supportedTranslation - map from source language to array of target languages
 * Example: `{ en: ['de', 'ja']}` meaning English translates to German and Japanese.
 * @param {ExternalService[]} info.externalServices - info about external services available
 */

/**
 * info about external services available
 * @typedef {Object} ExternalService
 * @property {string} type - The type of the service, such as MT for Machine Translation
 * @property {string} name - The name of the service
 * @property {string} id - The id of the service
 * @property {Object.<string,string[]>} supportedTranslation - map from source language to array of target languages
 * Example: `{ en: ['de', 'ja']}` meaning English translates to German and Japanese.
 */

/**
 * Verify that there is access to the server. An error result
 * will be returned if there is a problem. On success, the data returned
 * can be ignored. (Note: this is a synonym for getServiceInfo())
 * @param {object} args - (ignored)
 * @param {basicCallback} cb
 */
Client.prototype.ping = Client.prototype.getServiceInfo;

// --- user stuff ---
/**
 * Create a user
 * @param {object} args 
 * @param {string} args.type - User type (ADMINISTRATOR, TRANSLATOR, or READER)
 * @param {string} args.displayName - Optional display name for the user. 
 * This can be any string and is displayed in the service dashboard. 
 * @param {string} args.comment - Optional comment
 * @param {Array} args.bundles - set of accessible bundle ids. Use `['*']` for “all bundles”
 * @param {Object.<string, string>} args.metadata - optional key/value pairs for user metadata
 * @param {string} args.externalId - optional external user ID for your application’s use
 * @param {User~getUserCallback} cb - passed a new User object
 */
Client.prototype.createUser = function createUser(args, cb) {
  var that = this;
  var serviceInstance = this.getServiceInstance(args);
  if(!args.body) {
      args.body = {
      "type": args.type,
      displayName: args.displayName,
      comment: args.comment,
      bundles: args.bundles,
      metadata: args.metadata,
      externalId: args.externalId
    };
  }
  this.restCall("user.createUser", {
    serviceInstanceId: serviceInstance,
    serviceManaged: args.serviceManaged,
    body: args.body
  }, function(err, data) {
    if(err) {
      cb(err);
    } else {
      cb(null, new User(that, data.user));
    }
  });
};

/**
 * Delete a user. ( called by User.delete )
 * Note: This function may be deprecated soon, but won't be removed.
 * @param {object} args - TBD
 * @param {string} args.userId - user ID to be deleted.
 * @param {string} args.serviceInstance - override service instance
 * @param {basicCallback} cb
 * @ignore
 */
Client.prototype.deleteUser = function deleteUser(args, cb) {
  var serviceInstanceId = this.getServiceInstance(args);  
  this.restCall("user.deleteUser", {
    serviceInstanceId: serviceInstanceId,
    userId: args.userId
  }, cb);
};

/**
 * Note: this constructor is not usually called directly, use Client.bundle(id)
 * @class Bundle
 * @param {Client} gp - parent g11n-pipeline client object
 * @param {Object} props - properties to inherit
 * 
 * @prop {string} updatedBy - userid that updated this bundle
 * @prop {Date} updatedAt - date when the bundle was last updated
 * @prop {string} sourceLanguage - bcp47 id of the source language
 * @prop {string[]} targetLanguages - array of target langauge bcp47 ids
 * @prop {boolean} readOnly - true if this bundle can only be read
 * @prop {Object.<string,string>} metadata - array of user-editable metadata
 */
function Bundle(gp, props) {
  _initSubObject(this, gp, props);
  assert(this.id, "Property 'id' missing (bundle ID)");
}

/**
 * Delete this bundle.
 * @param {Object} [opts={}] - options
 * @param {basicCallback} cb
 */
Bundle.prototype.delete = function deleteBundle(opts, cb) {
  // make 'opts' optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  this.gp.restCall("bundle.deleteBundle",
     {serviceInstanceId: this.serviceInstance, bundleId: this.id}, cb);
};

/**
 * Create this bundle with the specified params.
 * Note that on failure, such as an illegal language being specified,
 * the bundle is not created.
 * @param {Object} body
 * @param {string} body.sourceLanguage - bcp47 id of source language such as 'en'
 * @param {Array} body.targetLanguages - optional array of target languages
 * @param {Object} body.metadata - optional metadata for the bundle
 * @param {string} body.partner - optional ID of partner assigned to translate this bundle
 * @param {basicCallback} cb
 * 
 */
Bundle.prototype.create = function createBundle(body, cb) {
  assert(body, 'Need to provide the “body” parameter.');
  this.gp.restCall("bundle.createBundle",
     {serviceInstanceId: this.serviceInstance, bundleId: this.id, body: body}, cb);
};

/**
 * List of fields usable with Bundle.getInfo()
 */
Bundle.prototype.getInfoFields = new utils.Fields(["translationStatusMetricsByLanguage",
                                  "reviewStatusMetricsByLanguage",
                                  "partnerStatusMetricsByLanguage"]);
/**
 * Get bundle info. Returns a new Bundle object with additional fields populated.
 * @param {Object} [opts={}] - Options object
 * @param {String} opts.fields - Comma separated list of fields
 * @param {Boolean} opts.translationStatusMetricsByLanguage - Optional field (false by default)
 * @param {Boolean} opts.reviewStatusMetricsByLanguage - Optional field (false by default)
 * @param {Boolean} opts.partnerStatusMetricsByLanguage - Optional field (false by default)
 * @param {Bundle~getInfoCallback} cb - callback (err, Bundle )
 */
Bundle.prototype.getInfo = function getBundleInfo(opts, cb) {
  // make 'opts' optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  opts.fields = Bundle.prototype.getInfoFields.processFields(opts);
  var that = this;
  this.gp.restCall("bundle.getBundleInfo",
     {serviceInstanceId: this.serviceInstance, bundleId: this.id, fields: opts.fields}, function(err, data) {
       if(err) {
         cb(err);
       } else {
         // Always return at least an empty metadata object
         data.bundle.metadata = data.bundle.metadata || {};
         // Make this a date object and not a string.
         if(data.bundle.updatedAt) {
             data.bundle.updatedAt = new Date(data.bundle.updatedAt);
         }
         // Copy these fields over. The REST getInfo call will not set them.
         data.bundle.id = data.bundle.id || that.id;
         data.bundle.serviceInstance = data.bundle.serviceInstance || that.serviceInstance;

         var b = that.gp.bundle(data.bundle);

         cb(null, b, data);
       }
     });
};

/**
 * Alias.
 * @ignore
 */
Bundle.prototype.getBundleInfo = Bundle.prototype.getInfo;

/**
 * Return all of the languages (source and target) for this bundle.
 * The source language will be the first element.
 * Will return undefined if this bundle was not returned by getInfo().
 * @return {String[]}
 */
Bundle.prototype.languages = function bundleLanguages() {
  if (!this.sourceLanguage) return undefined;
  return [this.sourceLanguage].concat(this.targetLanguages || []);
}

/**
 * Callback returned by Bundle~getInfo(). 
 * @callback Bundle~getInfoCallback
 * @param {object} err -  error, or null
 * @param {Bundle} bundle - bundle object with additional data
 * 
 * @param {string} bundle.updatedBy - userid that updated this bundle
 * @param {Date} bundle.updatedAt - date when the bundle was last updated
 * @param {string} bundle.sourceLanguage - bcp47 id of the source language
 * @param {string[]} bundle.targetLanguages - array of target langauge bcp47 ids
 * @param {boolean} bundle.readOnly - true if this bundle can only be read
 * @param {Object.<string,string>} bundle.metadata - array of user-editable metadata
 * @param {Object} bundle.translationStatusMetricsByLanguage - additional metrics information
 * @param {Object} bundle.reviewStatusMetricsByLanguage - additional metrics information
 */
/**
 * Fetch one language's strings
 * @param {Object} opts - options
 * @param {String} opts.languageId - language to fetch
 * @param {boolean} [opts.fallback=false] - Whether if source language value is used if translated value is not available
 * @param {string} [opts.fields] - Optional fields separated by comma
 * @param {basicCallback} cb - callback (err, { resourceStrings: { strings … } })
 */
Bundle.prototype.getStrings = function getStrings(opts, cb) {
  assert(opts, 'Need to provide opts');
  assert(opts.languageId, 'Need to provide opts.languageId');
  this.gp.restCall('bundle.getResourceStrings',{
    serviceInstanceId: this.serviceInstance, bundleId: this.id,
    languageId: opts.languageId,
    fallback: opts.fallback || false,
    fields: opts.fields
  }, cb);
};

/**
 * Alias.
 * @ignore
 */
Bundle.prototype.getResourceStrings = Bundle.prototype.getStrings;

/**
 * Alias.
 * @ignore
 */
Bundle.prototype.getResourceEntryInfo = Bundle.prototype.getEntryInfo;

/**
 * Fetch one entry's info
 * Deprecated, but won't be removed. This is called by Bundle~entry()
 * @param {Object} opts - options
 * @param {String} opts.languageId - language to fetch
 * @param {String} opts.resourceKey - resource to fetch
 * @param {ResourceEntry~getInfoCallback} cb - callback (err, { resourceEntry: { updatedBy, updatedAt, value, sourceValue, reviewed, translationStatus, metadata, partnerStatus } }  )
 * @ignore
 */
Bundle.prototype.getEntryInfo = function getEntryInfo(opts, cb) {
  assert(opts, 'Need to provide opts');
  this.gp.restCall('bundle.getResourceEntryInfo',{
    serviceInstanceId: this.serviceInstance, bundleId: this.id,
    languageId: opts.languageId,
    resourceKey: opts.resourceKey
  }, cb);
};

/**
 * Create an entry object. Doesn't fetch data, 
 * @see ResourceEntry~getInfo
 * @param {Object} opts - options
 * @param {String} opts.languageId - language
 * @param {String} opts.resourceKey - resource key
 */
Bundle.prototype.entry = function entry(opts) {
    assert(opts, 'need to provide opts');
    return new ResourceEntry(this, opts);
};

/**
 * Called by entries()
 * @callback Bundle~listEntriesCallback
 * @param {object} err - error, or null
 * @param {Object.<string,ResourceEntry>} entries - map from resource key to ResourceEntry object. 
 * The .value field will be filled in with the string value.
 */

/**
 * List entries. Callback is called with a map of 
 * resourceKey to ResourceEntry objects.
 * 
 * @param {Object} [opts={}] - ignored
 * @param {listEntriesCallback} cb - Callback with (err, map of resourceKey:ResourceEntry )
 */
Bundle.prototype.entries = function entries(opts, cb) {
  // opts is optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  var that = this;
  this.getStrings(opts, function(err, resResult) {
    if(err) return cb(err);
    var entries = {};
    for(var kid in resResult.resourceStrings) {
      entries[kid] = that.entry({languageId: opts.languageId, resourceKey: kid});
      entries[kid].value = resResult.resourceStrings[kid];
    }
    return cb(null, entries);
  });
};

/**
 * Upload resource strings, replacing all current contents for the language
 * @param {Object} opts - options
 * @param {String} opts.languageId - language to update
 * @param {Object.<string,string>} opts.strings - strings to update
 * @param {basicCallback} cb
 */
Bundle.prototype.uploadStrings = function uploadResourceStrings(opts, cb) {
  assert(opts, 'Need to provide opts');
  assert(opts.languageId, 'Need to provide opts.languageId');
  assert(opts.strings, 'Need to provide opts.strings');
  this.gp.restCall("bundle.uploadResourceStrings",
  {
    serviceInstanceId: this.serviceInstance, bundleId: this.id,
    languageId: opts.languageId,
    body: opts.strings
  }, cb);
};

/**
 * Alias
 * @ignore
 */
Bundle.prototype.uploadResourceStrings = Bundle.prototype.uploadStrings;

/**
 * @param {Object} opts - options
 * @param {array} opts.targetLanguages - optional: list of target languages to update
 * @param {boolean} opts.readOnly - optional: set this bundle to be readonly or not
 * @param {object} opts.metadata - optional: metadata to update
 * @param {string} opts.partner - optional: partner id to update
 * @param {basicCallback} cb - callback
 */
Bundle.prototype.update = function updateBundle(opts, cb) {
  assert(opts, 'Need to provide opts');
  if(!opts.body) {
      opts.body = {
        targetLanguages: opts.targetLanguages,
        readOnly: opts.readOnly,
        metadata: opts.metadata,
        partner: opts.partner
    };
  }
  this.gp.restCall('bundle.updateBundle',
  {
    serviceInstanceId: this.serviceInstance, bundleId: this.id,
    body: opts.body
  }, cb);
};

/**
 * Alias
 * @ignore
 */
Bundle.prototype.updateBundle = Bundle.prototype.update;

/**
 * Update some strings in a language.
 * @param {Object} opts - options
 * @param {Object.<string,string>} opts.strings - strings to update.
 * @param {String} opts.languageId - language to update
 * @param {Boolean} opts.resync - optional: If true, resynchronize strings 
 * in the target language and resubmit previously-failing translation operations
 * @param {basicCallback} cb
 */
Bundle.prototype.updateStrings = function updateResourceStrings(opts, cb) {
  assert(opts, 'need to provide opts');
  this.gp.restCall('bundle.updateResourceStrings',
  {
    languageId: opts.languageId,
    serviceInstanceId: this.serviceInstance,
    bundleId: this.id,
    body: opts.strings,
    resync: opts.resync
  }, cb);
};


/**
 * Alias.
 * @ignore
 */
Bundle.prototype.updateResourceStrings = Bundle.prototype.updateStrings;

/**
 * Called by ResourceEntry.update
 * @param {Object} opts - options
 * @param {string} opts.body.value - string value to update
 * @param {boolean} opts.body.reviewed - optional boolean indicating if value was reviewed
 * @param {object} opts.body.metadata - optional metadata to update
 * @param {string} opts.body.partnerStatus - translation status maintained by partner
 * @param {basicCallback} cb
 * @ignore
 */
Bundle.prototype.updateEntryInfo = function updateResourceEntryInfo(opts, cb) {
  assert(opts, 'need to provide opts');
  if(!opts.body) { // compatibility
      opts.body = {
        value: opts.value,
        reviewed: opts.reviewed,
        metadata: opts.metadata,
        partnerStatus: opts.partnerStatus
    };
  }
  this.gp.restCall('bundle.updateResourceEntryInfo',
  {
    serviceInstanceId: this.serviceInstance, 
    bundleId: this.id,
    languageId: opts.languageId,
    resourceKey: opts.resourceKey,
    body: opts.body
  }, cb);
};

/**
 * Alias.
 * @ignore
 */
Bundle.prototype.updateResourceEntryInfo = Bundle.prototype.updateEntryInfo;


/**
 * Create a bundle access object.
 * This doesn’t create the bundle itself, just a handle object.
 * Call create() on the bundle to create it.
 * @param {Object} opts - String (id) or map {id: bundleId, serviceInstance: serviceInstanceId}
 * @return {Bundle}
 */
Client.prototype.bundle = function bundle(opts) {
  if(typeof(opts) === "string") {
    opts = {id: opts};
  }
  return new Bundle(this, opts);
};

/**
 * Create a user access object.
 * This doesn’t create the user itself,
 * nor query the server, but is just a handle object.
 * Use createUser() to create a user.
 * @param {Object} id - String (id) or map {id: bundleId, serviceInstance: serviceInstanceId}
 * @return {User}
 */
Client.prototype.user = function user(id) {
  var opts;
  if(typeof(id) === "object") {
    opts = id;
  } else {
    // user passed in a string as the id
    opts = {
        id: id,
        serviceInstance: this.serviceInstance
    }; // common case, so we name the param id
  }
  // compatibility
  if(!opts.userId) {
    opts.userId = opts.id;
  } else if (!opts.id) {
    opts.id = opts.userId;
  }  
  
  assert(typeof(opts.userId) === "string",'Expected opts.userId to be a string but got ' + JSON.stringify(opts.userId));
  return new User(this, opts);
};

/**
 * Called by users()
 * @callback Client~listUsersCallback
 * @param {object} err -  error, or null
 * @param {Object.<string,User>} users - map from user ID to User object
 * @see User
 */

/**
 * List users. Callback is called with an array of 
 * user access objects.
 * 
 * @param {Object} [opts={}] - options
 * @param {Client~listUsersCallback} cb - callback
 */
Client.prototype.users = function users(opts, cb) {
  // make 'opts' optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  var serviceInstance = this.getServiceInstance(opts);
  var that = this;
  this.restCall('user.getUsers',
  {
      serviceInstanceId: serviceInstance
  }, function(err, data) {
      if(err) return cb(err);
      var users = {};
      for(var uid in data.users) {
          var u = data.users[uid]; // full user info
          u.serviceInstance = serviceInstance; // copy this
          users[uid] = that.user(u);
      }
      return cb(null, users);
  });
};

/**
 * Bundle list callback
 * @callback Client~listBundlesCallback
 * @param {object} err -  error, or null
 * @param {Object.<string,Bundle>} bundles - map from bundle ID to Bundle object
 */

/**
 * List bundles. Callback is called with an map of 
 * bundle access objects.
 * 
 * @param {Object} [opts={}] - options
 * @param {Client~listBundlesCallback} cb - given a map of Bundle objects
 */
Client.prototype.bundles = function bundles(opts, cb) {
  // make opts optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  var serviceInstance = this.getServiceInstance(opts);
  var that = this;
  this.restCall('bundle.getBundleList',
  {
      serviceInstanceId: serviceInstance
  }, function(err, data) {
      if(err) return cb(err);
      var bundles = {};
      for(var n in data.bundleIds) {
          var bid = data.bundleIds[n];
          bundles[bid] = that.bundle({id: bid, serviceInstance: serviceInstance});
      }
      return cb(null, bundles);
  });
};

/**
 * for testing. Internal.
 * @ignore
 */
Client.prototype._getUrl = function _getUrl(u) {
  return exports._normalizeUrl(u || this._options.credentials.url);
};

/**
 * Note: this constructor is not usually called directly, use Client.user(id)
 * @class User
 * @param {Client} gp - parent Client object
 * @param {Object} props - properties to inherit
 * 
 * @prop {String} id - the userid
 * @prop {String} updatedBy - gives information about which user updated this user last
 * @prop {Date} updatedAt - the date when the item was updated
 * @prop {String} type - `ADMINISTRATOR`, `TRANSLATOR`, or `READER`
 * @prop {String} displayName - optional human friendly name
 * @prop {Object.<string,string>} metadata - optional user-defined data
 * @prop {Boolean} serviceManaged - if true, the GP service is managing this user 
 * @prop {String} password - user password
 * @prop {String} comment - optional user comment
 * @prop {String} externalId - optional User ID used by another system associated with this user
 * @prop {Array.<string>} bundles - list of bundles managed by this user
 */
function User(gp, props) {
  _initSubObject(this, gp, props);
  this.user = this; // compatibility.
  assert(this.id, "Property 'id' missing (user ID)");
  
  // fixups:
  if( this.updatedAt ) {
      this.updatedAt = new Date(this.updatedAt);
  }
}

/**
 * Update this user. 
 * All fields of opts are optional. For strings, falsy = no change, empty string `''` = deletion.
 * 
 * @param {object} opts - options
 * @param {string} opts.displayName - User's display name - falsy = no change, empty string `''` = deletion.
 * @param {string} opts.comment - optional comment - falsy = no change, empty string '' = deletion.
 * @param {Array.<string>} opts.bundles - Accessible bundle IDs.
 * @param {object.<string,string>} opts.metadata - User defined user metadata containg key/value pairs. 
 * Data will be merged in. Pass in `{}` to erase all metadata.
 * @param {string} opts.externalId - User ID used by another system associated with this user - falsy = no change, empty string '' = deletion.
 * @param {basicCallback} cb - callback with success or failure
 */
User.prototype.update = function update(opts, cb) {
    var that = this;
    that.gp.restCall('user.updateUser', {
        serviceInstanceId: this.gp.getServiceInstance(this),
        userId: that.id,
        body: {
            displayName: opts.displayName || undefined,
            comment: opts.comment || undefined,
            bundles: opts.bundles || undefined,
            metadata: opts.metadata || undefined,
            externalId: opts.externalId || undefined
        }
    }, function(err, data) {
       if(err) return cb(err);
        cb(null, that.gp.user(data.user));
    });
};

/**
 * Delete this user. 
 * Note that the service managed user
 * (the initial users created by the service) may not be
 *  deleted.
 * @param {Object} [opts={}] - options
 * @param {basicCallback} cb - callback with success or failure
 */
User.prototype.delete = function userDelete(opts, cb) {
  // make opts optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  opts.userId = opts.userId || this.id;
  opts.serviceInstance = opts.serviceInstance || opts.serviceInstanceId || this.gp.getServiceInstance({serviceInstance: this.serviceInstance});
  this.gp.deleteUser(opts, cb);
};


/**
 * Fetch user info.
 * The callback is given a new User instance, with
 * all properties filled in.
 * @param {Object} opts - optional, ignored
 * @param {User~getUserCallback} cb - called with updated info
 */
User.prototype.getInfo = function getInfo(opts, cb) {
    var that = this;
    if(!cb) {
        cb = opts;
        opts = {};
    }
    that.gp.restCall('user.getUser', {
        serviceInstanceId: that.gp.getServiceInstance({serviceInstance:that.serviceInstance}),
        userId: that.id
    }, function(err, data) {
       if(err) return cb(err);
         // Always return at least an empty metadata object
         data.user.metadata = data.user.metadata || {};
        cb(null, that.gp.user(data.user));
    });
};

/**
 * Callback called by Client~createUser() and User~getInfo()
 * @callback User~getUserCallback
 * @param {object} err -  error, or null
 * @param {User} user - On success, the new or updated User object.
 */

/**
 * Note: this constructor is not usually called directly, use Bundle.entry(...)
 * @class ResourceEntry
 * Creating this object does not modify any data.
 * @param {Bundle} bundle - parent Bundle object
 * @param {Object} props - properties to inherit
 * 
 * @prop {String} resourceKey - key for the resource
 * @prop {string} updatedBy - the user which last updated this entry
 * @prop {Date} updatedAt - when this entry was updated
 * @prop {string} value - the translated value of this entry
 * @prop {string} sourceValue - the source value of this entry
 * @prop {boolean} reviewed - indicator of whether this entry has been reviewed
 * @prop {string} translationStatus - status of this translation: 
 * `source_language`, `translated`, `in_progress`, or `failed`
 * @prop {Object.<string,string>} entry.metadata - user metadata for this entry
 * @prop {string} partnerStatus - status of partner integration
 * @prop {number} sequenceNumber - relative sequence of this entry
 * 
 * @see Bundle~entries
 */
function ResourceEntry(bundle, props) {
  _initSubObject(this, bundle.gp, props);
  this.bundle = bundle;
  assert(this.resourceKey, "Property 'resourceKey' missing (user ID)");
  
  // fixups:
  if( this.updatedAt ) {
      this.updatedAt = new Date(this.updatedAt);
  }
}

/**
 * Load this entry's information. Callback is given
 * another ResourceEntry but one with all current data filled in.
 * @param {Object} [opts={}] - options
 * @param {ResourceEntry~getInfoCallback} cb - callback (err, ResourceEntry)
 */
ResourceEntry.prototype.getInfo = function resourceEntryGetInfo(opts, cb) {
  // make 'opts' optional
  if(!cb) {
      cb = opts;
      opts = {};
  }
  var that = this;
  this.bundle.getEntryInfo({
    languageId: this.languageId,
    resourceKey: this.resourceKey
  }, function(err, data) {
      if(err) return cb(err);
      // monkeypatch 
      data.resourceEntry.languageId = that.languageId;
      data.resourceEntry.resourceKey = that.resourceKey;
      // Always return at least an empty metadata object
      data.resourceEntry.metadata = data.resourceEntry.metadata || {};
      
      cb(null, new ResourceEntry(that.bundle, data.resourceEntry));
  });
};

/**
 * Callback called by ResourceEntry~getInfo()
 * @callback ResourceEntry~getInfoCallback
 * @param {object} err -  error, or null
 * @param {ResourceEntry} entry - On success, the new or updated ResourceEntry object.
 */

/**
 * Update this resource entry's fields.
 * @param {string} opts.value - string value to update
 * @param {boolean} opts.reviewed - optional boolean indicating if value was reviewed
 * @param {object} opts.metadata - optional metadata to update
 * @param {string} opts.partnerStatus - translation status maintained by partner
 * @param {string} opts.sequenceNumber - sequence number of the entry (only for the source language)
 */
ResourceEntry.prototype.update = function resourceEntryUpdate(opts, cb) {
    this.bundle.updateEntryInfo({
        
        // from this
        languageId: this.languageId,
        resourceKey: this.resourceKey,
        
        // parameters
        body: {
            value: opts.value,
            reviewed: opts.reviewed,
            metadata: opts.metadata,
            partnerStatus: opts.partnerStatus,
            sequenceNumber: opts.sequenceNumber
        }
  }, cb);
};

// -----------------------------

var assert = require('assert');
var version = "v2"; // REST version
var debugURL = false;
var debugREST = false;

/**
 * @ignore
 */
exports._normalizeUrl = function _normalizeUrl(u) {
  u = removeTrailing(u,"/"); // take off the trailing slash
  u = removeTrailing(u,"/rest"); // take off the trailing /rest
  return u;
}

/**
 * Remove trailing text from a string
 * @param {string} str - Input string
 * @param {string} chr - Text to be removed
 * @ignore
 */
function removeTrailing(str, chr) {
  if (!str || (str=="")) return str;
  var newIdx = str.length-chr.length;
  if(newIdx < 0) return str;
  if (str.substring(newIdx, str.length) == chr) {
    return str.substring(0, newIdx);
  } else {
    return str;
  }
};

/**
 * Copy all properties from props to o
 * @param {object} o - target of properties
 * @param {object} props - source of properties (map)
 * @ignore
 */
function _copyProps(o, props) {
  if ( props ) {
    // copy properties to this
    for(var k in props) {
      o[k] = props[k];
    }
  }
}

/**
 * Init a subsidiary client object from a Client
 * @param {Object} o - client object to init
 * @param {Client} gp - parent g11n-pipeline client object
 * @param {Object} props - properties to inherit
 * @ignore
 */
function _initSubObject(o, gp, props) {
  _copyProps(o, props);
  o.gp = gp; // actually Client
  o.serviceInstance = gp.getServiceInstance(o); // get the service instance ID
}
