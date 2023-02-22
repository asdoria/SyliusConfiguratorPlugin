const Encore = require('@symfony/webpack-encore');
const path = require('path');
const fs = require('fs');


const uiBundleScripts = path.resolve(__dirname, '../../sylius/sylius/src/Sylius/Bundle/UiBundle/Resources/private/js/');
const basePath = path.resolve(__dirname, './');
const assets_path = path.join(basePath, './src/Resources/private');
const output_path = path.join(basePath, './src/Resources/public');
const public_path = 'bundles/asdoriasyliusconfiguratorplugin';
const sass_path = path.join(assets_path, './sass');
const js_path = path.join(assets_path, './js');
const isProduction = Encore.isProduction();

Encore
  // empty the outputPath dir cd ../before each build
  .cleanupOutputBeforeBuild()

  // directory where all compiled assets will be stored
  .setOutputPath(output_path)

  .setPublicPath('/' + public_path)
  .setManifestKeyPrefix(public_path)

  .addEntry('shop-configurator', [
    path.join(js_path, './shop.js'),
    path.join(sass_path, './shop.scss'),
  ])
  .addEntry('admin-configurator', [
    path.join(js_path, './admin.js'),
    path.join(sass_path, './admin.scss'),
  ])

  // allow sass/scss files to be processed
  .enableSassLoader()
  // .enablePostCssLoader()

  // allow legacy applications to use $/jQuery as a global variable
  .autoProvidejQuery()

  .enableSourceMaps(!isProduction)

  .disableSingleRuntimeChunk()

  // create hashed filenames (e.g. app.abc123.css)
  .enableVersioning(isProduction)
  .configureFilenames({
    js: '[name].min.js',
    css: '[name].min.css',
  })
;

const config = Encore.getWebpackConfig();
config.resolve.alias['sylius/ui'] = uiBundleScripts;
module.exports = config;
