'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    sass = require('gulp-sass'),
    px2rem = require('gulp-px2rem'),
    px2remOptions = {replace: false},
    postCssOptions = {map: true},
    autoprefixer = require('gulp-autoprefixer'),
    cssmin = require('gulp-cssmin'),
    rename = require('gulp-rename'),
    rigger = require('gulp-rigger'),
    notify = require("gulp-notify"),
    browserSync = require('browser-sync'),
    reload = browserSync.reload,
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    del = require('del');

var path = {
  build: {
    html: './dist',
    js: './dist/assets/js',
    style: './dist/assets/css',
    img: './dist/assets/img'
  },
  src: {
    html: './dev/html/*.html',
    js: './dev/js/*.js',
    style: './dev/sass/*.scss',
    img: './dev/img/**/*'
  },
  watch: {
    html: './dev/html/**/*.html',
    js: './dev/js/**/*.js',
    style: './dev/sass/**/*.scss',
    img: './dev/img/**/*'
  }
};

gulp.task('rem', function() {
    gulp.src('./dist/assets/css/**/*.css')
        .pipe(px2rem(px2remOptions, postCssOptions))
        .pipe(gulp.dest('css'));
});

gulp.task('img:build', function() {
    // delete old files
    del([path.build.img] + '/**').then(paths => {
    	//console.log('Deleted files and folders:\n', paths.join('\n'));
    });
    //
	return gulp.src(path.src.img)
	.pipe(imagemin({
		progressive: true,
		svgoPlugins: [{removeViewBox: false}],
		use: [pngquant()]
	}))
	.pipe(gulp.dest(path.build.img));
});

// Browser Sync
var config = {
  server: {
    baseDir: 'dist'
  },
  tunnel: false,
  host: 'localhost',
  port: 9000,
  logPrefix: 'SmiteVils'
};

gulp.task('webserver', function() {
  browserSync(config);
});

// HTML
gulp.task('html', function() {
  gulp.src(path.src.html)
    .pipe(rigger())
    .pipe(gulp.dest(path.build.html))
    .pipe(reload({stream: true}));
});

gulp.task('sass', function () {
  gulp.src(path.src.style)
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(gulp.dest(path.build.style)) // put standart css
    .pipe(cssmin())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(path.build.style))
    .pipe(reload({stream: true})); // put minificid css
});

gulp.task('notify', function () {
	gulp.src(path.src.style)
	.pipe(notify("Откомпилил и собрал!"));
});

// Watch
gulp.task('watch', function(cb){
    gulp.watch(path.watch.style, ['sass']);
    gulp.watch(path.watch.html, ['html']);
    gulp.watch(path.watch.js, ['compress']);
    gulp.watch(path.watch.img, ['img:build']);
});

// custom watch
// gulp.task('stream', function () {
//     return gulp.src('css/**/*.css')
//     .pipe(watch('css/**/*.css'))
//     .pipe(gulp.dest('build'));
// });
gulp.task('stream', function(){
  watch([path.watch.html], function(event, cb) {
    gulp.start('html');
  });
      watch([path.watch.img], function(event, cb) {
    gulp.start('img:build');
  });
  // watch([path.watch.icons], function(event, cb) {
  //   gulp.start('icons:build');
  // });
  // watch([path.watch.fonts], function(event, cb) {
  //   gulp.start('fonts:build');
  // });
  watch([path.watch.style], function(event, cb) {
    gulp.start('sass');
  });
  watch([path.watch.js], function(event, cb) {
    gulp.start('compress');
  });
  //   watch([path.watch.docs], function(event, cb) {
  //   gulp.start('docs:build');
  // });
});

//compress
gulp.task('compress', function() {
  return gulp.src(path.src.js)
    .pipe(gulp.dest(path.build.js)) // put uncompress version
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(path.build.js)) // put compress version
    .pipe(reload({stream: true}));
});

gulp.task('default', ['sass', 'html', 'webserver', 'stream']);
