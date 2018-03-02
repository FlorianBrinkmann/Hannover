const webpack = require('webpack');
const path = require('path');

module.exports = {
	mode: 'development',
	entry: ['./assets/js/src/functions.js'],
	output: {
		path: path.resolve(__dirname, 'assets/js'),
		filename: 'bundle.js',
	},
	module: {
		rules: [
			/**
			 * Running Babel on JS files.
			 */
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['env']
					}
				}
			},
		]
	}
};
