var gulp  =  require('gulp');
     sass = require('gulp-sass');
     uglify = require('gulp-uglify');
     concat = require('gulp-concat');

// 编译sass
 gulp.task('sass', function(){
 	return gulp.src('./css/index.scss')
 		.pipe(sass())
 		.pipe(gulp.dest('./css'));
 		// .pipe(uglify())
 		// .pipe(gulp.dest('css/h'));
 });

// 合并 压缩js文件
  gulp.task('js', function(){
 	return gulp.src('./js/*.js')
 		.pipe(concat('jike.js'))
 		.pipe(gulp.dest('js'))
 		.pipe(uglify())
 		.pipe(gulp.dest('./js'));
 });

// 监听sass文件
gulp.task('watch', function(){
 	return gulp.watch('./css/index.scss', ['sass']);
 });

gulp.task('default', ['sass','js','watch']);