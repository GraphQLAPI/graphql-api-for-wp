const config = require( '@wordpress/scripts/config/webpack.config' );

/**
 * Add SCSS
 */
config.module.rules.push( {
	test: /\.s[ac]ss$/i,
	use: [
		// Creates `style` nodes from JS strings
		'style-loader',
		// Translates CSS into CommonJS
		'css-loader',
		// Compiles Sass to CSS
		'sass-loader',
	],
} );

module.exports = config;
