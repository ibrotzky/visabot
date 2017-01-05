var os = require('os')
  , join = require('path').join
  , util = require('util');


exports.makeTmpname = function(prefixSuffix) {
  var prefix, suffix;
  if ('string' == typeof prefixSuffix) {
    prefix = prefixSuffix;
    suffix = '';
  } else if (util.isArray(prefixSuffix)) {
    prefix = prefixSuffix[0];
    suffix = prefixSuffix[1];
  } else {
    throw new Error('unexpected prefixSuffix: ' + prefixSuffix);
  }

  // creates a "%Y%m%d" date string.
  var t = new Date().toISOString().split('T')[0].split('-').join('');

  return util.format('%s%s-%d-%s%s', prefix, t, process.pid,
      Math.round(Math.random() * 0x1000000000).toString(32), suffix);
};

exports.create = function(prefixSuffix, tmpdir, creator, callback) {
  // Node v0.8 doesn't support os.tmpdir
  tmpdir = tmpdir || (os.tmpdir || os.tmpDir)();

  function create() {
    var path;
    try {
      path = join(tmpdir, exports.makeTmpname(prefixSuffix));
    } catch (e) {
      creator(e);
      return;
    }

    creator(null, path, function(err) {
      if (err && 'EEXIST' == err.code) {
        // retry
        create();
        return;
      }

      callback(err, path);
    });
  }

  create();
};
