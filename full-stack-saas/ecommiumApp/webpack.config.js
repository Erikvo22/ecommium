var Encore = require('@symfony/webpack-encore');
const { VueLoaderPlugin } = require('vue-loader')

Encore
    .setOutputPath('public/build')

    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')

    .cleanupOutputBeforeBuild()

    .addPlugin(new VueLoaderPlugin())

    .enableVueLoader()

;

module.exports = Encore.getWebpackConfig();