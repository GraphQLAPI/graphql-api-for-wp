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

const MarkdownInfoModalButton = ( props ) => {
	const { pageFilename } = props;
	const [ page, setPage ] = useState([]);
	const lang = 'es'
	const defaultLang = 'en'
	useEffect(() => {
		return getMarkdownContentOrUseDefault( lang, defaultLang, pageFilename ).then( value => {
			setPage( value )
		});
	}, []);
	return (
		<InfoModalButton
			{ ...props }
			content={ page }
		/>
	);
};
export default MarkdownInfoModalButton;
