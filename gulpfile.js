const gulp = require('gulp');
const cleancss = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const autoprefixer = require('gulp-autoprefixer');
const del = require('del');
const browserSync = require('browser-sync').create();
const sass = require('gulp-sass')(require('sass'));
const rename = require('gulp-rename');
const notify = require('gulp-notify');

// Paths
const paths = {
  src: {
    scss: 'src/assets/styles/scss/**/*.scss',
    css: 'src/assets/styles/css/',
    js: 'src/assets/scripts/**/*.js',
    html: 'src/pages/**/*.html',
    img: 'src/assets/img/**/*',
  },
  dist: {
    css: 'dist/css/',
    js: 'dist/js/',
    html: 'dist/',
    img: 'dist/img/',
  },
};

// Clean tasks
gulp.task('clean:css', function () {
  return del([`${paths.src.css}*.*`, `${paths.dist.css}*.*`]);
});

gulp.task('clean:js', function () {
  return del([`${paths.dist.js}*.js`]);
});

gulp.task('clean:html', function () {
  return del([`${paths.dist.html}*.html`]);
});

// SCSS to CSS with Autoprefixer
gulp.task('sass', function () {
  return gulp.src(paths.src.scss)
      .pipe(sass({ outputStyle: 'expanded' }).on('error', notify.onError({
        title: 'SASS Compilation Error',
        message: '<%= error.message %>',
      })))
      .pipe(autoprefixer({
        overrideBrowserslist: ['last 2 versions'],
        cascade: false,
      }))
      .pipe(gulp.dest(paths.src.css))
      .pipe(cleancss())
      .pipe(rename({ suffix: '.min' }))
      .pipe(gulp.dest(paths.dist.css))
      .pipe(browserSync.stream());
});

// JavaScript Minification
gulp.task('js', function () {
  return gulp.src(paths.src.js)
      .pipe(uglify().on('error', notify.onError({
        title: 'JS Minification Error',
        message: '<%= error.message %>',
      })))
      .pipe(gulp.dest(paths.dist.js))
      .pipe(browserSync.stream());
});

// HTML Copy
gulp.task('html', function () {
  return gulp.src(paths.src.html)
      .pipe(gulp.dest(paths.dist.html))
      .pipe(browserSync.stream());
});

// BrowserSync
gulp.task('browserSync', function () {
  browserSync.init({
    server: {
      baseDir: 'dist',
    },
    port: 3000,
    notify: false,
    startPath: 'home.html',
  });
  browserSync.watch('dist/**/*.*').on('change',browserSync.reload);
  gulp.watch(paths.src.scss, gulp.series('sass'));
  gulp.watch(paths.src.js, gulp.series('js'));
  gulp.watch(paths.src.html, gulp.series('html'));
});

// Default task
gulp.task('default', gulp.series(
    gulp.parallel('clean:css', 'clean:js', 'clean:html'),
    gulp.parallel('sass', 'js', 'html'),
    'browserSync'
));

// Build task
gulp.task('build', gulp.series(
    gulp.parallel('clean:css', 'clean:js', 'clean:html'),
    gulp.parallel('sass', 'js', 'html')
));