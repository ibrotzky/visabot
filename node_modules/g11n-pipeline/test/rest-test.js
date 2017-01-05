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

// Low Level test of GAAS API

// load locals
require('./lib/localsetenv').applyLocal();

var projectId = process.env.GP_PROJECT  || 'MyLLProject'+Math.random();
var projectId2 = process.env.GP_PROJECT2 || 'MyOtherLLProject'+Math.random();
var CLEANSLATE = false; // CLEANSLATE: assume no other projects
var VERBOSE = process.env.GP_VERBOSE || false;
var d = describe;
if(process.env.NO_REST_TEST) { describe = describe.skip; }

if(VERBOSE) console.dir(module.filename);
var http = require('http');
var minispin = require('./lib/minispin');
var randHex = require('./lib/randhex');
var expect = require('chai').expect;

var assert = require('assert');

var gaas = require('../index.js');

var gaasTest = require ('./lib/gp-test');
var opts = {credentials: gaasTest.getCredentials()};
var isAdmin = opts.credentials.isAdmin; // admin creds available?
var gaasClient = gaas.getClient(opts);
var basicOpts = {basicAuth: true, credentials: gaasTest.getCredentials()};
var basicClient = gaas.getClient(basicOpts);
var url = opts.credentials.uri;

var sourceLoc = "en-US";
var targLoc = "zh-Hans";

var sourceData = {
    "key1": "First string to translate",
    "key2": "Second string to translate"
};

if ( ! url ) {
  url = gaasClient._getUrl(); // fetch the URL
}

var http_or_https = require('./lib/byscheme')(url);

var instanceName = randHex()+'-'+randHex();

var httpUrl = undefined;

if ( url.indexOf('https:') === 0 ) {
  httpUrl = 'http:' + url.substring(6);
}

describe('Check URL ' + url+'/', function() {
    var urlToPing = url+'/';
    if(VERBOSE) console.dir(urlToPing);
    it('should let us eventually ping ' + urlToPing, function(done) {
      var timeout;
      var http_or_https = require('./lib/byscheme')(urlToPing);
      var t = 200;
      var loopy = function() {
          if(timeout) {
            clearTimeout(timeout);
            timeout = undefined;
          }
          minispin.step();
          try {
            http_or_https.get(urlToPing, // trailing slash to avoid 302
            function(d) {
               if(VERBOSE) console.log(urlToPing + '-> ' + d.statusCode); // dontcare
               if(d.statusCode === 200) {
                 minispin.clear();
                 done();
               } else {
                 timeout = setTimeout(loopy, t);
               }
            }).on('error', function(e) {
              if(VERBOSE) console.dir(e, {color: true});
               timeout = setTimeout(loopy, t);
            });
          } catch(e) {
              if(VERBOSE) console.dir(e, {color: true});
             timeout = setTimeout(loopy, t);
          }
      };
      process.nextTick(loopy); // first run
    });
    
    // verify security headers on landing page
    gaasTest.verifySecurityHeaders(url+'/');

    it('Should let me fetch landing page', function(done) {
    http_or_https.get(url+'/', // trailing slash to avoid 302
                      function(d) {
                        if(VERBOSE) console.log('-> ' + d.statusCode); // dontcare
                         done();
                      })
    .on('error', done);
    });
    var swaggerUrl = url+'/rest/swagger.json';
    gaasTest.expectCORSURL(swaggerUrl); // expect CORS here
    var swaggerUIUrl = url+'/swagger/';
    gaasTest.verifySecurityHeadersSwagger(swaggerUIUrl); // expect CORS here
    var otherUrl = url+'/';
    gaasTest.expectNonCORSURL(otherUrl); // don't expect CORS here
    it('Should let me fetch version page', function(done) {
    http_or_https.get(url+'/version',
        function(res) {
          if(VERBOSE) console.log('-> ' + res.statusCode); // dontcare
          res.on('data', function(d) {
            var data = JSON.parse(d);
            if(VERBOSE) console.dir(data.components[Object.keys(data.components)[0]], {color: true});
            done();
          });
          res.on('error', function(d) {
            done(d);
          });
        })
    .on('error', done);
    });
});

describe('Check HTTP URL', function() {
  if(!httpUrl) {
    it('was not HTTPS - test skipped');
  } else {
    var urlToPing = httpUrl + '/rest/swagger.json';
    it('Should not let me access ' + urlToPing, function(done) {
      http.get(urlToPing,
        function(res) {
          expect(res.statusCode).to.equal(403);
          res.on('data', function(d) {
            try {
              var err = JSON.parse(d);
              expect(err.status).to.equal("ERROR");
              expect(err.message).to.contain('crypt');
              expect(err.message).to.contain('HTTP');
              done();
            } catch(e) {
              done(e);
            }
          });
          res.on('error', function(d) {
            done(d);
          });
        }).on('error', done); // transport err
    });
  } 
});

describe('BASIC auth', function() {
  if(process.env.AUTHENTICATION_SCHEME === 'BASIC') {
    it('is allowed, AUTHENTICATION_SCHEME=BASIC', function(done) {
      basicClient.ready(null, done);
    });
  } else if(!isAdmin) {
    it('is allowed to be ready, normal user.', function(done) {
      basicClient.ready(null, done);
    });
    it('is allowed, to ping as normal user.', function(done) {
      basicClient.ping(null, done);
    });
    it('is NOT allowed, to list bundles as normal user.', function(done) {
      basicClient.getBundleList({}, function(err, x) {
        if(err) done();
        else {
          done(Error('Expected to fail but worked: ' + x));
        }
      });
    });
    } else it('should NOT become ready', function(done) {
    basicClient.ready(null, function(err) {
      if(err) {
        done();
      } else {
        done(Error('Expected Basic Authentication to not be allowed.'))
      }
    });
  });
});

describe('client.apis', function() {
  it('should become ready', function(done) {
    gaasClient.ready(null, done);
  });
  it('should have APIs', function(done) {
    gaasClient.ready(done, function(err, done) {
      if(err) { done(err); return; }
      expect(gaasClient.apis()).to.include.keys('help');
      // Verify the APIs are as expected.
      if(isAdmin) {
        expect(gaasClient.apis()).to.include.keys('bundle','config','instance','service','user','admin');
      } else {
        expect(gaasClient.apis()).to.include.keys('bundle','config','instance','service','user');
      }
      done();
    });
  });
  
  it('should let me get service info', function(done) {
    gaasClient.ready(done, function(err, done, apis) {
      if(err) { done(err); return; }
      apis.service.getServiceInfo({}, function(o) {
        // console.dir(o, {color:true, depth:null});
        expect(o.status).to.equal(200);
        expect(o.obj.status).to.equal('SUCCESS');
        expect(o.obj.supportedTranslation).to.include.keys('en');
        done();
      });
    });
  });
});


