/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { InfoModalButton } from '../../../../packages/components/src';

/**
 * Internal dependencies
 */
import { getMarkdownContentOrUseDefault } from '../markdown-loader';

const EndpointModalButton = ( props ) => {
	const [ page, setPage ] = useState([]);
	const lang = 'fr'
	const defaultLang = 'en'
	const markdownPageFilename = 'schema-config-options'
	useEffect(() => {
		return getMarkdownContentOrUseDefault( lang, defaultLang, markdownPageFilename ).then( value => {
			setPage( value )
		});
	}, []);
	return (
		<InfoModalButton
			{ ...props }
			title= { __('Using the options', 'graphql-api') }
			content={ page }
		/>
	);
};
export default EndpointModalButton;
