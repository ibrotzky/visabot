2016-02-19, Version 21.0.2
==========================

 * Disable browser tests on ubuntu-0.10 (Miroslav Bajtoš)

 * Fix CI detection (Miroslav Bajtoš)

 * Improve browser logging (Miroslav Bajtoš)

 * test: wait for swagger resource to be loaded (Miroslav Bajtoš)


2015-09-09, Version 21.0.1
==========================

 * fix linting error (Ryan Graham)


2015-09-01, Version 21.0.0
==========================

 * Bump up strong-swagger-client version to ^21.0.0 (Miroslav Bajtoš)


2015-08-11, Version 21.0.0-dev.2
================================

 * 21.0.0-dev.2 (Miroslav Bajtoš)

 * Add back src/main/html/css/typography.css (Miroslav Bajtoš)


2015-08-11, Version 21.0.0-dev.1
================================

 * 21.0.0-dev.1 (Miroslav Bajtoš)

 * Add missing jshint dev-dependency (Miroslav Bajtoš)

 * Revert "bump strong-swagger-client dep" (Miroslav Bajtoš)

 * Disable tests on Jenkins slaves w/o browser (Miroslav Bajtoš)

 * bump strong-swagger-client dep (Ryan Graham)

 * Update README.md (Ron)

 * Update CONTRIBUTING.md (Ron)

 * Fix timing issues in unit-tests (Miroslav Bajtoš)

 * Issue #1423: responses containing references to definitions were not being fully resolved when the spec was pulled in over AJAX but was working locally. OperationView.render()'s parsing of the responses was just checking if the ref _started_ with '#/definitions/', not whether it contained it, and when pulled in over AJAX the refs have the URL prepended to them (Aaron Baker)

 * updated versions (Tony Tam)

 * Missing "Headers" translation (Francisco Guimarães)

 * Fix merge (Francisco Guimarães)

 * Missing some data-sw-translation (Francisco Guimarães)

 * dist (Francisco Guimarães)

 * Missing some data-sw-translation at templates (Francisco Guimarães)

 * Build (Francisco Guimarães)

 * merged from swagger-api (Francisco Guimarães)

 * missing some data-sw-translation (Francisco Guimarães)

 * merged (Tony Tam)

 * rebuilt (Tony Tam)

 * Revert "Updating documentation based on issue swagger-ui issue #1414" (Tony Tam)

 * merged to develop_2.0 (Tony Tam)

 * Changes at src (Francisco Guimarães)

 * Translation (Francisco Guimarães)

 * Include pt translation (Francisco Guimarães)

 * Move pt to dist (Francisco Guimarães)

 * Move to dist (Francisco Guimarães)

 * PT localization (Francisco Guimarães)

 * Added built files (sonicd300)

 * Added flexible scope separator (sonicd300)

 * Fix build on Node v0.12 and io.js (Miroslav Bajtoš)

 * Move padding from index.html to CSS (Miroslav Bajtoš)

 * Embed throbber.gif in CSS via data-uri (Miroslav Bajtoš)

 * Upgrade less to 2.5 (latest) (Miroslav Bajtoš)

 * Fix "Expand Operations" link (Miroslav Bajtoš)

 * Use strong-swagger-client instead of swagger-client (Miroslav Bajtoš)

 * Add back maxwidth in standalone mode (Miroslav Bajtoš)

 * Refactored to visualize more parameter and property restrictions (Shelby Sanders)

 * Corrected to replace '/' and '.' in anchors, since they break the shebang logic (Shelby Sanders)

 * Changed to never show Response Class, because it must be in Swagger Spec for Code Gen to work (Shelby Sanders)

 * Added better higlight of required params, with indication other than color/weight, and support for select and read-only (Shelby Sanders)

 * Corrected shebang() to avoid closing Model when called an even number of times (Shelby Sanders)

 * Added option for headersToHide in order to optionally hide arbitrary headers (e.g. Authorization) (Shelby Sanders)

 * Corrected to actually show request headers and body, rearranged sections based on probability of reference (Shelby Sanders)

 * Add default padding for when not embedded (Shelby Sanders)

 * Added better highlighting and padding for preformatted JSON and XML (Shelby Sanders)

 * Corrected styling of title and description, and refactored to remove with restrictions (Shelby Sanders)

 * Added support for HTML in title, renamed footer to info_server avoiding conflict with Bootstrap, and corrected replacement of array[ (Shelby Sanders)

 * make wider and show request parts (Nicolas Duchastel de Montrouge)

 * Changed to protect against missing  when checking for file uploads (Shelby Sanders)

 * Added support for basePaths to document across multiple environments (Shelby Sanders)

 * Refactored column widths to better use space for likely growing content (Shelby Sanders)

 * Added support for anchoring to first reference of a Model (Shelby Sanders)

 * Defer creation of signature and sampleJSON, so all Models will be loaded (Shelby Sanders)

 * Omit divs for info.title and info.description if they're absent (Shelby Sanders)

 * Removed inappropriate commas from sorters (Shelby Sanders)

 * Corrected to avoid showing invalid Model and Model Schema is missing or void (Shelby Sanders)

 * Added guard against null mockSignature (Shelby Sanders)

 * Removed 'Implementation Notes' label since it's just noise, widened Resource expansion anchor to full label (Shelby Sanders)

 * Corrected to work when loading resources from file:// (Shelby Sanders)

 * Added support for primitives in StatusCodeView (Shelby Sanders)

 * Add support for toggling Model and Schema, instead of just expanding (Shelby Sanders)

 * Ensure Response Content Type is shown regardless of Response Class (Shelby Sanders)

 * Collapse and label responseModel description by default (Shelby Sanders)

 * Added support for multiple responseMessages from Swagger 1.2 (Shelby Sanders)

 * Changed to hide the message-bar when no message (Miroslav Bajtoš)

 * Add minimal test (Miroslav Bajtoš)

 * Remove src/main/css from version control. (Miroslav Bajtoš)

 * Remove lib/swagger.js from version control (Miroslav Bajtoš)

 * Rename the module to strong-swagger-ui (Miroslav Bajtoš)

 * Fix project infrastructure (Miroslav Bajtoš)

 * Spelling and closing tag fixes (Taras Katkov)

 * Fix value for window.location.protocol. (Lucian Hontau)

 * Add support for oauth client secret when calling the token URL. Fixes #1384. Fixes #1324. (Lucian Hontau)

 * Updating documentation based on issue swagger-ui issue #1414 (Mike Dalrymple)

 * Improve the language in CONTRIBUTING.md (Andreas Kohn)

 * Use a SPDX-compatible identifier for the 'license' value (Andreas Kohn)

 * regenerated (aurelian)

 * ignore case. (aurelian)

 * X-data-* vendor extension for parameter objects (aurelian)

 * Fix #1394: A bug in IE 10/11 causes the placeholder text to be copied to "value" (Praseeth T)

 * removed superfluous quote (Vladimir L)

 * OAuth flow only selected scopes should be sent to Authorize endpoint #1388. Need to clear out previous popupDialog nodes, so previous checkboxes won't be considered. (Tom Baker)

 * Found a bug in OperationView.js which caused the oAuth toggle to be displayed for non-oAuth endpoints if oAuth was enabled at all, fixed it (Robert Brownstein)

 * Spelling is hard (Rob Richardson)

 * fix lint issue (Josh Ponelat)

 * build with translator support (Josh Ponelat)

 * add lang to gulpfile (Josh Ponelat)

 * add data-sw-translate to templates (Josh Ponelat)

 * Translation to Spanish finished (Ignacio Peluffo)

 * Some words translation added (ipeluffo)

 * Spanish translation added (ipeluffo)

 * removed unnesessary logic, replaced by CSS style (Vladimir L)

 * fixed issue with OAuth hint (Vladimir L)

 * Remove moot `version` property from bower.json dev branch (Kevin Kirsche)


2015-06-06, Version 2.1.0
=========================

 * updated versions, rebuilt (Tony Tam)

 * removed extra tag (Tony Tam)

 * removed logging (Tony Tam)

 * updated version (Tony Tam)

 * Update swagger-ui to reuse client authorizations properly (Jeremy Whitlock)

 * remove dead code: show-wordnik-dev (Josh Ponelat)

 * Updated project location (Ron)

 * Updated ToS (Ron)

 * Updated License (Ron)

 * finish removing bootstrap.min entirely (Josh Ponelat)

 * removed unused bootstrap.min.js file (Shawn Gong)

 * fixed support for default values in param_list (Josh Ponelat)

 * fix for lint errors (Tony Tam)

 * merged (Tony Tam)

 * rebuilt (Tony Tam)

 * rewrote into source files, generate using gulp (Shawn Gong)

 * use $.text instead of $.html for showMessage and onFailure (Josh Ponelat)

 * rewrote using asCurl (Shawn Gong)

 * Update translator.js (bnupaladin)

 * curl hack to showcase curl output in the swagger-ui (Shawn Gong)

 * Update README.md (Ron)

 * updated validator badge logic per #1299 (Tony Tam)

 * remove fileupload function (Vitaliy Kanev)

 * fixed: REFACTOR handleFileUpload (Vitaliy Kanev)

 * 508 Fixes (Joe Wolf)

 * fixes #693 (Nara Kasbergen)

 * fixes #958 (Nara Kasbergen)

 * Add support for GFM syntax in model (schema) descriptions -- relies on one small change to swagger-client (Nara Kasbergen)


2015-05-14, Version 2.1.5-M2
============================

 * rebuilt (Tony Tam)

 * added authorizations (Tony Tam)

 * Just realized this adds some extra horizontal padding which may not be desirable, so this fixes that (Nara Kasbergen)

 * Fixes #1233 (Nara Kasbergen)

 * Fixes #1268 (Nara Kasbergen)

 * Use the correct new sorting parameter name (Nara Kasbergen)


2015-05-13, Version 2.1.4-M2
============================

 * rebuilt (Tony Tam)

 * merged (Tony Tam)

 * updated versions for patch release (Tony Tam)

 * fixed MainView for ES5 browser. (Matthias Le Brun)


2015-05-13, Version 2.1.3-M2
============================

 * updated swagger-js, rebuilt (Tony Tam)

 * fix for #1257 (Tony Tam)


2015-05-08, Version 2.1.2-M2
============================

 * updated swagger-client version (Tony Tam)

 * set highlight threshold per #1184 (Tony Tam)

 * updated per #931 (Tony Tam)

 * rebuilt (Tony Tam)

 * merged #1238 (Tony Tam)

 * merged (Tony Tam)

 * merged logic from #1177 (Tony Tam)

 * fix for #1253 (Tony Tam)

 * added fix from outdated PR #1122 (Tony Tam)

 * Fix for #1252, check for http protocol before setting validator url (Tony Tam)

 * manually added #1086, #1089 (Tony Tam)

 * fixes #1205, remove dead line (Josh Ponelat)

 * uncomment swagger-oauth and add script (Josh Ponelat)

 * add jshint to gulp..lint and dist (Josh Ponelat)

 * fix #1191, forgot to put returns on handlebar helper (Josh Ponelat)

 * Forcing old images to not be CRLF conformed due to new gitattributes file. (Bryan Hazelbaker)

 * created files in dist (Merlin Göttlinger)

 * Fix for #1113 (mgttlinger)

 * updated version (Tony Tam)

 * Use jQuery for trimming (Björn Rochel)

 * Prevent the OperationView to display an error in case the response contains a whitespace only body with content type application/json (Bjoern Rochel)

 * Corrected for Travis build. (Waldek Kozba)

 * Multiple values for array type parameters as separated lines in the textarea. Improved required parameter handling for the array type. Improved default value handling for the array type. (Waldek Kozba)

 * Pass empty object to guard the case when options.swaggerOptions is undefined (aurelian)

 * Pass swagger options to swagger-js Operation#execute method (aurelian)


2015-04-16, Version 2.1.1-M2
============================

 * rebuilt (Tony Tam)

 * merged with master (Tony Tam)

 * removed text files from binary attribute (Tony Tam)

 * updated versions (Tony Tam)

 * updated from master (Tony Tam)

 * Add API Key auth in onComplete callback of SwaggerUI#load call (Mohsen Azimi)

 * Support plain text in response (Mohsen Azimi)

 * Fix issue #1132 "JS error when testing uploadFile method (http://petstore.swagger.io/#!/pet/uploadFile)" (Mohsen Azimi)

 * Fix issue #1160 "piKey is not set when Explore button is clicked " (Mohsen Azimi)

 * Call to swaggerUi.load before possible use. (Sebastian Ortiz)

 * updated client version (Tony Tam)

 * Fix - Swagger/Swashbuckle OAuth2 Authorizations not set #1134 (vprefournier)

 * Update default validator with correct protocol (shuisman)


2015-03-30, Version 2.1.0-M2
============================

 * rebuilt (Tony Tam)

 * fixed binary for images (Tony Tam)

 * replaced corrupt images (Tony Tam)

 * Fix images and add all images as binary to gitattributes (Mohsen Azimi)

 * prepare for release (Tony Tam)

 * Update TravisCI badge to show only master result and remove CoffeeScript references (Mohsen Azimi)

 * updated package, rebuilt (Tony Tam)

 * Cleanup package.json (Mohsen Azimi)

 * removed shred (Tony Tam)

 * updated links (Tony Tam)

 * Fix JSHint issues (Mohsen Azimi)

 * Add link innerText (Mohsen Azimi)

 * Add support for downloading via Blob (Mohsen Azimi)

 * Add file download to try operation (Mohsen Azimi)

 * Update .travis.yml (Tony Tam)

 * xit out tests that are failing due to SwaggerJS (Mohsen Azimi)

 * Use node_modules/.bin for binary executions in scripts of package.json (Mohsen Azimi)

 * Append .json to v1 spec files to enforce Content-Type when serving in test (Mohsen Azimi)

 * Use SwaggerJS from npm (Mohsen Azimi)

 * Fixing sorter typo (Dan Rice)

 * Rename redirectUrl optoon to oauth2RedirectUrl (Mohsen Azimi)

 * Add ability to override redirectUrl in swagger-oauth (Mohsen Azimi)

 * Make SwaggerUi UMD compatible (Mohsen Azimi)

 * Fix typos in SwaggerUi.js (Mohsen Azimi)

 * operation.handlebars: Using a <div> around {{{description}}} is more robust to            potential HTML tags cannot be nested within the <p> context. (Livio Soares)

 * Fix #1040 : add options to be able to sort APIs and operations (Julien Maurel)

 * OperatioView.js: fix rendering of Markdown (GFM) in description fields. (Livio Soares)

 * fixed build error (Tony Tam)

 * merged (Tony Tam)

 * Normalize all the line endings (Mohsen Azimi)

 * Add .gitattributes file (Mohsen Azimi)

 * Encode/decode tags before using it in the URL (Mohsen Azimi)

 * Move  backward compatibility to swaggerUi.js and warn users about API changes (Mohsen Azimi)

 * fix for #968, removed block (Tony Tam)

 * Append swagger-auth global functions to window and don't break backward (Mohsen Azimi)

 * Append `Docs` to window for now (Mohsen Azimi)

 * Remove all references to `swaggerUi` global instance (Mohsen Azimi)

 * Update docs (Mohsen Azimi)

 * Remove global auth object references and fix header api key adding mechanism (Mohsen Azimi)

 * Pass router to all views (Mohsen Azimi)

 * Remove CoffeeScript folder (Mohsen Azimi)

 * Wrap all SwaggerUI code in a iife (Mohsen Azimi)

 * Fix V2 test (Mohsen Azimi)

 * Run  JSHint in TravisCi (Mohsen Azimi)

 * JSHintify test (Mohsen Azimi)

 * Fix all JSHint errors (Mohsen Azimi)

 * Some cleanup in Docs.js (Mohsen Azimi)

 * Remove global views (Mohsen Azimi)

 * Remove [].forEach calls (Mohsen Azimi)

 * Fix binding error in swagger-js file (Mohsen Azimi)

 * remove coffeescript from build process (Mohsen Azimi)

 * Fix JS errors in source (Mohsen Azimi)

 * Move all files to javascript folder and remove coffeescript folder (Mohsen Azimi)

 * Convert view/OperationView.coffee (Mohsen Azimi)

 * Convert view/ParameterView.coffee (Mohsen Azimi)

 * Convert view/ParameterContentTypeView.coffee (Mohsen Azimi)

 * Convert view/ResourceView.coffee (Mohsen Azimi)

 * Convert view/SignatureView.coffee (Mohsen Azimi)

 * Convert view/StatusCodeView.coffee (Mohsen Azimi)

 * Convert view/ResponseContentTypeView.coffee (Mohsen Azimi)

 * Convert view/MainView.coffee (Mohsen Azimi)

 * Convert view/HeaderView.coffee (Mohsen Azimi)

 * Convert view/ContentTypeView.coffee (Mohsen Azimi)

 * Convert view/BasicAuthButton.coffee (Mohsen Azimi)

 * Convert view/ApiKeyButton.coffee (Mohsen Azimi)

 * Convert helpers/handlebars.coffee (Mohsen Azimi)

 * Convert SwaggerUI.js and introduce JSHint (Mohsen Azimi)

 * Update swagger-js with latest (Jeremy Whitlock)

 * Fix issue with $.contains in Firefox (Mohsen Azimi)

 * Remove global references to swaggerUi object (Mohsen Azimi)

 * Update favicon with official logo and HiDPI support (Mohsen Azimi)

 * Bring back "Raw" link for 1.2 specs (Mohsen Azimi)

 * Add browser support information (Mohsen Azimi)

 * encodeURIComponent on api_key (LASSALLE Nicolas)

 * Render externalDocs when it's available (Mohsen Azimi)

 * Render response headers (Mohsen Azimi)

 * Add generated print CSS file in src/main/html/css/ (Mohsen Azimi)

 * Add bower.json (Mohsen Azimi)

 * increase TravisCI timeout to 20s (Mohsen Azimi)

 * increase TravisCI timeout to 10s (Mohsen Azimi)

 * Increase timeout for server launch in TravisCI (Mohsen Azimi)

 * Refactor tests (Mohsen Azimi)

 * update dependency and test in node 0.12 (Mohsen Azimi)

 * Add a link to releases for CHANGELOG (Mohsen Azimi)

 * Show master branch build badge (Mohsen Azimi)

 * add print style sheet (Nuno Vieira)

 * made space-delimited (Tony Tam)

 * Oauth 2.0: use space as delimiter for scopes. (Ivan Goncharov)

 * reverted files (Tony Tam)

 * updated js lib (Tony Tam)

 * user gulp-order to produce ordered template (Mohsen Azimi)

 * Add 'required' class to list parameter if it's required (Mohsen Azimi)

 * When running `gulp watch` watch handlebars file changes too (Mohsen Azimi)

 * Set marked options to render GFM correctly (Mohsen Azimi)

 * Add underscore source map file (Mohsen Azimi)

 * updated client for remote ref support (Tony Tam)

 * updated version (Tony Tam)

 * updated version, client (Tony Tam)

 * fix for #944, form data with 2.0 spec (Tony Tam)

 * prepare for file upload in swagger-js (Tony Tam)

 * added tags to test (Tony Tam)

 * updated client (Tony Tam)

 * Update README.md (Ron)

 * rebuild (Tony Tam)

 * updated from master (Tony Tam)

 * moved from render to init method (Tony Tam)

 * reduced timeout (Tony Tam)

 * Fix OAuth2 login when no scope is defined (Stefano Travelli)

 * Appended the checks of existing of translated attributes (Константин Калинин)

 * rebuilt client (Tony Tam)

 * Create CONTRIBUTING.md (Mohsen Azimi)

 * merg (Константин Калинин)

 * readme (Константин Калинин)

 * codestyle (Константин Калинин)

 * Adding (ignored) oauth2 state parameter. (Thijs Van der Schaeghe)

 * merge (Константин Калинин)

 * translated of title-attribute and extended a list ow known lexemes (Константин Калинин)

 * more phrases (Константин Калинин)

 * add simple translation support (Константин Калинин)

 * If possible, support audio content types (Vincent Pizzo)


2015-02-21, Version 2.1.8-M1
============================

 * updated client for remote ref support (Tony Tam)


2015-02-21, Version 2.1.7-M1
============================

 * updated version (Tony Tam)


2015-02-21, Version 2.1.6-M1
============================

 * rebuilt (Tony Tam)

 * updated client (Tony Tam)

 * updated version, client (Tony Tam)

 * fix for #944, form data with 2.0 spec (Tony Tam)

 * prepare for file upload in swagger-js (Tony Tam)

 * merged from develop_2.0 (Tony Tam)

 * updated version (Tony Tam)

 * added tags to test (Tony Tam)

 * Update README.md (Ron)


2015-02-18, Version 2.1.5-M1
============================

 * rebuild (Tony Tam)

 * merged from develop_2.0 (Tony Tam)

 * rebuilt (Tony Tam)

 * updated from master (Tony Tam)

 * removed bin folder (Tony Tam)

 * moved from render to init method (Tony Tam)

 * reduced timeout (Tony Tam)

 * Fix OAuth2 login when no scope is defined (Stefano Travelli)

 * Update README.md (Ron)

 * Update LICENSE (Ron)


2015-02-12, Version 2.1.4-M1
============================

 * test for #932 (Tony Tam)

 * updated version (Tony Tam)

 * rebuilt (Tony Tam)

 * updated client lib (Tony Tam)

 * fixed rendering to support latest client for #932 (Tony Tam)


2015-02-11, Version 2.1.3-M1
============================

 * updated versions (Tony Tam)

 * updated files (Tony Tam)

 * Include redirect_uri in access token request for OAuth2 authentication. (Stefano Travelli)


2015-02-09, Version 2.1.2-M1
============================

 * updated client, version (Tony Tam)

 * added fix for #916 by copying identifiers instead of using references (Tony Tam)

 * fixes to docker installation (Akshat Aranya)


2015-02-02, Version 2.1.1-M1
============================

 * rebuilt (Tony Tam)

 * updated js library to 2.1.1-M1 (Tony Tam)

 * added array check for #899 (Tony Tam)

 * added gulp-header to write metadata in comments of output library #900 (Tony Tam)

 * updated swagger-js to fix header (Tony Tam)

 * merged from develop_2.0 (Tony Tam)

 * Update README.md (Ron)

 * Update LICENSE (Ron)

 * added travis (Tony Tam)

 * updated package version, readme (Tony Tam)

 * fix for #640 (Tony Tam)

 * removed log (Tony Tam)

 * fixed body param (Tony Tam)

 * unified client (Tony Tam)

 * updated lib (Tony Tam)

 * formatting (Tony Tam)

 * updated mocha (Tony Tam)

 * moved tests, added 1.x, 2.0 (Tony Tam)

 * More test + fix decodeURIComponent call (Mohsen Azimi)

 * a build (Mohsen Azimi)

 * decode encoded URLs (Mohsen Azimi)

 * Sleep instead of wait (Mohsen Azimi)

 * Take out `checkConsoleErrors` from inside title check (Mohsen Azimi)

 * updated test to check console errors after loading (Tony Tam)

 * removed unnecessary logging (Tony Tam)

 * rebuilt distro (Tony Tam)

 * Fix backbone compability issue (Mohsen Azimi)

 * Don't fail with the first browser error. (Mohsen Azimi)

 * new build (Mohsen Azimi)

 * Add test for console errors (Mohsen Azimi)

 * regenerated files (Tony Tam)

 * Define default email subject in contact link (Matti Schneider)

 * fix for #517 (Tony Tam)

 * Upgrade to backbone@1.1. (Mohsen Azimi)

 * Upgrade to underscore.js@1.7 (Mohsen Azimi)

 * fix for #593 (Tony Tam)

 * PR #660 made to develop_2.0 (Mohsen Azimi)

 * New build with gulp (Mohsen Azimi)

 * added contact.name, email to template (Tony Tam)

 * fix for #819 (Tony Tam)

 * added files from #862 (Tony Tam)

 * fixes for #859, ie compat (Tony Tam)

 * updated for #849 (Tony Tam)

 * updated for #852 (Tony Tam)

 * fix for #855 (Tony Tam)

 * Add documentation and npm script for gulp (Mohsen Azimi)

 * Add `gulp serve` (Mohsen Azimi)

 * fixed return type (Tony Tam)

 * Add `gulp watch` (Mohsen Azimi)

 * updated lib to address content types (Tony Tam)

 * fix for #484, reset class for response container when switching media types (Tony Tam)

 * Do clean task before any other task (Mohsen Azimi)

 * Update Swagger Client (Mohsen Azimi)

 * Cleanup and add update docs (Mohsen Azimi)

 * Finish "gulp" command (Mohsen Azimi)

 * Add templates (Mohsen Azimi)

 * Add less and copy html files (Mohsen Azimi)

 * Add gulfile.js (Mohsen Azimi)

 * rebuilt file (Tony Tam)

 * merged into swagger-js (Tony Tam)

 * Update README.md (Tony Tam)

 * Oauth2 changes to support accessCode flow (Brian Shamblen)

 * Added hover over popup to display property validation attributes (Brian Shamblen)

 * updated swagger-client lib, index fonts (Tony Tam)

 * merged from develop, #824 (Tony Tam)

 * fixed build, dist (Tony Tam)

 * merged files (Tony Tam)

 * merged (Tony Tam)

 * add local google font cache (Gen Liu)

 * remove google web font (gengen1988)

 * manual merge #654 (Tony Tam)

 * Add ncp to npm dependencie (Mohsen Azimi)

 * Add build badge (Mohsen Azimi)

 * Add chai (Mohsen Azimi)

 * Add e2e tests (Mohsen Azimi)

 * Add travis yaml (Mohsen Azimi)

 * manually applied #458 (Tony Tam)

 * updated & rebuilt lib with unified client (Tony Tam)

 * Collapsing multiple elements when resource == '' (Damien Nozay)

 * Add Dockerfile and notes to README. (Daniel Nephin)

 * more platform-independent build (Aliaksandr Autayeu)


2015-02-01, Version 2.1.0-M1
============================

 * updated badge (Tony Tam)

 * merged from develop_2.0 (Tony Tam)

 * fix for submit methods (Tony Tam)

 * fix for https://github.com/swagger-api/swagger-js/issues/210, #814, query param encoding issue (Tony Tam)

 * re-enabled submit methods (Tony Tam)

 * Fixed terms of service link (Shaun Becker)

 * fixed headers, file upload per #662 (Tony Tam)

 * removed unnecessary ignores (Tony Tam)

 * Update README.md (webron)

 * removed handlebars reference (Tony Tam)

 * merged #779 (Tony Tam)

 * Added information about CORS support (webron)

 * fix for #770 (Tony Tam)

 * fix for #761 (Tony Tam)

 * updated swagger-js (Tony Tam)

 * merged from develop, added fix for #627 (Tony Tam)

 * Update SwaggerUi.coffee (Damien Nozay)

 * updated swagger-js per #715 (Tony Tam)

 * Update README.md (Tony Tam)

 * updated swagger.js (Tony Tam)

 * updated versions (Tony Tam)

 * updated swagger-js per https://github.com/swagger-api/swagger-js/issues/167 (Tony Tam)


2014-11-22, Version 2.1.0-alpha.6
=================================

 * added  for 2.0 specs to support multi-select inputs (Tony Tam)

 * fix for default values--standardized on  which is handled by ParameterView.coffee (Tony Tam)

 * Fix spelling errors and improve a sentence (Matt Hurne)

 * Fix spelling error in README.md (Matt Hurne)

 * Update README.md (Matt Hurne)

 * removed file (Tony Tam)

 * proposed fix for #731 (Tony Tam)

 * fix for https://github.com/swagger-api/swagger-ui/issues/729 (Tony Tam)


2014-11-17, Version 2.1.0-alpha.5
=================================

 * updated version (Tony Tam)

 * fix for #727 (Tony Tam)

 * updated link, removed buttons (Tony Tam)


2014-11-11, Version 2.1.0-alpha.4
=================================

 * updated versions (Tony Tam)

 * merged from auth_2.0 branch (Tony Tam)

 * updated js libs (Tony Tam)

 * rebuilt lib (Tony Tam)

 * smarter content type detection (Tony Tam)

 * updated from swagger-js (Tony Tam)

 * added back exports (Tony Tam)

 * Do not create operations for non-HTTP methods/verbs (Jeremy Whitlock)

 * Update README.md (Tony Tam)

 * updated for auth (Tony Tam)

 * minor formatting (Tony Tam)

 * removed validator for 1.2 specs (Tony Tam)

 * whitespace (Tony Tam)

 * updated readme (Tony Tam)

 * updated index (Tony Tam)

 * fix for https://github.com/wordnik/swagger-ui/issues/644, verify path object by type (Tony Tam)

 * updated swagger-js for https://github.com/wordnik/swagger-js/issues/151 (Tony Tam)

 * updated build (Tony Tam)

 * updated js client (Tony Tam)

 * fix for #663 (Tony Tam)

 * updated client per #669 (Tony Tam)

 * fix for #632, param names other than (Tony Tam)

 * added support for body params with name other than (Tony Tam)

 * rebuilt with deprecate support, https://github.com/wordnik/swagger-ui/pull/645 (Tony Tam)

 * merged from https://github.com/wordnik/swagger-ui/pull/642 (Tony Tam)

 * updated version for publish (Tony Tam)

 * updated swagger-js library (Tony Tam)

 * updated version check, multi support for 2.0 (Tony Tam)

 * Adding deprecated indicator to operations (Chris Allen)


2014-10-06, Version 2.1.0-alpha.1
=================================

 * updated version to alpha tag (Tony Tam)

 * fix for header undefined (Tony Tam)

 * added check for schema (Tony Tam)

 * Fix swagger-ui issue#637 [https://github.com/wordnik/swagger-ui/issues/637] to show response for 20x as default if response 200 is not present. (Edmond Chui)

 * fix for #626, added support for (default || defaultValue) (Tony Tam)

 * undid double stringify (Tony Tam)

 * added exception handling, updated client (Tony Tam)

 * formatting (Tony Tam)

 * updated library (Tony Tam)

 * Update README.md (Harold Combs)

 * fix for #605, create default tag group (Tony Tam)

 * added key listener (Tony Tam)

 * fix for #612 (Tony Tam)

 * fixed version (Tony Tam)

 * added validator for v2 (Tony Tam)

 * added logic for  link (Tony Tam)

 * updated lib (Tony Tam)

 * fix for #606, renamed resource  to  in the template (Tony Tam)

 * updated client, converted newlines to br in description (Tony Tam)

 * updated libraries (Tony Tam)

 * fix for #602 (Tony Tam)

 * fix for #596 (Tony Tam)

 * rebuilt distro (Tony Tam)

 * updated client to enable auth #588 (Tony Tam)

 * added null check (Tony Tam)

 * tidy up the commenting (Alex Agranov)

 * pull out onChange handler for #input_apiKey so it can be called manually (Alex Agranov)

 * fix for #592, added http method (Tony Tam)

 * Fixing tags check for undefined. (John Chiu)

 * Escape returned HTML. (David Cole)

 * Use initial url parameter if given, else fallback to petstore example (Markus Wolf)

 * Update package.json (Tony Tam)

 * updated link (Tony Tam)

 * updated versions (Tony Tam)

 * merged from develop (Tony Tam)

 * updated links (Tony Tam)

 * merged from master (Tony Tam)

 * fix for license structure change (Tony Tam)

 * added compatibility table (Tony Tam)

 * added links to readme (Tony Tam)

 * updated readme, resource to remove url if not present (Tony Tam)

 * added response check for swagger 2.0/1.2 client (Tony Tam)

 * fix for #570, sanitized tag names (Tony Tam)

 * removed stubs (Tony Tam)

 * updated swagger lib (Tony Tam)

 * updated swagger-js (Tony Tam)

 * updates to develop branch (Tony Tam)

 * added 2.0 client (Tony Tam)

 * updated files (Tony Tam)


2015-07-14, Version 20.0.2
==========================



2015-07-14, Version 20.0.1
==========================

 * Fix build on Node v0.12 and io.js (Miroslav Bajtoš)

 * Move padding from index.html to CSS (Miroslav Bajtoš)

 * Embed throbber.gif in CSS via data-uri (Miroslav Bajtoš)

 * Upgrade less to 2.5 (latest) (Miroslav Bajtoš)


2015-07-08, Version 20.0.0
==========================

 * Fix "Expand Operations" link (Miroslav Bajtoš)

 * Use strong-swagger-client instead of swagger-client (Miroslav Bajtoš)

 * Add back maxwidth in standalone mode (Miroslav Bajtoš)

 * Refactored to visualize more parameter and property restrictions (Shelby Sanders)

 * Corrected to replace '/' and '.' in anchors, since they break the shebang logic (Shelby Sanders)

 * Changed to never show Response Class, because it must be in Swagger Spec for Code Gen to work (Shelby Sanders)

 * Added better higlight of required params, with indication other than color/weight, and support for select and read-only (Shelby Sanders)

 * Corrected shebang() to avoid closing Model when called an even number of times (Shelby Sanders)

 * Added option for headersToHide in order to optionally hide arbitrary headers (e.g. Authorization) (Shelby Sanders)

 * Corrected to actually show request headers and body, rearranged sections based on probability of reference (Shelby Sanders)

 * Add default padding for when not embedded (Shelby Sanders)

 * Added better highlighting and padding for preformatted JSON and XML (Shelby Sanders)

 * Corrected styling of title and description, and refactored to remove with restrictions (Shelby Sanders)

 * Added support for HTML in title, renamed footer to info_server avoiding conflict with Bootstrap, and corrected replacement of array[ (Shelby Sanders)

 * make wider and show request parts (Nicolas Duchastel de Montrouge)

 * Changed to protect against missing  when checking for file uploads (Shelby Sanders)

 * Added support for basePaths to document across multiple environments (Shelby Sanders)

 * Refactored column widths to better use space for likely growing content (Shelby Sanders)

 * Added support for anchoring to first reference of a Model (Shelby Sanders)

 * Defer creation of signature and sampleJSON, so all Models will be loaded (Shelby Sanders)

 * Omit divs for info.title and info.description if they're absent (Shelby Sanders)

 * Removed inappropriate commas from sorters (Shelby Sanders)

 * Corrected to avoid showing invalid Model and Model Schema is missing or void (Shelby Sanders)

 * Added guard against null mockSignature (Shelby Sanders)

 * Removed 'Implementation Notes' label since it's just noise, widened Resource expansion anchor to full label (Shelby Sanders)

 * Corrected to work when loading resources from file:// (Shelby Sanders)

 * Added support for primitives in StatusCodeView (Shelby Sanders)

 * Add support for toggling Model and Schema, instead of just expanding (Shelby Sanders)

 * Ensure Response Content Type is shown regardless of Response Class (Shelby Sanders)

 * Collapse and label responseModel description by default (Shelby Sanders)

 * Added support for multiple responseMessages from Swagger 1.2 (Shelby Sanders)

 * Changed to hide the message-bar when no message (Miroslav Bajtoš)

 * Add minimal test (Miroslav Bajtoš)

 * Remove src/main/css from version control. (Miroslav Bajtoš)

 * Remove lib/swagger.js from version control (Miroslav Bajtoš)

 * Rename the module to strong-swagger-ui (Miroslav Bajtoš)

 * Fix project infrastructure (Miroslav Bajtoš)

 * Use handlebars from npm. (dblock)


2014-09-12, Version 2.0.24
==========================

 * updated client, version (Tony Tam)

 * Remove inline event handlers from resource template. (Samuel Reed)

 * rebuilt (Tony Tam)

 * Fix potential self XSS in request url. (Samuel Reed)

 * Moved reference to throbber.gif to CSS file. (Bez Hermoso)

 * Fixed oauth redirect url path. URL works with nested pathnames. (Antek Drzewiecki)


2014-08-06, Version 2.0.22
==========================

 * fixes for https://github.com/wordnik/swagger-js/issues/107 (Tony Tam)


2014-08-02, Version 2.0.21
==========================

 * updated swagger-js (Tony Tam)


2014-08-02, Version 2.0.20
==========================

 * updated swagger-js, added #507 to dist (Tony Tam)

 * Provide option highlightSizeThreshold to allow conditional syntax highlighting based on response size (John Bryson)


2014-08-01, Version 2.0.19
==========================

 * updated versions (Tony Tam)

 * rebuilt (Tony Tam)

 * updated templates to support file as body or form params (Tony Tam)

 * updated logger to avoid logging arrays (Tony Tam)

 * updated key name (Tony Tam)

 * updated swagger-js to 2.0.34 (Tony Tam)

 * provide option sorter=[alpha|method] (Chris Hatch)

 * fixed undefined variable errors (aurelian)

 * remove console.info (Chris Hatch)

 * #254 alphabetical sort of apis and operations under apis new option sortAlphabetical=true|false (Chris Hatch)

 * updated distro to include #493 (Tony Tam)

 * Fix for issue #492; HTML in response headers (Martijn van der Lee)


2014-07-12, Version 2.0.18
==========================

 * switched to snippet view #491 (Tony Tam)

 * updated versions, swagger-js (Tony Tam)

 * fixed #340 with empty body, updated swagger-js (Tony Tam)

 * Fix handling for jQuery response headers (Travis Illig)

 * Update underscore-min.js (paladox2015)

 * updated to support explicit keys (Tony Tam)

 * The list of scopes now RFC6749 Sec.3.3 compliant (Jörg Adler)


2014-05-14, Version 2.0.17
==========================



2014-05-14, Version 2.0.17
==========================

 * bumped version (Tony Tam)

 * fix for upload with no files (Tony Tam)

 * updated with version number (Tony Tam)

 * Updated index.html to include css links for print media (Samuel Raghunath)


2014-04-29, Version 2.0.16
==========================

 * updated version for release (Tony Tam)

 * rebuilt distro for #331 (Tony Tam)

 * ~ "Error Status Codes" -> "Response Messages" + "Response Model" column in OperationView template + Response Model in Response Messages (FilippQoma)


2014-04-27, Version 2.0.15
==========================

 * updated swagger.js version (Tony Tam)

 * added emitting version into swagger-ui.js file (Tony Tam)

 * moved css scoping to less templates per owners request, converted highlight.default.css to less, and made reset css its only include as it is difficult to scope due to html and body tag css overrides (Kyle J. Ginavan)

 * added scope to swagger, therefore, it can be included/embedded within other applications and not have css bleed. (Kyle J. Ginavan)

 * Update README.md (Pat)

 * Update README.md (webron)

 * updated oauth2 support into a single config (Tony Tam)

 * merged from oauth2 branch (Tony Tam)

 * Add index.js file that returns the dist location and version when required (Paul Winkler)


2014-03-19, Version 2.0.14
==========================

 * updated swagger-js, version (Tony Tam)

 * Create LICENSE (webron)

 * rebuilt per #417 (Tony Tam)

 * rebuilt per #418 (Tony Tam)

 * added options styling, swagger-js update, per #420 (Tony Tam)

 * Fix header response on file upload (Johan.Bloemberg)

 * Don't send empty form fields as undefined for file uploads (Johan.Bloemberg)

 * Modified commit for the https://github.com/wordnik/swagger-ui/pull/414 Brings backward compatibility for the 'allowMultiple' attribute. (valdemon)

 * rebuilt (Tony Tam)

 * updated client (Tony Tam)

 * fix for toggle operation (Tony Tam)

 * Fix content and response url on file uploads (Johan.Bloemberg)

 * fixed id construction in toggleOperationContent (Joyce Stack)

 * fix for #410, varibles declared in closures cause ie8 pains (Tony Tam)

 * updated files (Tony Tam)

 * ie8 fixes (Tony Tam)

 * fix for https://github.com/wordnik/swagger-js/issues/81 (Tony Tam)


2014-02-19, Version 2.0.12
==========================

 * updated swagger-js (Tony Tam)


2014-02-19, Version 2.0.11
==========================

 * updated swagger-js version (Tony Tam)

 * fix for https://github.com/wordnik/swagger-ui/pull/399 (Tony Tam)

 * fix for array params (Tony Tam)


2014-02-16, Version 2.0.10
==========================

 * fixes for allowable values (Tony Tam)


2014-02-16, Version 2.0.9
=========================

 * updated swagger-js per https://github.com/wordnik/swagger-ui/pull/394#issuecomment-35181116 https://github.com/wordnik/swagger-ui/pull/394#issuecomment-35181116 (Tony Tam)


2014-02-13, Version 2.0.8
=========================

 * updated swagger-js versions (Tony Tam)


2014-02-12, Version 2.0.7
=========================

 * updated version (Tony Tam)

 * updated client to 2.0.13 (Tony Tam)

 * updated swagger-client (Tony Tam)

 * merge of #369, https://github.com/wordnik/swagger-js/issues/74 (Tony Tam)

 * fix for #387 (Tony Tam)

 * fix for #388 (Tony Tam)

 * fix for #381 (Tony Tam)


2014-01-23, Version 2.0.4
=========================

 * updated version (Tony Tam)

 * updated swagger-js client, support for IE8 (Tony Tam)

 * updated swagger-js version to address #377, #72 (Tony Tam)

 * Added support for pretty-printing responses for media types with extended subtypes. For example the media type 'application/vnd.myresource+json; version=1.2' will be correctly recognized as JSON and pretty-printed. Conforms to RFC 6838, 6839. (Michael Iles)

 * fix for #248 (Tony Tam)

 * Update README.md (Tony Tam)

 * safe-JSON-parsing-check-for-type-undefined (invincible)

 * rebuilt client (Tony Tam)

 * restrict uploaded file inputs to those in the form being submitted (Bryan Matsuo)

 * removed selfclosing tag #332 - reapplied e4d01c5 by thadudexx (Aliaksandr Autayeu)


2013-11-29, Version 2.0.3
=========================

 * updated version (Tony Tam)

 * fix for #288 (Tony Tam)

 * fix for #334 (Tony Tam)

 * Update index.html (thadudexx)

 * fixed package to include less (Tony Tam)

 * Match all image types (Takeharu Oshida)

 * Add image contents type resopnse handler (Takeharu Oshida)

 * fixing request_url updates for all operations in one resource (Pavel Puchkarev)

 * merged https://github.com/wordnik/swagger-ui/pull/323 (Tony Tam)


2013-09-26, Version 2.0.2
=========================

 * updated swagger-js (Tony Tam)

 * updating docs, change in sample and java required for build (Pavel Puchkarev)

 * merged pr for https://github.com/wordnik/swagger-js/pull/54 (Tony Tam)

 * fix for #310 (Tony Tam)

 * fixed example (Tony Tam)

 * fix for required fields showing optional (Tony Tam)

 * added resource description (Tony Tam)

 * fixed merge issue (Tony Tam)

 * fix for #301, headers being URI encoded (Tony Tam)

 * manual merge of #304 (Tony Tam)

 * manual merge of #303 (Tony Tam)

 * fix for #297, enabled throbber (Tony Tam)

 * Added proper formatting for hal+json Content-Type responses in coffeescript source file (tomrac87)

 * added enum support per #296 (Tony Tam)

 * Added proper formatting for hal+json Content-Type responses (tomrac87)


2013-08-29, Version 2.0.1
=========================

 * updated swagger-js to address sample schema issues (Tony Tam)

 * fix for #279, empty boolean drop-downs (Tony Tam)


2013-08-19, Version 2.0.0
=========================

 * fixed responseMessages issue per #267 (Tony Tam)

 * fixing file name -- realized the typo in the link tag was replicating one in the css file name (Robert Crooks)

 * fix for issue #285 (Brightcove Learning Services)

 * fixed enum, required flags (Tony Tam)

 * updated readme (Tony Tam)

 * updated swagger-client (Tony Tam)

 * fixed petstore link (Tony Tam)

 * merged from develop branch (Tony Tam)

 * updated handlebars (Tony Tam)

 * fixes for content-type (Tony Tam)

 * updated for 1.2 support (Tony Tam)

 * removed console log, updated swagger.js (Tony Tam)

 * fixed discrepancy between handlebars precompiler and runtime (Ryan Bales)

 * merged https://github.com/wordnik/swagger-js/issues/42 (Tony Tam)

 * fix for #263 (Tony Tam)

 * fix for #261 (Tony Tam)

 * removed console logs (Tony Tam)

 * removed some logging (Tony Tam)

 * fixed default URL (Tony Tam)

 * added api info (Tony Tam)

 * updated to use petstore instead of localhost (Tony Tam)

 * added authorization support from swagger-js 2.0 (Tony Tam)

 * renamed discoveryUrl to url to match js change (Tony Tam)

 * fix for #174, #78 (Tony Tam)

 * updated to latest swagger-js (Tony Tam)

 * #199 (Tony Tam)

 * removed swagger-client dep for now, it's copied manually (Tony Tam)

 * updated deps, version (Tony Tam)

 * merged with swagger.js-2.0-develop (Tony Tam)

 * added shred library (Tony Tam)

 * added separate request and response templates (Tony Tam)

 * Update index.html (Tony Tam)

 * added support for swagger-spec 1.2 (Tony Tam)


2013-06-26, Version 1.1.15
==========================

 * updated to support 1.2 spec responseMessages (Tony Tam)

 * form data fix (Tony Tam)

 * fixed file param name (Tony Tam)

 * Create proper example JSON (George Sibble)

 * Add new column to display the paramType (Ian Forsey)

 * minor typo correction (Marsh Gardiner)

 * merged #pr175 (Tony Tam)

 * fix for #180 (Tony Tam)

 * fix for sending content-type header during GET requests (Tony Tam)

 * Making fonts call protocol agnostic so it works behind https (Paul Hill)

 * updated to support file + form params in same request (Tony Tam)

 * removed reference to downloads folder (Tony Tam)

 * don't set contentType for empty body (Johannes Dewender)


2013-03-08, Version 1.0.13
==========================

 * fixed required param bug per #163 (Tony Tam)


2013-03-05, Version 1.0.12
==========================

 * bumped version (Tony Tam)

 * More CSS cleaning. (Pepijn de Geus)

 * Fix custom bold text. (Pepijn de Geus)

 * UI improvements for parameters (input and read-only) and model description. Uses changes to swagger.js (swagger-client). (Pepijn de Geus)

 * Update to Handlebars 1.0.10+, CoffeeScript 1.5 (Pepijn de Geus)

 * toggleOperationContent to escape resource name before querying for node (Andreas Andreou)

 * updated swagger.js dependency for #136 (Tony Tam)

 * added ignore file (Tony Tam)

 * added swagger-client as dependency, renamed from  to  to avoid collisions (Tony Tam)

 * add correct link to distribution downloads (Eric Himmelreich)

 * reverted default url (Tony Tam)

 * fix for #121 (Tony Tam)

 * improved error handling (Tony Tam)

 * update for swagger.js#14, #138, #139 (Tony Tam)

 * updated README for download instructions (Tony Tam)

 * added dist folder back now that git downloads are gone (Tony Tam)

 * fixed file bug for indent issue and param type check (Tony Tam)

 * update README (Filirom1)

 * simplify build (Filirom1)


2012-12-04, Version 1.1.7
=========================

 * closes #107 (Ayush Gupta)

 * using the latest swagger.js (Ayush Gupta)

 * closes #98 (Ayush Gupta)

 * Fixing merge problem (Alberto Pose)


2012-11-27, Version 1.1.6
=========================

 * fixed duplicate model, updated example name (Tony Tam)

 * re-merged #96 (Tony Tam)

 * Adding fallback to XML when JSON response parsing fails. (Alberto Pose)

 * Update src/main/javascript/doc.js (sequielo)

 * added missing files per #100 (Tony Tam)


2012-11-25, Version 1.1.5
=========================

 * manual merge of #97 (Tony Tam)

 * Adding operation number to create a unique href for the <a/> tag. (Alberto Pose)

 * Adding JSON sample UI to response (Alberto Pose)

 * Small improvements to some titles (Alberto Pose)

 * coffeescript compiled swagger.js for #91 (Ayush Gupta)

 * fixes https://github.com/wordnik/swagger-core/issues/68 in a generic way. Should work with all params. (predicador37)

 * fix from https://github.com/wordnik/swagger-core/issues/68 (Ayush Gupta)


2012-11-19, Version 1.1.4
=========================

 * redo of PR #88, #89, #90 (Tony Tam)

 * updated to 1.1.4 (Tony Tam)

 * closes #83 (Ayush Gupta)

 * closes #84 (Ayush Gupta)

 * Adding syntax highlight to JSON snippets and responses. (Alberto Pose)

 * Removing console.log (Alberto Pose)

 * Adding 'snippet' tab to parameter datatype signature UI This new section displays how a complex datatype should look like providing some sample code for the developer using Swagger. (Alberto Pose)


2012-11-14, Version 1.1.3
=========================

 * merged pull request #42 from @tim-vandecasteele (Ayush Gupta)

 * Support console.log in IE9 (Greg MacLellan)

 * Adding response status code views (swagger.js updated). (Alberto Pose)

 * Added doctype declaration (Greg MacLellan)

 * closes #35 (Ayush Gupta)

 * closes #63 (Ayush Gupta)

 * removed unnecessary node_modules, updated some of the logic from pull request for docExpansion, onComplete and onFailure param support (Ayush Gupta)

 * upgraded handlebars and removed the need for local copy of handlebars. (Ayush Gupta)

 * code to display resources in default/list/expended style. style can be passed as a option while creating swagerUi object. In future I would like to achieve this by passing options to templates and have a template helper method manipulate dom and apply correct classes.This approach will improve the performance as we need not go over the entire dom and call Doc.collapseOperationsForResource or Doc.expandOperationsForResource on each matched element (Arjun Balla)

 * code to pass doneSuccess and doneFailure callback functions. doneSuccess is invoked after successful rendering of swagger-ui and doneFailure is invoke if there is failure in rendering swagger-ui (Arjun Balla)

 * added .project to gitignore (unknown)

 * Problem sending parameters via POST (Артём Курапов)

 * swagger-ui handlebar templates are compatible only with 1.0.5beta version (arjunballa)


2012-10-09, Version 1.1.1
=========================

 * closed #68 (Ayush Gupta)

 * Update README.md (Ayush Gupta)

 * setting content type to JSON for http PATCH (Arul)

 * added styling for patch (Ayush Gupta)

 * reformatted css for readability (Ayush Gupta)

 * Custom Header Parameters - (For Basic auth etc). Closes #53. Thanks @rintcius! (Ayush Gupta)

 * refer to jquery-1.8.0.min.js because jquery-1.8.0.js is not available in lib (also added .idea to .gitignore) (Rintcius Blok)

 * closes #45 (Ayush Gupta)

 * closes #46 (Ayush Gupta)

 * Updated readme with a section on SwaggerUi and its instantiation (Ayush Gupta)

 * closes #38 closes #37 (Ayush Gupta)

 * load logo_small.png and throbber.gif from images/ directory (Thomas Taschauer)

 * Fixing typo (Stephen McKamey)

 * removed dist folder (Tony Tam)

 * updated to point to downloads (Tony Tam)

 * allow html in summary, notes and description (Rintcius Blok)

 * fix build (Rintcius Blok)

 * re-enabled notes (Tony Tam)

 * updated readme with info on supportHeaderParams (Ayush Gupta)

 * updated readme, escaping underscore (Ayush Gupta)

 * Support for changing api_key parameter name. Closes #36 (Ayush Gupta)

 * added sublime project file (Ayush Gupta)

 * Calling Backbone.history.start later (Ayush Gupta)

 * updated readme (Ayush Gupta)

 * support for non GET methods. Closes #15 (Ayush Gupta)

 * closes #34 (Ayush Gupta)

 * proper encoding of query params (Ayush Gupta)

 * Calling load after instantiation of SwaggerUI (Ayush Gupta)

 * closes #32 (Ayush Gupta)

 * added pre-built distro (Tony Tam)

 * updated key (Tony Tam)

 * updated to v2 (Tony Tam)


2012-06-21, Version 1.0.1
=========================

 * added ignore of baseUrl if not defined or valid (Tony Tam)

 * Corrected bug where top level (only one initial '/') api paths get no name. (Aaron McCall)

 * Rebuilt using 'middleman build' (Hiram Chirino)

 * Simplify how the name of the resource is constructed so that it can handle resources nested multiple directory levels in. (Hiram Chirino)

 * Support a relative url path to the service. (Hiram Chirino)

 * Make swagger-ui compatible with non-GET requests (Albert Casademont)

 * Added development instructions to the README (zeke)

 * using basePath from the apis (Ayush Gupta)

 * using different basePath variants to get API Listing (Ayush Gupta)

 * Infer root resource name from baseUrl. (zeke)

 * Updated sample discovery URL. (zeke)

 * new build (zeke)

 * Support resource and discovery URLs with or without .json extension (zeke)

 * small templating change (zeke)

 * Removed empty CSS rule (zeke)

 * Fixed conflicts (bpinkney)

 * updated urls (Tony Tam)

 * Updated UI to handle object structure for list allowed values (rpidikiti)


2011-10-11, Version 1.0
=======================

 * First release!
