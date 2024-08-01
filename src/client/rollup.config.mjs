import babel from '@rollup/plugin-babel';

const pages = ['guestIndex', 'signIn', 'signUp', 'userIndex', 'message'];

const export_page = pages.reduce((acc, item) => {
  acc.push({
    input: `./script/${item}.js`,
    output: {
      file: `../server/www/js/${item}_bundle.js`,
      format: 'cjs',
      sourcemap: 'inline',
    },
    plugins: [babel({ babelHelpers: 'bundled' })],
  });
  return acc;
}, []);

export default export_page;
