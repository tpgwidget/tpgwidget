const gulp = require('gulp');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');

gulp.task('ios-css', () =>
    gulp.src('tpg/resources/css/tpgwidget.css')
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(concat('tpgwidget.min.css'))
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('tpg/resources/css/'))
);

gulp.task('ios-js', () =>
    gulp.src('tpg/resources/js/tpgwidget.js')
        .pipe(babel())
        .pipe(uglify())
        .pipe(concat('tpgwidget.min.js'))
        .pipe(gulp.dest('tpg/resources/js/'))
);

gulp.task('android-css', () =>
    gulp.src('tpga/resources/css/tpgwidget.css')
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(concat('tpgwidget.min.css'))
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('tpga/resources/css/'))
);
