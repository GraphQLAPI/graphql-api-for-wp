
/**
 * Receive Markdown files
 * 
 * Use webpackMode: "eager" because the module with the translated docs has already been loaded:
 * @see https://github.com/webpack/webpack/issues/4807#issuecomment-300443876
 * 
 * Use { default: _ } for some reason (it doesn't work):
 * @see https://webpack.js.org/guides/code-splitting/#dynamic-imports
 */
const controls = {
	RECEIVE_MARKDOWN_FILES( action ) {
		return import( /* webpackMode: "eager" */ `../../guides/${ action.lang }/${ action.fileName }.md` )
			.then(obj => obj.default)
			// .then( ( { default: _ } ) )
			.catch(err => action.defaultValue );
	},
};

export default controls;
