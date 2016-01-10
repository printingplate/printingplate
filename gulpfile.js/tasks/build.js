var
  gulp                = require('gulp'),
  plugins             = require('gulp-load-plugins')();

gulp.task('build', function(cb) {
  plugins.sequence(
    'clean',
    [
      'images',
      'scripts:standalone'
    ],
    [
      'styles',
      'scripts'
    ],
    cb
  );
});
