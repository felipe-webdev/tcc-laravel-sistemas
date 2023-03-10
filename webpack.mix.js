const mix = require('laravel-mix');

/*
  |--------------------------------------------------------------------------
  | Mix Asset Management
  |--------------------------------------------------------------------------
  |
  | Mix provides a clean, fluent API for defining some Webpack build steps
  | for your Laravel application. By default, we are compiling the Sass
  | file for the application as well as bundling up all the JS files.
  |
  */

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },
    ]
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  }
});

mix.babelConfig({
  plugins: ['@babel/plugin-syntax-dynamic-import'],
});

mix
  .vue()
  .js('resources/js/app.js', 'public/js')
  .copy('resources/css/app.css', 'public/css')
  .copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'public/js')
  .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css')
  .copy('node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'public/css')
  .copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');