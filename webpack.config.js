const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
  ...defaultConfig,
  entry: {
    admin: path.resolve(process.cwd(), 'assets', 'js', 'admin.js'),
    'performance-collector': path.resolve(
      process.cwd(),
      'assets',
      'js',
      'performance-collector.js'
    ),
  },
  output: {
    filename: '[name].js',
    path: path.resolve(process.cwd(), 'build'),
  },
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader'],
      },
    ],
  },
};
