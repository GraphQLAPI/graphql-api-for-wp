/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	accessControlLists: [],
	hasRetrievedAccessControlLists: false,
	retrievingAccessControlListsErrorMessage: null,
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
		case 'SET_ACCESS_CONTROL_LISTS':
			return {
				...state,
				accessControlLists: action.accessControlLists,
				hasRetrievedAccessControlLists: true,
				retrievingAccessControlListsErrorMessage: action.errorMessage,
			};
	}
	return state;
};

export default schemaInstrospection;
