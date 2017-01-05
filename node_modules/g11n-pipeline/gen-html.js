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

var marked = require('marked');
var fs = require('fs');

var header = '<!DOCTYPE html> <html><head><meta charset="UTF-8"><title>Globalization Pipeline Node.js SDK</title>'+
    '<link rel="stylesheet" href="node_modules/github-markdown-css/github-markdown.css"></head><body style="padding: 1em;" class="markdown-body">\n';
var footer = '</body>\n';

var ifn = 'README.md';
var ofn = 'README.html';

marked(fs.readFileSync(ifn,'utf-8').toString(), {}, function (err, content) {
    if(err) throw err;
    fs.writeFile(ofn,
        header + content + footer, function(err) {
            if(err) throw err;
            console.log('Wrote ' + ofn);
        });
});
