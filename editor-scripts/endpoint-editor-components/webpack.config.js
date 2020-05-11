const path = require( 'path' );

const config = require( '@wordpress/scripts/config/webpack.config' );
const isProduction = process.env.NODE_ENV === 'production';

/**
 * Documentation in different languages
 */
langs = ['en', 'es']
langs.forEach( lang => config.entry[`docs-${ lang }`] = path.resolve( process.cwd(), `docs/${ lang }`, 'index.js' ) )
config.resolve.alias['@endpointDocs'] = path.resolve(process.cwd(), 'docs/')

// ---------------------------------------------
// Uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
// config.entry.index = {
// 	import: path.resolve( process.cwd(), 'src', 'index.js' ),
// 	dependOn: 'docs'
// }
// config.entry.docs = ['docs-en', 'docs-es']
// ---------------------------------------------

/**
 * Add support for additional file types
 */
config.module.rules.push( 
	/**
	 * SCSS
	 */
	{
		test: /\.s[ac]ss$/i,
		use: [
			// Creates `style` nodes from JS strings
			'style-loader',
			// Translates CSS into CommonJS
			'css-loader',
			// Compiles Sass to CSS
			'sass-loader',
		],
	},
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

if ( ! isProduction ) {
	/**
	 * Exclude "node_modules" from "source-map-loader" (problem similar to https://github.com/angular-redux/store/issues/64)
	 * Otherwise we get error in GraphiQL:
	 *
	 * WARNING in ./node_modules/graphql-language-service-parser/esm/CharacterStream.js
	 * Module Warning (from ./node_modules/source-map-loader/index.js):
	 * (Emitted value instead of an instance of Error) Cannot find source file '../src/CharacterStream.ts': Error: Can't resolve '../src/CharacterStream.ts' in '.../node_modules/graphql-language-service-parser/esm'
	 *  @ ./node_modules/graphql-language-service-parser/esm/index.js 1:0-63 1:0-63
	 *  @ ./node_modules/codemirror-graphql/variables/mode.js
	 *  @ ./node_modules/graphiql/dist/components/VariableEditor.js
	 *  @ ./node_modules/graphiql/dist/components/GraphiQL.js
	 *  @ ./node_modules/graphiql/dist/index.js
	 *  @ ./src/EditBlock.js
	 *  @ ./src/index.js
	 *
	 * Because that rule was added using "unshift" (in file node_modules/@wordpress/scripts/config/webpack.config.js) then it's on the first position of the array
	 */
	config.module.rules[ 0 ].exclude = [ /node_modules/, /build/ ];
}

module.exports = config;
