var gulp = require("gulp");
var sass = require("gulp-sass");

var paths = {
    css: "./",
    scss: "./scss/**"
};

gulp.task("default");

gulp.task("watch", function () {
    gulp.watch(paths.scss, ["sass"]);
});

gulp.task("sass", function () {
    return gulp.src(paths.scss)
      .pipe(sass())
      .pipe(gulp.dest(paths.css));
});
