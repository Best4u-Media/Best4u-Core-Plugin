const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const magicImporter = require('node-sass-magic-importer')
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin')

const config = {
	entry: {
		frontend: './assets/src/js/frontend.js',
		backend: './assets/src/js/backend.js',
	},
	output: {
		path: path.resolve(__dirname, '../assets/dist/js'),
		filename: '[name].js',
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
						plugins: [
							'@babel/plugin-transform-async-to-generator',
							'@babel/plugin-proposal-object-rest-spread',
							[
								'@babel/plugin-transform-react-jsx',
								{
									pragma: 'wp.element.createElement',
								},
							],
						],
					},
				},
				exclude: /node_modules/,
			},
			{
				test: /\.css$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							importLoaders: 1,
						},
					},
					'postcss-loader',
				],
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					{
						loader: 'sass-loader',
						options: {
							sassOptions: {
								importer: magicImporter(),
							},
						},
					},
				],
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,
				type: 'asset',
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i,
				use: [
					{
						loader: 'file-loader',
					},
				],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin({ filename: '../css/[name].css' }),
		new ImageMinimizerPlugin({
			minimizerOptions: {
				plugins: [
					['gifsicle', { interlaced: true }],
					['jpegtran', { progressive: true }],
					['optipng', { optimizationLevel: 5 }],
					[
						'svgo',
						{
							plugins: [{ removeViewBox: false }],
						},
					],
				],
			},
		}),
	],
}

module.exports = config
