const mix = require('laravel-mix');
const config = require('./webpack.config');
require('laravel-mix-versionhash');

mix
  .js('resources/js/app.js', 'public/dist/js')
  .vue({ version: 2 })
  .sass('resources/sass/app.scss', 'public/dist/css')
  .options({
    postCss: [require('tailwindcss')],
    processCssUrls: false
  })
  .sourceMaps()
  .disableNotifications();

if (mix.inProduction()) {
  mix.versionHash();
} else {
  mix.sourceMaps();
}

mix.webpackConfig(config);
