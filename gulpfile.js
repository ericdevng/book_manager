const { src, dest, watch } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const cleanCSS = require('gulp-clean-css'); // ← Nuevo plugin

const paths = {
  scss: './src/scss/*.scss',
  css: './public/'
};

function css() {
  return src(paths.scss)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS()) // ← Minificación agregada aquí
    .pipe(sourcemaps.write('.'))
    .pipe(dest(paths.css));
}

// Función para observar cambios
function watchFiles() {
  watch(paths.scss, css);
}

exports.css = css;
exports.watch = watchFiles;
exports.default = css;
