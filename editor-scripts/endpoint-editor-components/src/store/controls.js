
/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_MARKDOWN_FILES( action ) {
		// return import( `../markdown/${ action.lang }/${ action.fileName }.md` )
		// return import( /* webpackChunkName: "guides/[request]" */ `../../guides/${ action.lang }/${ action.fileName }.md` )
		return import( /* webpackMode: "eager" */ `../../guides/${ action.lang }/${ action.fileName }.md` )
			.then(obj => obj.default)
			// .then( { default: _ } )
			.catch(err => action.defaultValue );
	},
};

export default controls;
