const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const path = require('path');

module.exports = {
	entry: "./app/index.js",
	output: {
		path: path.resolve(__dirname, 'js'),
		filename: "js.js",
		sourceMapFilename: 'js.map'
	},
	devtool: '#source-map',
	mode: 'development',
	module: {
		rules: [
			{
				test: /\.js?$/,
				loader: 'babel-loader'
			}
		]
	},
	plugins: [
		new UglifyJsPlugin({
			sourceMap: true,
			cache: false
		})
	]
};
