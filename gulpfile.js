// Distribution paths
var
  html_path           = 'app/html/',
  src_path            = html_path + 'src/',
  build_path          = html_path+'www/',
  theme_name          = 'theme-name',
  theme_path          = 'app/www/content/themes/' + theme_name +  '/';

// Requirements, gulp plugins loaded with gulp-load-plugins
var
  gulp                = require('gulp'),
  del                 = require('del'),
  gulpLoadPlugins     = require('gulp-load-plugins'),
  plugins             = gulpLoadPlugins({
    rename: {
        'gulp-if' : 'gulpIf',
        'gulp-minify-css': 'minifyCss',
        'gulp-util': 'gulpUtil'
      }
    });


// Install bower components in specified folder
gulp.task('bower', function() {
  return plugins.bower()
    .pipe(gulp.dest('./bower_components'));
});

// Error handler
var onError = function (err) {
  plugins.gulpUtil.beep();
  console.log(err);
  this.emit('end');
};

// Templates
gulp.task('templates', function() {
  return gulp.src(src_path+'**/*.jade')

    // Catch errors
    .pipe(plugins.plumber({errorHandler: onError}))

    // Watch if files are changed agains html files
    .pipe(plugins.changed(build_path, {extension: '.html'}))

    // If watching, use the cached jade files in the pipeline
    .pipe(plugins.gulpIf(global.isWatching, plugins.cached('jade')))

    // Check partial dependency
    .pipe(plugins.jadeInheritance({basedir: src_path}))

    // Ignore files starting with an underscore
    .pipe(plugins.filter(function (file) {
      return !/\/_/.test(file.path) && !/^_/.test(file.relative);
    }))

    // Output jade
    .pipe(plugins.jade({pretty: true}))

    // catch errors
    .pipe(plugins.plumber({errorHandler: onError}))

    // Distribute to build
    .pipe(gulp.dest(build_path))

    // Show notification
    .pipe(plugins.gulpIf(global.isWatching, plugins.notify({ message: 'Templates task complete' })));

});

// Styles
gulp.task('styles', function() {
  return gulp.src(src_path+'assets/sass/screen.scss')

    // Catch errors
    .pipe(plugins.plumber({errorHandler: onError}))

    // Parse Sass
    .pipe(plugins.sass({outputStyle: 'nested'}))

    // Add autoprefixes
    .pipe(plugins.autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))

    // Distribute to destination paths
    .pipe(gulp.dest(build_path+'assets/css/'))
    .pipe(gulp.dest(theme_path + '/assets/css/'))

    // Add .min version
    .pipe(plugins.rename({ suffix: '.min' }))

    // Minify .min version
    .pipe(plugins.minifyCss())

    // Distribute to destination paths
    .pipe(gulp.dest(build_path+'assets/css/'))
    .pipe(gulp.dest(theme_path + '/assets/css/'))

    // Show notifcation
    .pipe(plugins.gulpIf(global.isWatching, plugins.notify({ message: 'Styles task complete' })));
});

// Scripts
gulp.task('scripts', function() {
  return gulp.src([
      src_path+'assets/js/vendor/moment.js',
      src_path+'assets/js/vendor/pikaday.js',
      src_path+'assets/js/vendor/jquery.validate.js',
      src_path+'assets/js/vendor/*.js',
      src_path+'assets/js/partials/*.js',
      src_path+'assets/js/*.js'
    ])

    // Catch erros
    .pipe(plugins.plumber({errorHandler: onError}))

    // Concatenate scripts
    .pipe(plugins.concat('script.js'))

    // Distribute to paths
    .pipe(gulp.dest(build_path + 'assets/js/'))
    .pipe(gulp.dest(theme_path + 'assets/js/'))

    // Add minified version
    .pipe(plugins.rename({ suffix: '.min' }))

    // Minify with jsUglify
    .pipe(plugins.uglify())

    // Distribute to paths
    .pipe(gulp.dest(build_path + 'assets/js/'))
    .pipe(gulp.dest(theme_path + 'assets/js/'))

    // Show notification
    .pipe(plugins.gulpIf(global.isWatching, plugins.notify({ message: 'Scripts task complete' })));
});

// Images
gulp.task('images', function() {
  return gulp.src(src_path+'assets/img/**/**/*')

    // Optimize images
    .pipe(plugins.cache(plugins.imagemin({
      optimizationLevel: 3,
      progressive: true,
      interlaced: true
    })))

    // Distribute to paths
    .pipe(gulp.dest(build_path + 'assets/img/'))
    .pipe(gulp.dest(theme_path + 'assets/img/'))

    // Show notification
    .pipe(plugins.gulpIf(global.isWatching, plugins.notify({ message: 'Scripts task complete' })));
});

// Clear (image) cache
gulp.task('clear', function (done) {
  return cache.clearAll(done);
});

// Clean build folder
gulp.task('clean', function(cb) {
  del([build_path], cb);
});

// Copy font files
gulp.task('copyfonts', function() {
  gulp.src(src_path+'assets/fonts/**/*')
  .pipe(gulp.dest(build_path+'assets/fonts'))
  .pipe(gulp.dest(theme_path+'assets/fonts'));
});

// Default task
gulp.task('default', ['clean', 'bower'], function() {
  gulp.start('templates', 'styles', 'scripts', 'images', 'copyfonts');
});

// Set global watch to true
gulp.task('setWatch', function() {
  global.isWatching = true;
});

// Watch
gulp.task('watch', ['setWatch', 'templates'], function() {

  // Watch .scss files
  gulp.watch(src_path+'assets/sass/**/*.scss', ['styles']);

  // Watch .jade files
  gulp.watch(src_path+'**/*.jade', ['templates']);

  // Watch images
  gulp.watch(src_path+'assets/img/**/*', ['images']);

  // Watch .js files
  gulp.watch(src_path+'assets/js/**/*.js', ['scripts']);

  // Copy stuff
  gulp.watch(src_path+'assets/fonts/**/*', ['copyfonts']);

  // Create LiveReload server
  plugins.livereload.listen();

  // Watch any files in build/, reload on change
  gulp.watch([build_path+'/**/**/*']).on('change', plugins.livereload.changed);

});