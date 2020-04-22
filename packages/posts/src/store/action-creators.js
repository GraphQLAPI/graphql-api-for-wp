/**
 * Returns an action object used in setting the accessControlLists in the state
 *
 * @param {Array} accessControlLists Array of accessControlLists received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setAccessControlLists( accessControlLists, errorMessage ) {
	return {
		type: 'SET_ACCESS_CONTROL_LISTS',
		accessControlLists,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the accessControlLists must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveAccessControlLists( query ) {
	return {
		type: 'RECEIVE_ACCESS_CONTROL_LISTS',
		query,
	};
};
