Testing the JavaScript Client for IBM Bluemix Globalization-as-a-Service
===
<!--
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
-->

# This document describes how to test the `g11n-pipeline` client code.

*NOTE/WARNING:* Running the tests will create and delete various translation projects in your service!
Don't run this test against an account with valuable data.
Running this test may incur service costs.

* create a `local-credentials.json` file with the credentials
as given in the bound service (but see below)

      {
        "credentials": {
          "url": "https://…",
          "userId": "…",
          "password": "……",
          "instanceId": "………"
        }
      }


    
* install [node](http://nodejs.org)
* `npm install`
* `npm test`


# OTHER CONFIG OPTIONS

    # in lieu of a local-credentials.json file, you can set the
    # following keys:
    GAAS_API_URL=https://…
    GAAS_INSTANCE=12345
    GAAS_USER=67890
    GAAS_PASSWORD=hunter42

    # set this to skip the 'REST' test
    NO_REST_TEST=true
    
    # set this to skip the 'Client' test
    NO_CLIENT_TEST=true

    # set this to skip the 'utilities' tests (everything else)
    NO_UTIL_TEST=true
        
    # set this for extra verbosity
    GP_VERBOSE=true
    
    # set this to NOT delete the bundle in the client test at the end
    NO_DELETE=true
    
    # an alternate project name (else random)
    GP_PROJECT=someproject
    
    # is the @DELAY@ option available? (normally false)
    DELAY_AVAIL=false

# Internal Development Use

If you are developing the Globalization Pipeline service,
you may want to use the following parameters also.

    # Set these, and don't set any user credentials.
    # The test will create/delete the service instances
    GAAS_ADMIN_ID=mysuperuser
    GAAS_ADMIN_PASSWORD=hunter42
    
    # set this if AUTHENTICATION_SCHEME=BASIC is set on the server
    # assumes that Admins can login with HTTP Basic
    AUTHENTICATION_SCHEME=BASIC

    # specify the URL
    GAAS_API_URL=http://localhost…


LICENSE
===
Apache 2.0. See [LICENSE.txt](LICENSE.txt)
