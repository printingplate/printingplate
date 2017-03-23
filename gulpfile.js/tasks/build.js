var gulp        = require('gulp');
var runSequence = require('run-sequence');

gulp.task('build', function(callback) {
  runSequence('clean', [
    'webpack:production',
    'styles:production',
    'svg',
    'fonts'
  ], callback);
});
