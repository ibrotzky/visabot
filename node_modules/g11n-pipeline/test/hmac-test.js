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

// High Level test of GAAS API

// load locals
require('./lib/localsetenv').applyLocal();

var expect = require('chai').expect;

var assert = require('assert');

var GaasHmac = require('../lib/gp-hmac.js');

if(process.env.NO_UTIL_TEST) { describe = describe.skip; }

// test of various utilities

describe('lib/gaas-hmac', function() {
	it('Should verify that the name and secret is set properly', function() {
		var myHmac = new GaasHmac('MyAuth', 'MyUser', 'MySecret');
		
		expect(myHmac).to.be.ok;
		expect(myHmac.name).to.be.ok;
		expect(myHmac.name).to.equal('MyAuth');
		expect(myHmac.user).to.be.ok;
		expect(myHmac.user).to.equal('MyUser');
		expect(myHmac.secret).to.be.ok;
		expect(myHmac.secret).to.equal('MySecret');
	});
	it('Should verify hash (JSON content)', function() {
		var myHmac = new GaasHmac('MyAuth', 'MyUser', 'MySecret');
		
		expect(myHmac).to.be.ok;
		expect(myHmac.name).to.be.ok;
		expect(myHmac.name).to.equal('MyAuth');
		
		var obj = {
			method: 'get',
			url: 'http://example.com/gaas',
			headers: {
				Authorization: undefined
			},
			body: { param: 'value' }
		};
		
		// we must force the Date so that we have a consistent test.
		myHmac.forceDateString = "Mon, 30 Jun 2014 00:00:00 GMT"; // Bluemix launch date
		expect(myHmac.apply(obj)).to.be.true;

		expect(obj.headers.Authorization).to.be.ok;
		expect(obj.headers.Authorization).to.equal(
			'GaaS-HMAC MyUser:C+0WoqztV6Go5Ttu05y2jcCD450=');
		expect(obj.headers.Date).to.be.ok;
		expect(obj.headers.Date).to.equal(myHmac.forceDateString);
	});
	it('Should verify that we can apply with a string body (golden test)', function() {
		var myHmac = new GaasHmac('MyAuth', 'MyUser', 'MySecret');
		
		expect(myHmac).to.be.ok;
		expect(myHmac.name).to.be.ok;
		expect(myHmac.name).to.equal('MyAuth');
		
		var obj = {
			method: 'pOsT',
			url: 'https://example.com/gaas',
			headers: {
				Authorization: undefined
			},
			body: '{"param":"value"}'
		};
		
		// we must force the Date so that we have a consistent test.
		myHmac.forceDateString = "Mon, 30 Jun 2014 00:00:00 GMT"; // Bluemix launch date
		expect(myHmac.apply(obj)).to.be.true;

		expect(obj.headers.Authorization).to.be.ok;
		expect(obj.headers.Authorization).to.equal(
			'GaaS-HMAC MyUser:ONBJapYEveDZfsPFdqZHQ64GDgc=');
		expect(obj.headers.Date).to.be.ok;
		expect(obj.headers.Date).to.equal(myHmac.forceDateString);
	});
	it('Should verify that we can apply with undefined body', function() {
		var myHmac = new GaasHmac('MyAuth', 'MyUser', 'MySecret');
		
		expect(myHmac).to.be.ok;
		expect(myHmac.name).to.be.ok;
		expect(myHmac.name).to.equal('MyAuth');
		
		var obj = {
			method: 'put',
			url: 'http://example.com/gaas',
			headers: {
				Authorization: undefined
			},
			body: undefined
		};
		
		// we must force the Date so that we have a consistent test.
		myHmac.forceDateString = "Mon, 30 Jun 2014 00:00:00 GMT"; // Bluemix launch date		expect(myHmac.apply(obj)).to.be.true;
		expect(myHmac.apply(obj)).to.be.true;

		expect(obj.headers.Authorization).to.be.ok;
		expect(obj.headers.Authorization).to.equal(
			'GaaS-HMAC MyUser:VutLpxSxYtUZKLJPV8eqRU3Spxw=');
		expect(obj.headers.Date).to.be.ok;
		expect(obj.headers.Date).to.equal(myHmac.forceDateString);
	});
	it('Should verify that we can apply with a random date', function() {
		var myHmac = new GaasHmac('MyAuth', 'MyUser', 'MySecret');
		
		expect(myHmac).to.be.ok;
		expect(myHmac.name).to.be.ok;
		expect(myHmac.name).to.equal('MyAuth');
		
		var obj = {
			method: 'delete',
			url: 'http://example.com/gaas',
			headers: {
				Authorization: undefined
			},
			body: undefined
		};
		
		expect(myHmac.apply(obj)).to.be.true;

		expect(obj.headers.Authorization).to.be.ok;
		expect(obj.headers.Authorization.length).to.not.equal(0);
		// can't test the auth header against a static string
		expect(obj.headers.Date).to.be.ok;
		expect(obj.headers.Date.length).to.not.equal(0);
		expect(obj.headers.Date).to.contain(" GMT");
	});
});
