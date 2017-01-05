/*	
 * Copyright IBM Corp. 2015
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
var url = require('url');
var expect = require('chai').expect;
var byscheme = require('./byscheme');
var optional = require('optional');
var fs = require('fs');

var localCredsFile = './local-credentials.json';
var localCredentials;

try {
    fs.accessSync(localCredsFile);
} catch (e) {
    console.log('# Missing (ignored): ' + localCredsFile);
    localCredsFile = null;
}

if(localCredsFile) {
    localCredentials = optional(localCredsFile);
    if(!localCredentials) {
        throw Error('could not read (check for malformed JSON) ' + localCredsFile);
    }
}

/**
 * Acceptable source language.
 */
module.exports.SOURCES = [ 'en' ];

/**
 * Acceptable target languages.
 */
module.exports.TARGETS = [ 'it', 'ko' ];

/**
 * Pseudo language doing Cyrillic transliteration
 */
module.exports.CYRILLIC = 'qru';

/**
 * Language not covered by translation, yet
 */
module.exports.KLINGON = 'tlh';

/**
 * Language that always fails
 */
module.exports.NOLANG = 'zxx';

var VERBOSE=false;

module.exports.getCredentials = function getCredentials() {
  var creds;
  if (localCredentials && localCredentials.credentials) {
    creds =  localCredentials.credentials;
  } else {
    creds = {
      // api_key: apiKeyEnv,
      url: process.env.GP_URL || process.env.GAAS_API_URL || null,
      instanceId: process.env.GP_INSTANCE_ID || process.env.GAAS_INSTANCE_ID || null /*admin*/,
      userId: process.env.GP_ADMIN_ID || process.env.GAAS_ADMIN_ID || process.env.GAAS_USER_ID || null,
      password: process.env.GP_ADMIN_PASSWORD || process.env.GAAS_ADMIN_PASSWORD || process.env.GAAS_PASSWORD || null,
      isAdmin: ((process.env.GP_ADMIN_ID || process.env.GAAS_ADMIN_ID)!=null)?true:false
    };
  }
  if(VERBOSE) console.dir(creds);
  return creds;
};

function expectHeaders(res, h) {
  Object.keys(h).forEach(function(header) {
    expect(res.headers).to.contain.key(header);
    expect(res.headers[header]).to.equal(h[header]);
  });
}

var cspStrict = {
  'content-security-policy': "default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self'; font-src 'self';"
};

var cspSwagger = {
  'content-security-policy': "default-src 'none'; script-src 'self' 'unsafe-inline'; connect-src 'self'; img-src 'self'; style-src 'self' 'unsafe-inline'; font-src 'self';"
};

var securityHeaders = module.exports.securityHeaders = {
  'x-content-type-options': 'nosniff',
  'x-xss-protection': '1',
  'x-frame-options': 'deny',
  'frame-options': 'deny',
  'cache-control': 'no-store, no-cache=\"set-cookie\"',
  'pragma': 'no-cache',
};

function expectSecurityHeaders(res) {
  expectHeaders(res, securityHeaders);
  expectHeaders(res, cspStrict);
}


function expectSecurityHeadersSwagger(res) {
  expectHeaders(res, securityHeaders);
  expectHeaders(res, cspSwagger);
}

var hstsHeaders = module.exports.hstsHeaders = {
  'strict-transport-security': 'max-age=3600'
};

function expectHSTS(res) {
  expect(res.headers).to.contain.key('strict-transport-security');
}

function expectResCORSHeaders(res) {
  expect(res.headers).to.be.ok;
  expectSecurityHeaders(res);
  expect(res.headers).to.contain.key('access-control-allow-headers');
  expect(res.headers['access-control-allow-headers'])
    .to.match(/^x-requested-with, Content-Type, api-key, Authorization/); // has Date in some versions
  expect(res.headers).to.contain.key('access-control-allow-methods');
  expect(res.headers['access-control-allow-methods']).to.equal('GET');
  expect(res.headers).to.contain.key('access-control-allow-origin');
  expect(res.headers['access-control-allow-origin']).to.equal('*');
}

function expectResNonCORSHeaders(res) {
  expect(res.headers).to.be.ok;
  expectSecurityHeaders(res);
  expect(res.headers).to.not.contain.key('access-control-allow-headers');
  expect(res.headers).to.not.contain.key('access-control-allow-methods');
  expect(res.headers).to.not.contain.key('access-control-allow-origin');
}

/**
 * Utility function for a GET where CORS is set
 */
function expectResCORSGET(res, done) {
  expectResCORSHeaders(res);
  done();
}

function expectResSecurity(res, done) {
  expect(res.headers).to.be.ok;
  expectSecurityHeaders(res);
  done();
}

/**
 * Utility function for a GET where CORS is set
 */
