let mix = require('laravel-mix')
require('laravel-mix-purgecss')
require('laravel-mix-tailwind')

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.sass', 'public/css')
  .tailwind()
  .options({
    postCss: [ 
      require('autoprefixer')({
        browsers: ['last 40 versions'],
      }) 
    ]
  }).purgeCss()

if (mix.inProduction()) {
  mix.version()
}

mix.browserSync('sankalan-portal.test')