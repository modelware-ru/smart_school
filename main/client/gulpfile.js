import gulp from 'gulp';

const { src, dest, watch, parallel, series, tree } = gulp;
import postcss from 'gulp-postcss';
import postcssImport from 'postcss-import';
import autoprefixer from 'autoprefixer';
import newer from 'gulp-newer';
import svgSprite from 'gulp-svg-sprite';
import fonter from 'gulp-fonter-unx';
import ttf2woff2 from 'gulp-ttf2woff2';

const destPath = `../server/www`;

const fontMaker = () => {
  return src([`./font/**/*.ttf`])
    .pipe(
      fonter({
        formats: ['woff'],
      })
    )
    .pipe(src([`./font/**/*.ttf`]))
    .pipe(
      newer({
        dest: destPath + '/font',
        ext: '.woff2',
      })
    )
    .pipe(ttf2woff2())
    .pipe(dest(destPath + '/font'));
};

const styleMaker = () => {
  return src(`./style/style.css`)
    .pipe(postcss([postcssImport(), autoprefixer()]))
    .pipe(dest(destPath + '/style'));
};

// const spriteMaker = () => {
//   return src('./image/icon/*.svg')
//     .pipe(
//       svgSprite({
//         mode: {
//           stack: {
//             sprite: '../sprive.svg',
//             example: true,
//           },
//         },
//       })
//     )
//     .pipe(dest(destPath + '/image'));
// };

// const watchingApp = () => {
//   watch(
//     [
//       '../host/www/**/*.php',
//       './page/**/*.js',
//       './widget/**/*.js',
//       './atom/**/*.js',
//       './page/**/*.css',
//       './widget/**/*.css',
//       './atom/**/*.css',
//       './style/*.css',
//       './tailwind.config.js',
//     ],
//     styleMakerApp
//   );
// };

// const watchingUiKit = () => {
//   watch(
//     [
//       '../host/www/**/*.php',
//       './uikit/**/*.js',
//       './widget/**/*.js',
//       './atom/**/*.js',
//       './uikit/**/*.css',
//       './widget/**/*.css',
//       './atom/**/*.css',
//       './style/style-uikit.css',
//       './style/*.css',
//       './tailwind-uikit.config.js',
//     ],
//     styleMakerUiKit
//   );
// };

const app = series(/*fontMaker, */styleMaker);

export { app };
