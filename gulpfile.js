let gulp = require('gulp');
let cleanCSS = require('gulp-clean-css');
let sourcemaps = require('gulp-sourcemaps');
let autoprefixer = require('gulp-autoprefixer');
let concat = require('gulp-concat');

gulp.task('ios-css', function() {
    return gulp.src('tpg/resources/css/tpgwidget.css')
    .pipe(sourcemaps.init())
    .pipe(autoprefixer())
    .pipe(concat('tpgwidget.min.css'))
    .pipe(cleanCSS())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('tpg/resources/css/'));
});

gulp.task('android-css', function() {
    return gulp.src('tpga/resources/css/tpgwidget.css')
    .pipe(sourcemaps.init())
    .pipe(autoprefixer())
    .pipe(concat('tpgwidget.min.css'))
    .pipe(cleanCSS())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('tpga/resources/css/'));
});
