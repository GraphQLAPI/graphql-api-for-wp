/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	typeFields: [],
	fetchedTypeFields: false,
	directives: [],
	fetchedDirectives: false,
};

/**
 * Reducer returning an array of types and their fields, and directives.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const schemaInstrospection = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_TYPE_FIELDS':
			return {
				...state,
				typeFields: action.typeFields,
				fetchedTypeFields: true,
			};
		case 'SET_DIRECTIVES':
			return {
				...state,
				directives: action.directives,
				fetchedDirectives: true,
			};
	}
	return state;
};

export default schemaInstrospection;
