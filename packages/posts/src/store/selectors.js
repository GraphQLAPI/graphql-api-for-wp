/**
 * Get the Access Control Lists from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of Access Control Lists
 */
export function getAccessControlLists( state ) {
	return state.accessControlLists;
};

/**
 * Have the Access Control Lists been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of Access Control Lists
 */
export function hasRetrievedAccessControlLists( state ) {
	return state.hasRetrievedAccessControlLists;
};

/**
 * Get the error message from retrieving the Access Control Lists from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingAccessControlListsErrorMessage( state ) {
	return state.retrievingAccessControlListsErrorMessage;
};
