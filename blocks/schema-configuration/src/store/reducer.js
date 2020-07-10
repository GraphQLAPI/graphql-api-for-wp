/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	schemaConfigurations: [],
	hasRetrievedSchemaConfigurations: false,
	retrievingSchemaConfigurationsErrorMessage: null,
};

/**
 * Reducer returning an array of types and their fields, and schemaConfigurations.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const schemaConfigurations = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_SCHEMA_CONFIGURATIONS':
			// Commented since adding custom `config.output.jsonpFunction` to `webpack.config.js` solves the issue
			// // ------------------------------------------------------
			// // IMPORTANT: THIS CODE IS NEEDED TO FIX A BUG
			// // @see: https://github.com/WordPress/gutenberg/issues/23607#issuecomment-654355737
			// // The spread operator does not work, so use Object.assign instead
			// // ------------------------------------------------------
			// return Object.assign( {}, state, {
			// 	schemaConfigurations: action.schemaConfigurations,
			// 	hasRetrievedSchemaConfigurations: true,
			// 	retrievingSchemaConfigurationsErrorMessage: action.errorMessage,
			// } );
			// // ------------------------------------------------------
			// // The commented code below makes the block not work after compiling with `npm run build`
			// // ------------------------------------------------------
			return {
				...state,
				schemaConfigurations: action.schemaConfigurations,
				hasRetrievedSchemaConfigurations: true,
				retrievingSchemaConfigurationsErrorMessage: action.errorMessage,
			};
			// // ------------------------------------------------------

	}
	return state;
};

export default schemaConfigurations;
