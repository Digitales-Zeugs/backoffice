const mix = require('laravel-mix');

const TargetsPlugin = require('targets-webpack-plugin');

mix.webpackConfig({
    plugins: [
        new TargetsPlugin({
          browsers: ['last 2 versions', 'chrome >= 41', 'IE 11'],
    }),
]});

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/dashboard.js', 'public/js');

mix.js('resources/js/login.js', 'public/js');

mix.js('resources/js/members.index.js', 'public/js');
mix.js('resources/js/members.view.js', 'public/js');

mix.js('resources/js/works.index.js', 'public/js');
mix.js('resources/js/works.view.js', 'public/js');

mix.js('resources/js/jingles.index.js', 'public/js');
mix.js('resources/js/jingles.view.js', 'public/js');

mix.js('resources/js/integration.js', 'public/js');
