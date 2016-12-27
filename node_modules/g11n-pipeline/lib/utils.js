
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


function Fields(f) {
	if(typeof(f)==="string") {
		f = f.split(/[, ]/);
	}
	this.fields = f;
}

/**
 * Given a parameter 'opts' return a 'fields' parameter in comma-separated form.
 */
Fields.prototype.processFields = function processFields(opts) {
  if(!opts) opts = {};
  var fields = [];
  if(opts.fields) {
    fields = opts.fields.split(',');
  }
  for(var i in this.fields) {
    if(opts[this.fields[i]]) {
      fields.push(this.fields[i]);
    }
  }
  opts.fields = fields.join(',');
  if(opts.fields === "") {
    opts.fields = undefined;
  }
  return opts.fields;
}


module.exports = {
	Fields: Fields
};
