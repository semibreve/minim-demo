// Promise polyfill.
var Promise = require('es6-promise').Promise;

// Gulp itself.
var gulp = require('gulp');

// Less and CSS stuff.
var less = require('gulp-less');
var prefix = require('gulp-autoprefixer');
var cleancss = require('gulp-clean-css');

// Compile all the Less.
gulp.task('less', function () {
    return gulp.src(['./web/less/*.less'])
        .pipe(less())
        .pipe(prefix(
            "last 1 version", "> 1%", "ie 8", "ie 7"
        ))
        .pipe(cleancss()) // Minify resulting CSS.
        .pipe(gulp.dest('./web/css'));
});

gulp.task('watch', function() {
    // Compile Less.
    return gulp.watch("./web/less/*.less", function(event) {
        gulp.run('less');
    });
});

gulp.task('default', gulp.series('less'));
