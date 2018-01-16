var path = require('path');
var webpack = require('webpack');

var config = {
  entry: {
    'app/themes/printingplate/assets/js/app': './resources/assets/js/index.js'
  },
  output: {
    path: path.join(__dirname, '../../../'),
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        test: /\.js?/,
        exclude: /node_modules/,
        loaders: 'babel-loader',
      }
    ]
  },
  devtool: 'source-map'
};

module.exports = config;
