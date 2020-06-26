const config = require( '@wordpress/scripts/config/webpack.config' );
const isProduction = process.env.NODE_ENV === 'production';

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
