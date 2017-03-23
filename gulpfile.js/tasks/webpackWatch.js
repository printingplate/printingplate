var gulp        = require('gulp');
var webpack     = require('webpack');
var gutil       = require('gulp-util');
var notify      = require('gulp-notify');
var browserSync = require('browser-sync');
var config      = require('../config/webpack/dev.config');

var webpackInit = false;
var statsLog = {
  colors: true,
  reasons: true
};

gulp.task('webpack:watch', function(callback) {
  function onComplete(error, stats) {
    if (error) { // fatal error
      onError(error);
    } else if (stats.hasErrors()) { // soft error
      onError(stats.toString(statsLog));
    } else {
      onSuccess(stats.toString(statsLog));
    }
  }

  function onError(error) {
    var formatedError = new gutil.PluginError('webpack', error);

    notify({
      title: 'Error: ' + formatedError.plugin,
      message: formatedError.message
    });

    callback(formatedError);
  }

  function onSuccess(detailInfo) {
    notify({
      title: 'Webpack',
      message: 'Webpack complete'
    });

    gutil.log('[webpack]', detailInfo);
    browserSync.reload();

    if (!webpackInit) {
      webpackInit = true;
      callback();
    }
  }

  webpack(config).watch(200, onComplete);
});
