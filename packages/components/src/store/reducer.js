/**
 * Reducer returning an array of types and their fields, and directives.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const schemaInstrospection = (
	state = {
		typeFields: {},
		directives: {},
	},
	action
) => {
	switch ( action.type ) {
		case 'SET_TYPE_FIELDS':
			return {
				...state,
				typeFields: action.typeFields,
			};
		case 'SET_DIRECTIVES':
			return {
				...state,
				directives: action.directives,
			};
	}
	return state;
};

export default schemaInstrospection;
