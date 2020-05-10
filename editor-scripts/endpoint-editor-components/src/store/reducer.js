/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	markdownFiles: [],
	hasRetrievedMarkdownFiles: false,
	retrievingMarkdownFilesErrorMessage: null,
};

/**
 * Reducer returning an array of types and their fields, and markdownFiles.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const markdownFiles = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_MARKDOWN_FILES':
			return {
				...state,
				markdownFiles: action.markdownFiles,
				hasRetrievedMarkdownFiles: true,
				retrievingMarkdownFilesErrorMessage: action.errorMessage,
			};
	}
	return state;
};

export default markdownFiles;
