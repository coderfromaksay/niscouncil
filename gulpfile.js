const gulp = require('gulp');
const cleancss = require('gulp-clean-css');
const cleanjs = require('gulp-uglify');
const autoprefix = require('gulp-autoprefixer');
const del = require('del');
const bsync = require('browser-sync').create();
const sass = require('gulp-sass')(require('sass'));
const rename = require('gulp-rename');
const notify = require('gulp-notify');
const gutil = require('gulp-util');

// var deploy = require('gulp-gh-pages');
//
// gulp.task('deploy', function () {
//   return gulp.src("./homePage/public/**/*")
//     .pipe(deploy())
// });

gulp.task('cleanjs',async function () {
  return gulp.src('teamPage/app/js/*.js')
    .pipe(cleanjs().on('error', gutil.log))
    .pipe(gulp.dest('teamPage/public/js/'));
});

gulp.task('prefix', function () {
  return gulp.src('teamPage/app/styles/css/*.css')
  .pipe(autoprefix({
    overrideBrowserslist: ['last 20 versions'],
    cascade: false
  }))
  .pipe(cleancss())
  .pipe(rename({suffix:'.min'}))
  .pipe(gulp.dest('teamPage/public/css/'));
});

gulp.task('bsync',function () {
  bsync.init({
    server:'teamPage/public'
  });
  bsync.watch('teamPage/public/**/*.*').on('change',bsync.reload);
});

gulp.task('deljs', function () {
  return (del('teamPage/public/js/*.js'));
});

gulp.task('delcss', function () {
  return del('teamPage/app/styles/css/*.*');
  del('teamPage/public/css/style.min.css');
});

gulp.task('sassToCss', function () {
  return gulp.src('teamPage/app/styles/scss/*.scss')
  .pipe(sass({
    errorLogToConsole:true
  }))
  .on( 'error', notify.onError({
           title: 'Sass Compilation Failed',
           message: '<%= error.message %>'
       })
     )
  .pipe(rename('style.css'))
  .pipe(gulp.dest('teamPage/app/styles/css/'));
});

gulp.task('watchfiles', function () {
  gulp.watch('teamPage/app/styles/scss/*.scss', gulp.series('sassToCss'));//series- делать таски один за одним
  gulp.watch('teamPage/app/styles/css/*.css', gulp.series('prefix'));
  gulp.watch('teamPage/app/js/*.*', gulp.series('cleanjs'));
});

// gulp.task('default',gulp.parallel('watchfiles', 'bsync'));//parallel-делает таски паралельно
gulp.task('default', gulp.series(gulp.parallel('delcss','deljs'),gulp.parallel('watchfiles','bsync')));//parallel-делает таски паралельно
gulp.task('build', gulp.series(gulp.parallel('delcss','deljs')));