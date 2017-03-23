var gulp   = require('gulp');
var watch  = require('gulp-watch');
var config = require('../config');

gulp.task('watch', ['browserSync', 'webpack:watch'], function() {
  watch(config.html.src, function() {
    gulp.start('templates');
  });

  watch(config.images.src, function() {
    gulp.start('images');
  });

  watch(config.svg.src, function() {
    gulp.start('svg');
  });

	watch(config.sass.watchSrc, function() {
    gulp.start('styles');
  });

  watch(config.html.src, function() {
    gulp.start('templates');
  });
});
