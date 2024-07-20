import gulp from 'gulp';
const { watch } = gulp;
import fileInclude from 'gulp-file-include';
import formatHTML from 'gulp-format-html';

const { src, dest, series } = gulp;

const destPath = `../page`;

const htmlMaker = () => {
  return src([`./html/*.html`]).pipe(fileInclude()).pipe(formatHTML()).pipe(dest(destPath));
};

const watcher = () => {
  watch([`./html/**/*.html`], htmlMaker);
};

const buildMockup = series(htmlMaker);
const watchMockup = series(htmlMaker, watcher);

export { buildMockup, watchMockup };
