/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { /*ExternalLink, */Button, Guide, GuidePage } from '@wordpress/components';
import {
	welcomeGuideMarkdown,
	schemaConfigOptionsMarkdown,
} from '../markdown';

const EndpointGuide = ( props ) => {
	const pages = [
		welcomeGuideMarkdown,
		schemaConfigOptionsMarkdown,
	]
	return (
		<Guide { ...props } >
			{ pages.map( page => (
				<GuidePage
					{ ...props }
					dangerouslySetInnerHTML={ { __html: page } } 
				/>
			) ) }
		</Guide>
	)
}
const EndpointGuideButton = ( props ) => {
	const [ isOpen, setOpen ] = useState( false );

	const openGuide = () => setOpen( true );
	const closeGuide = () => setOpen( false );
	return (
		<>
			<Button isSecondary onClick={ openGuide }>
				{ __('Open tutorial guide', 'graphql-api') }
			</Button>
			{ isOpen && (
				<EndpointGuide 
					{ ...props }
					onFinish={ closeGuide }
				/>
			) }
		</>
	);
};
export default EndpointGuideButton;
