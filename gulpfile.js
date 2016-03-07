'use strict';

var gulp = require('gulp'),
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
    reload = browserSync.reload;

var path = {
  build: {
    html: './dist',
    js: './dist/assets/js',
    style: './dist/assets/css'
  },
  src: {
    html: './dev/html/*.html',
    js: './dev/js/*.js',
    style: './dev/sass/*.scss'
  },
  watch: {
    html: './dev/html/**/*.html',
    js: './dev/js/*.js',
    style: './dev/sass/**/*.scss'
  }
};

gulp.task('rem', function() {
    gulp.src('./dist/assets/css/**/*.css')
        .pipe(px2rem(px2remOptions, postCssOptions))
        .pipe(gulp.dest('css'));
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
});

gulp.task('default', ['sass', 'html', 'webserver', 'watch']);
