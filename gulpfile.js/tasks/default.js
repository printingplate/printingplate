var gulp        = require('gulp');
var runSequence = require('run-sequence');

gulp.task('default', function(callback) {
  runSequence('clean', [
    'styles:production',
    'webpack:production',
    'templates',
    'svg',
    'images',
    'fonts'
  ], 'watch', callback);
});
