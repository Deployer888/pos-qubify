const mix = require('laravel-mix');
const path = require("path");

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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

// mix.js('resources/js/app.js', 'public/js').webpackConfig({
//     output: {
//         chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
//         publicPath: '/',
//     }
// }).version().vue();

mix.setResourceRoot(process.env.MIX_ASSET_URL);
if (process.env.MIX_ENV_MODE == 'production') {
    mix.js('resources/js/app.js', 'public/js').webpackConfig({
        output: {
            chunkFilename: 'public/js/chunks/[name].js?id=[chunkhash]',
            publicPath: 'auto',
            path: path.resolve(__dirname, 'public'),
        }
    }).version().vue();
} else {
    mix.js('resources/js/app.js', 'public/js').webpackConfig({
        output: {
            chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
            publicPath: '/',
        }
    }).version().vue();
}

