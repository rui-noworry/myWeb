var gulp  =  require('gulp'),
     sass = require('gulp-sass'),
     uglify = require('gulp-uglify'),
     concat = require('gulp-concat'),
     minifycss = require('gulp-minify-css'),
     rename = require('gulp-rename'),
     imagemin = require('gulp-imagemin');


// 编译sass
 gulp.task('sass', function(){
 	return gulp.src('./public/src/css/index.scss')
 		.pipe(sass())
 		.pipe(minifycss())
 		.pipe(gulp.dest('./public/dist/css'));
 });

// 合并 压缩js文件
  gulp.task('js', function(){
 	return gulp.src('./public/src/js/*.js')
 		.pipe(concat('jike.js'))
 		.pipe(gulp.dest('./public/dist/js'))
 		.pipe(rename({ suffix: '.min' }))
 		.pipe(uglify())
 		.pipe(gulp.dest('./public/dist/js'));
 });

  // 压缩图片
  gulp.task('imagemin', function(){
  	return gulp.src('./public/src/images/*')
  		.pipe(imagemin())
  		.pipe(gulp.dest('./public/dist/images'));
  })

// 监听sass文件
gulp.task('watch', function(){
 	return gulp.watch('./public/src/css/index.scss', ['sass']);
 });

gulp.task('default', ['sass','js','imagemin','watch']);