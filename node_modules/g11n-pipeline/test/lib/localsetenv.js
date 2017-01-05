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

var fs = require('fs');

var alreadyRead = {};

function tryRead(fn) {
	// read each file only once.
	if(alreadyRead[fn]) return;
	alreadyRead[fn]=true;

	var fc;
    try {
        fs.accessSync(fn);
    } catch(e) {
		console.log('# Missing (ignored): ' + fn);
        return;
    }
	try {
		fc = fs.readFileSync(fn);
	} catch(e) {
		console.error('# Error reading: ' + fn + ' ('+e+')');
	}
	if(fc) {
		console.log('# Using: '+fn);
		fc.toString().split(/[\n\r]/).forEach(function(s) {
			if(!s || s[0]===('#') || (s==='') ) return;
			var e = s.split('=');
			if(process.env.hasOwnProperty(e[0])) {
				// console.log('Not overriding $'+e[0] +' with entry from ' +fn);
			} else {
				process.env[e[0]] = e[1];
				console.log(e[0]+"="+e[1]);
			}
		});
	}
}

function applyLocal() {
	tryRead('local-test.properties');
	tryRead('test.properties');
}

module.exports = {
	tryRead: tryRead,
	applyLocal: applyLocal
};
