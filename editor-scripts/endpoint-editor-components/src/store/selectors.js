/**
 * Get the markdownFiles from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of markdownFiles from the GraphQL schema
 */
export function getMarkdownFiles( state ) {
	return state.markdownFiles;
};

/**
 * Have the markdownFiles been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of markdownFiles from the GraphQL schema
 */
export function hasRetrievedMarkdownFiles( state ) {
	return state.hasRetrievedMarkdownFiles;
};

/**
 * Get the error message from retrieving the markdownFiles from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingMarkdownFilesErrorMessage( state ) {
	return state.retrievingMarkdownFilesErrorMessage;
};
