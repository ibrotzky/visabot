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

require('./lib/localsetenv').applyLocal();

var gaasTest = require ('./lib/gp-test');
var expect = require('chai').expect;

if(process.env.NO_UTIL_TEST) { describe = describe.skip; }

var cfenvUtils = require('../lib/cfenv-credsbylabel');

// from https://www.npmjs.com/package/cfenv#appenv-getservices
var testData = 
{
    "cf-env-test": {
        "name": "cf-env-test",
        "label": "user-provided",
        "tags": [],
        "credentials": {
            "database": "database",
            "password": "passw0rd",
            "url": "https://example.com/",
            "username": "userid"
        },
        "syslog_drain_url": "http://example.com/syslog"
    }
};

var fakeAppEnv = {
    getServices: function() {
        return testData;
    }
};

describe('Testing getServiceCredsByLabel()',function(){
    it('should let me call getServiceByLabel()', function() {
        var svc = cfenvUtils.getServiceByLabel(fakeAppEnv, /user.*/);
        expect(svc).to.be.ok;
        expect(svc.credentials).to.be.ok;
        expect(svc.name).to.equal('cf-env-test');
        expect(svc.label).to.equal('user-provided');
    });
    it('Should let me call getServiceCredsByLabel()', function() {
        var cred = cfenvUtils.getServiceCredsByLabel(fakeAppEnv, /user.*/);
        expect(cred).to.be.ok;
        expect(cred.database).to.equal('database');
        expect(cred.password).to.equal('passw0rd');
        expect(cred.url).to.equal('https://example.com/');
        expect(cred.username).to.equal('userid');
    });
    it('should let me call getServiceByLabel(/nomatch/)', function() {
        var svc = cfenvUtils.getServiceByLabel(fakeAppEnv, /nomatch.*/);
        expect(svc).to.not.be.ok;
    });
    it('Should let me call getServiceCredsByLabel(/nomatch/)', function() {
        var cred = cfenvUtils.getServiceCredsByLabel(fakeAppEnv, /nomatch.*/);
        expect(cred).to.not.be.ok;
    });
});
