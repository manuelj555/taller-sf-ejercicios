var gulp = require("gulp");
var sass = require("gulp-sass");
var concat = require("gulp-concat");
var nano = require("gulp-cssnano");
// var babel = require('gulp-babel');
var gulpif = require('gulp-if');
var gutil = require('gulp-util');
var uglify = require('gulp-uglify');

var isProd = gutil.env.env == 'prod';

gulp.task('css', function () {
    return gulp.src([
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        ])
        .pipe(sass()).on('error', sass.logError)
        .pipe(concat('app.css'))
        .pipe(nano())
        .pipe(gulp.dest('web/build'));
});

gulp.task('js', function () {
    // return gulp.src([])
    //     .pipe(concat('app.js'))
    //     .pipe(gulpif(isProd, uglify()))
    //     .pipe(gulp.dest('web/build'));
});

gulp.task('watch', function () {
    gulp.watch(['**/*.{css,scss}'], {cwd: 'app/Resources/assets/css/'}, ['css']);
    gulp.watch(['**/*.js'], {cwd: 'app/Resources/assets/js/'}, ['js']);
});

gulp.task('default', ['css', 'js']);