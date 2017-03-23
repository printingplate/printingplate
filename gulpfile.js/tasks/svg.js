var gulp     = require('gulp');
var svgmin   = require('gulp-svgmin');
var svgstore = require('gulp-svgstore');
var notify   = require('gulp-notify');
var path     = require('path');
var config   = require('../config').svg;

gulp.task('svg', function() {
  return gulp.src(config.src)
    .pipe(svgmin(function (file) {
      var prefix = path.basename(file.relative, path.extname(file.relative));
      return {
        plugins: [{
          cleanupIDs: {
            prefix: prefix + '-',
            minify: true
            }
          }]
        }
      }))
    .pipe(svgstore({ inlineSvg: true }))
    .pipe(gulp.dest(config.dest))
    .pipe(notify('SVG complete'));
});
