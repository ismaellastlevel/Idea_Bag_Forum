const gulp = require('gulp'),
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    cssnano = require('cssnano'),
    sourcemaps = require('gulp-sourcemaps');

function compileSassToCss() {
    return(
        gulp
            .src('scss/*.scss')
            .pipe(sourcemaps.init())
            .pipe(sass()) //utilisation de gulp-sass
            .on('error', sass.logError)
            .pipe(postcss([autoprefixer(), cssnano()]))
            .pipe(sourcemaps.write())
            .pipe(gulp.dest('public/css')) //destination du résultat
    );
}

//  gulp --watch
function watchTask() {
    const watcher = gulp.watch('scss/**/*.scss');
    watcher.on('change', (path, stats) => {
        console.log('=== Compilation SASS ===');
        compileSassToCss();
    })
}


exports.default = gulp.series(watchTask);