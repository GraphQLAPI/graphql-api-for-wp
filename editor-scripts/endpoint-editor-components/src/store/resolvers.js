/**
 * External dependencies
 */
import {
	receiveMarkdownFiles,
	setMarkdownFiles,
} from './action-creators';

export default {
	/**
	 * Fetch the markdownFiles from the GraphQL server
	 */
	* getMarkdownFiles( langSources ) {

		const lang = 'es';// (Math.random() > 0.5) ? 'es' : 'de';
		let response = [];
		const fileNames = Object.keys(langSources);
		for (let i = 0; i < fileNames.length; i++) {
			response.push( yield receiveMarkdownFiles( lang, fileNames[i], langSources[ fileNames[i] ] ) )
		}
		return setMarkdownFiles( response );
	},
};
