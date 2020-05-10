/**
 * Returns an action object used in setting the markdownFiles in the state
 *
 * @param {Array} markdownFiles Array of markdownFiles received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setMarkdownFiles( markdownFiles, errorMessage ) {
	return {
		type: 'SET_MARKDOWN_FILES',
		markdownFiles,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the markdownFiles must be received.
 *
 * @param {string} langSources Imported Markdown file to load
 *
 * @return {Object} Action object.
 */
export function receiveMarkdownFiles( lang, fileName, defaultValue ) {
	return {
		type: 'RECEIVE_MARKDOWN_FILES',
		lang,
		fileName,
		defaultValue,
	};
};
