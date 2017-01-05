var fs = require('fs')
var rimraf = require('rimraf')
var tmpname = require('./tmpname');


module.exports = mktmpdir;

/**
 * Creates a temporary directory.
 *
 * The directory is created with 0700 permission.
 * Application should not change the permission to make the temporary directory accessible from other users.
 *
 * The prefix and suffix of the name of the directory is specified by
 * the optional first argument, <i>prefixSuffix</i>.
 * - If it is not specified or null, "d" is used as the prefix and no suffix is used.
 * - If it is a string, it is used as the prefix and no suffix is used.
 * - If it is an array, first element is used as the prefix and second element is used as a suffix.
 *
 *   mktmpdir(function(err, dir) { dir is ".../d..." });
 *   mktmpdir('foo', function(err, dir) { dir is ".../foo..." });
 *   mktmpdir(['foo', 'bar'], function(err, dir) { dir is ".../foo...bar" });
 *
 * The directory is created under os.tmpdir() or
 * the optional second argument <i>tmpdir</i> if non-null value is given.
 *
 *   mktmpdir(function(err, dir) { dir is os.tmpdir() + "/d..." });
 *   mktmpdir(null, '/var/tmp', function(err, dir) { dir is "/var/tmp/d..." });
 *
 * If a callback is invoked,
 * the path of the directory and its contents are removed.
 *
 *   mktmpdir(function(err, dir, done) {
 *     if (err) throw err;
 *     // use the directory...
 *     fs.open(dir + '/foo', 'w', function(err, fd) {
 *       done(err);
 *     });
 *   }, function(err) {
 *     // the directory has been removed.
 *   });
 *
 * If a callback is not invoked, mktmpdir doesn't remove the directory.
 *
 *   mktmpdir(function(err, dir) {
 *     if (err) throw err;
 *     // use the directory...
 *     fs.open(dir + '/foo', 'w', function(err, fd) {
 *       // remove the directory.
 *       exec('rm -rf ' + dir);
 *     });
 *   });
 *
 * @param {String|Array} prefix, or an array including prefix and suffix.
 * @param {String} base tmpdir
 * @param {Function} callback
 * @param {Function} onend
 * @api public
 */

function mktmpdir(prefixSuffix, tmpdir, callback, onend) {
  if ('function' == typeof prefixSuffix) {
    onend = tmpdir;
    callback = prefixSuffix;
    tmpdir = null;
    prefixSuffix = null;
  } else if ('function' == typeof tmpdir) {
    onend = callback;
    callback = tmpdir;
    tmpdir = null;
  }

  prefixSuffix = prefixSuffix || 'd';
  onend = onend || function() {};

  tmpname.create(prefixSuffix, tmpdir, function(err, path, next) {
    if (err) return callback(err);

    fs.mkdir(path, 0700, next);
  }, function(err, path) {
    if (err) return callback(err);

    callback(null, path, function(err) {
      if (!path) return onend(err);

      rimraf(path, function(_err) {
        onend(err || _err, path);
      });
    });
  });
}

