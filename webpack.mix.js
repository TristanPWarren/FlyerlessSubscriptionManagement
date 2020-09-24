const mix = require('laravel-mix');

mix.setPublicPath('./public');

mix.js('resources/js/module.js', 'public/modules/flyerless-subscription-management/js')
    .sass('resources/sass/module.scss', 'public/modules/flyerless-subscription-management/css');