function expectResNonCORSGET(res, done) {
  expectResNonCORSHeaders(res);
  done();
}


/**
 * Set up an 'options' block for the specified method.
 */
function optionsCreate(swaggerUrl, method) {
  var options = url.parse(swaggerUrl);
  options.url = swaggerUrl;
  options.method = method;
  
  return options;
}

function optionsAuth(options, auth) {
  if(auth) auth(options);
  return options;
}

var methods = ['GET', 'OPTIONS'];
/**
 * verify this URL DOES have CORS and other headers set
 * @param swaggerUrl - url to check
 * @param auth - function(options) -  apply auth to options obj. Can set 'options.auth' or headers, etc
 */
module.exports.expectCORSURL = function expectCORSURL(swaggerUrl, auth, str) {
  if(!str) str = '';
  methods.forEach(function (method) {
    var optionsGet = optionsCreate(swaggerUrl, method);
    it('Should let me ' + method + ' ' + swaggerUrl + ' w/ CORS ' + (auth?' (auth) ':' ') + str, function (done) {
      optionsGet = optionsAuth(optionsGet, auth);
      var oreq = byscheme(swaggerUrl).request(optionsGet,
        function (res) {
          if(method === 'GET') {
            expect(res.statusCode).to.equal(200);
          } else if(method === 'OPTIONS') {
            expect(res.statusCode).to.equal(204);
          }
          expectResCORSGET(res, done);
        })
        .on('error', function(e) {
            // console.dir(e);
            // console.dir(optionsGet);
            done(Error(e.message+':'+e.code));
        });
      oreq.end();
    });
  });
}

/**
 * verify this URL does NOT have CORS, but verify other headers set
 * @param swaggerUrl - url to check
 * @param auth - function(options) -  apply auth to options obj. Can set 'options.auth' or headers, etc
 */
module.exports.expectNonCORSURL = function expectNonCORSURL(swaggerUrl, auth, str) {
  if(!str) str = '';
  methods.forEach(function (method) {
    var optionsGet = optionsCreate(swaggerUrl, method);
    it('NonCORS: Should NOT let me ' + method + ' ' + swaggerUrl + ' w/ CORS' + (auth?' (auth) ':' ') + str, function (done) {
      var oreq = byscheme(swaggerUrl).get(optionsAuth(optionsGet, auth),
        function (res) {
          if(method === 'GET') {
            expect(res.statusCode).to.equal(200);
          } else if(method === 'OPTIONS') {
            expect(res.statusCode).to.equal(200);
          }
          expectResNonCORSGET(res, done);
        })
        .on('error', done);
      oreq.end();
    });
  });
}


/**
 * verify this URL has the 'typical' security headers
 * @param swaggerUrl - url to check
 * @param auth - function(options) -  apply auth to options obj. Can set 'options.auth' or headers, etc
 */
module.exports.verifySecurityHeaders = function verifySecurityHeaders(swaggerUrl, auth, str) {
  if(!str) str = '';
  ['GET'].forEach(function (method) {
    var optionsGet = optionsCreate(swaggerUrl, method);
    it('Sec: Should NOT let me ' + method + ' ' + swaggerUrl + ' w/ CORS' + (auth?' (auth) ':' ') + str, function (done) {
      var oreq = byscheme(swaggerUrl).get(optionsAuth(optionsGet, auth),
        function (res) {
          if(method === 'GET') {
            expect(res.statusCode).to.equal(200);
          } else if(method === 'OPTIONS') {
            expect(res.statusCode).to.equal(200);
          }
          if(swaggerUrl.toString().indexOf('https:')===0) {
            expectHSTS(res);
          }
          expectResSecurity(res, done);
        })
        .on('error', done);
      oreq.end();
    });
  });
}

/**
 * verify this URL has the 'typical' security headers
 * @param swaggerUrl - url to check
 * @param auth - function(options) -  apply auth to options obj. Can set 'options.auth' or headers, etc
 */
module.exports.verifySecurityHeadersSwagger = function verifySecurityHeadersSwagger(swaggerUrl, auth, str) {
  if(!str) str = '';
  ['GET'].forEach(function (method) {
    var optionsGet = optionsCreate(swaggerUrl, method);
    it('Should NOT let me ' + method + ' ' + swaggerUrl + ' w/ CORS' + (auth?' (auth) ':' ') + str, function (done) {
      var oreq = byscheme(swaggerUrl).get(optionsAuth(optionsGet, auth),
        function (res) {
          if(method === 'GET') {
            expect(res.statusCode).to.equal(200);
          } else if(method === 'OPTIONS') {
            expect(res.statusCode).to.equal(200);
          }
          if(swaggerUrl.toString().indexOf('https:')===0) {
            expectHSTS(res);
          }
          expectSecurityHeadersSwagger(res);
          done();
        })
        .on('error', done);
      oreq.end();
    });
  });
}

