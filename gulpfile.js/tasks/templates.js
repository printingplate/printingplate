var gulp        = require('gulp');
var cache       = require('gulp-cached');
var fileinclude = require('gulp-file-include');
var notify      = require('gulp-notify');
var config      = require('../config').html;

gulp.task('templates', function() {
  gulp.src(config.src)
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file'
    }))
  .pipe(cache('templates'))
  .pipe(gulp.dest(config.dest))
  .pipe(notify('Templates Complete'));
});