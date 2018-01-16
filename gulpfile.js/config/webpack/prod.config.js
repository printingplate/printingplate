var path = require('path');
var webpack = require('webpack');
var webpackUglifyJsPlugin = require('webpack-uglify-js-plugin');

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
  plugins: [
    new webpack.DefinePlugin({
      'process.env':{
        'NODE_ENV': JSON.stringify('production')
      },
    }),
    new webpack.optimize.UglifyJsPlugin({
      minimize: true
    })
  ]
};

module.exports = config;
