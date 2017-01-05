'use strict';

var getShrinkwrapDependencies = function (shrinkwrap, cb) {

  var results = {};

  var _parseModule = function (module, path, name) {

    var moduleName = (name || module.name) + '@' + module.version;
    if (results[moduleName]) {
      results[moduleName].paths.push(path.concat([moduleName]));
    }
    else {
      results[moduleName] = {
        name: name || module.name,
        version: module.version,
        paths: [path.concat([moduleName])]
      };
    }

    var children = Object.keys(module.dependencies || {});
    for (var i = 0, il = children.length; i < il; ++i) {
      var child = children[i];
      _parseModule(module.dependencies[child], path.concat([moduleName]), child);
    }
  };

  _parseModule(shrinkwrap, []);

  return cb(null, results);
};

module.exports = {
  getShrinkwrapDependencies: getShrinkwrapDependencies
};
