var gulp         = require('gulp');
var notify       = require('gulp-notify');
var sass         = require('gulp-sass');
var sourcemaps   = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var browserSync  = require('browser-sync');
var handleErrors = require('../util/handleErrors');
var config       = require('../config').sass;

var settings = {
  outputStyle: 'compressed'
};

gulp.task('styles:production', function() {
  return gulp.src(config.src)
    .pipe(sass(config.settings))
    .on('error', handleErrors)
    .pipe(autoprefixer({ browsers: ['last 2 version'] }))
    .pipe(gulp.dest(config.dest))
    .pipe(notify('Sass complete'));
});
