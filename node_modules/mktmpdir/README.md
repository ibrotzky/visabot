mktmpdir
==================
[![Build Status](https://travis-ci.org/nkzawa/mktmpdir.png?branch=master)](https://travis-ci.org/nkzawa/mktmpdir)

mktmpdir creates a temporary directory, ported from Ruby's [Dir.mktmpdir](http://www.ruby-doc.org/stdlib-2.0/libdoc/tmpdir/rdoc/Dir.html#method-c-mktmpdir).

```js
var mktmpdir = require('mktmpdir');

mktmpdir(function(err, dir, done) {
  if (err) throw err;
  // use the directory...
  fs.writeFile(dir + '/foo', 'hello, World', done);
}, function(err, dir) {
  // after the directory is removed.
});
```

## Installation
    $ npm install mktmpdir

## Usage

### mktmpdir([prefixSuffix], [tmpdir], callback, [onend])
The prefix and suffix of the name of the directory is specified by `prefixSuffix`.
The directory is created under `tmpdir` or `os.tmpdir()` with 0700 permission.

## Examples

```js
mktmpdir(function(err, dir) {
  // dir is ".../d..."
});

mktmpdir('foo', function(err, dir) {
  // dir is ".../foo..."
});

mktmpdir(['foo', 'bar'], function(err, dir) {
  // dir is ".../foo...bar"
});

mktmpdir(null, '/var/tmp', function(err, dir) {
  // dir is "/var/tmp/d..."
});

// If a callback is invoked, the path of the directory and its contents are removed.
mktmpdir(function(err, dir, done) {
  if (err) throw err;
  fs.open(dir + '/foo', 'w', function(err, fd) {
    done(err);
  });
}, function(err) {
  // the directory has been removed.
});

// If a callback is not invoked, `mktmpdir` doesn't remove the directory.
mktmpdir(function(err, dir) {
  if (err) throw err;
  fs.open(dir + '/foo', 'w', function(err, fd) {
    // remove the directory.
    exec('rm -rf ' + dir);
  });
});
```

## License
MIT
