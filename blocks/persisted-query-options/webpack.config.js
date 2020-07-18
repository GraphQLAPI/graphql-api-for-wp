const path = require( 'path' );

const config = require( '@wordpress/scripts/config/webpack.config' );

/**
 * Documentation in different languages
 */
langs = ['en']
langs.forEach( lang => config.entry[`docs-${ lang }`] = path.resolve( process.cwd(), `docs/${ lang }`, 'index.js' ) )
config.resolve.alias['@docs'] = path.resolve(process.cwd(), 'docs/')

// ---------------------------------------------
// Uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
// config.entry.index = {
// 	import: path.resolve( process.cwd(), 'src', 'index.js' ),
// 	dependOn: 'docs'
// }
// config.entry.docs = langs.map( lang => `docs-${ lang }` )
// ---------------------------------------------

/**
 * Add support for additional file types
 */
config.module.rules.push(
	/**
	 * Markdown
	 */
	{
		test: /\.md$/,
		use: [
			{
				loader: "html-loader"
			},
			{
				loader: "markdown-loader"
			}
		]
	}
);

module.exports = config;
