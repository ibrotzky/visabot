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

/**
 * see https://github.com/cloudfoundry-community/node-cfenv/issues/3
 * @author Steven R. Loomis
 * @module cfenv-credsbylabel
 * @ignore
 */


/**
 * like getService, but match the label
 * see https://www.npmjs.com/package/cfenv#appenv-getservice-spec
 * This is implemented by calling appEnv.getServices() 
 *  - see https://www.npmjs.com/package/cfenv#appenv-getservices
 */
var getServiceByLabel =
module.exports.getServiceByLabel = 
function getServiceByLabel(appEnv, regex) {
    var svcs = appEnv.getServices();
    /* istanbul ignore next */
    if(!svcs) return null;
    
    for(var svc in svcs) {
        if(regex.test(svcs[svc].label)) {
            return svcs[svc];
        }
    }
    return null;
};

/**
 * like getServiceCreds, but match the label
 * see https://www.npmjs.com/package/cfenv#appenv-getservicecreds-spec
 */
var getServiceCredsByLabel =
module.exports.getServiceCredsByLabel = 
function getServiceCredsByLabel(appEnv, regex) {
    var svc = getServiceByLabel(appEnv,regex);
    if(svc) {
        return svc.credentials || /* istanbul ignore next */{};
    } else {
        return null;
    }
};
