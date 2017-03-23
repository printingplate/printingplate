var gulp        = require('gulp');
var cache       = require('gulp-cached');
var notify      = require('gulp-notify');
var config      = require('../config').fonts;

gulp.task('fonts', function() {
  gulp.src(config.src)
    .pipe(cache())
    .pipe(gulp.dest(config.dest))
    .pipe(notify('Fonts complete'));
});