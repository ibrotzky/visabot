/*
 * Web driver manager
*/
'use strict';

// running on CI, but DISPLAY is NOT provided
if (process.env.CI && !process.env.DISPLAY) {
  // skip browser tests
  console.error('Running on CI but DISPLAY is NOT provided.');
  console.error('Skipping all browser tests.');
  process.exit(0);
}

if (process.env.CI && process.env.label === 'ubuntu-0.10') {
  console.error('Skipping all browser tests on ubuntu-0.10');
  process.exit(0);
}

var webdriver = require('selenium-webdriver');

var driver = new webdriver.Builder().withCapabilities(webdriver.Capabilities.firefox()).build();

module.exports = driver;

before(function reportBrowserNameAndVersion() {
  if (!process.env.DEBUG) {
    return;
  }
  return driver.getCapabilities().then(function (caps) {
    console.log('BROWSER NAME %s VERSION %s',
                caps.get('browserName'), caps.get('version'));
  });
});

afterEach(function verifyConsoleErrors() {
  return driver.manage().logs().get('browser').then(function(browserLogs) {
    var errors = [];
    browserLogs.forEach(function(log){
      // 900 and above is "error" level. Console should not have any errors
      if (log.level.value > 900) {
        console.log('BROWSER ERROR:', log.message);
        errors.push(log);
      } else if (process.env.DEBUG) {
        console.log('browser log:  ', log.message);
      }
    });
    if (errors.length) {
      throw new Error('Unexpected error, see the browser logs above.');
    }
  });
});
