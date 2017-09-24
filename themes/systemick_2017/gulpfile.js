/* File: gulpfile.js */

// grab our gulp packages
var gulp  = require('gulp'),
    //gutil = require('gulp-util'),
    sass   = require('gulp-sass');

// create a default task and just log a message
//gulp.task('default', function() {
  //return gutil.log('Gulp is running!')
//});

gulp.task('build-css', function() {
  return gulp.src('scss/**/*.scss')
    .pipe(sass())
    .pipe(gulp.dest('css'));
});

gulp.task('watch', function() {
  gulp.watch('scss/**/*.scss', ['build-css']);
});
