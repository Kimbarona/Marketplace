const mix = require('laravel-mix');


// dito mo plug ung mga plugins mo.
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
var CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');


var webpackConfig = {
    plugins: [
        new CaseSensitivePathsPlugin(),
        new VuetifyLoaderPlugin()
    ]
}

mix.webpackConfig(webpackConfig);
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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
