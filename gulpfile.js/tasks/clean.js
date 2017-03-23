var gulp   = require('gulp');
var del    = require('del');
var config = require('../config/index').baseDir;

gulp.task('clean', function() {
  return del(config.build + '/**/*.*');
});
