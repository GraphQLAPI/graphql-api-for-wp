
/**
 * Returns an action object used in setting the typeFields object in the state
 *
 * @param {Array} typeFields Array of typeField objects received, where each object has key "type" for the type name, and key "fields" with an array of the type's fields.
 *
 * @return {Object} Action object.
 */
export function setTypeFields( typeFields ) {
	return {
		type: 'SET_TYPE_FIELDS',
		typeFields,
	};
};

/**
 * Returns an action object used in signalling that the typeFields object has been received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveTypeFields( query ) {
	return {
		type: 'RECEIVE_TYPE_FIELDS',
		query,
	};
};

/**
 * Returns an action object used in setting the directives object in the state
 *
 * @param {Array} directives Array of directives received.
 *
 * @return {Object} Action object.
 */
export function setDirectives( directives ) {
	return {
		type: 'SET_DIRECTIVES',
		directives,
	};
};

/**
 * Returns an action object used in signalling that the directives have been received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveDirectives( query ) {
	return {
		type: 'RECEIVE_DIRECTIVES',
		query,
	};
};
