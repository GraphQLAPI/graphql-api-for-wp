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
		typesAndFields: {},
		directives: {},
	},
	action
) => {
	switch ( action.type ) {
		case 'SET_TYPES_AND_FIELDS':
			return {
				...state,
				typesAndFields: action.typesAndFields,
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
