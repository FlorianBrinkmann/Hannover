const webpack = require('webpack');
const path = require('path');

module.exports = {
	mode: 'production',
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
	},
	plugins: [
		new webpack.BannerPlugin({
			banner: "Want to take a look at the JS before bundled by Webpack? Check out https://github.com/florianbrinkmann/hannover"
		})
	]
};
