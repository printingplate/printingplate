var SRC_DIR = './resources/assets';
// var BUILD_DIR = './resources/frontend-build';
var BUILD_DIR = './app/themes/printingplate';

var config = {
  baseDir: {
    dest: BUILD_DIR,
    src: SRC_DIR
  },
  browserSync: {
    src: BUILD_DIR
  },
  fonts: {
    dest: BUILD_DIR + '/assets/fonts/',
    src: SRC_DIR + '/fonts/**/*'
  },
  html: {
    src: SRC_DIR + '/html/**/*.html',
    dest: BUILD_DIR
  },
	images: {
		src: SRC_DIR + '/img/**/*.{gif,jpg,png,svg}',
		dest: BUILD_DIR + '/assets/img'
	},
  sass: {
    watchSrc: SRC_DIR + '/sass/**/*.scss',
    src: SRC_DIR + '/sass/main-screen.scss',
    dest: BUILD_DIR + '/assets/css',
    settings: {
      outputStyle: 'compressed'
    }
  },
  svg: {
    src: SRC_DIR + '/svg-sprite/**/*.svg',
    dest: BUILD_DIR + '/assets/svg-sprite'
  }
};

module.exports = config;
