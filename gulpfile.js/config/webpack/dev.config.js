var path = require('path');
var webpack = require('webpack');

var config = {
  entry: {
    'app/themes/printingplate/assets/js/app': './resources/assets/js/index.js'
  },
  output: {
    path: path.join(__dirname, '../../../'),
    publicPath: '/js/',
    filename: '[name].js'
  },
  module: {
    loaders: [
      {
        test: /\.js?/,
        loaders: ['babel'],
        exclude: /node_modules/
      }
    ]
  },
  devtool: 'source-map'
};

module.exports = config;
