/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { /*ExternalLink, */Button, Guide, GuidePage } from '@wordpress/components';
import {
	welcomeGuideMarkdown,
	schemaConfigOptionsMarkdown,
} from '../../guides';
import { compose, withState } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';

const EndpointGuide = ( props ) => {
	const { pages } = props;
	return (
		<Guide
			{ ...props }
			contentLabel={ __('Endpoint guide', 'graphql-api') } 
		>
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
	return (
		<>
			<Button isSecondary onClick={ () => setOpen( true ) }>
				{ __('Open tutorial guide', 'graphql-api') }
			</Button>
			{ isOpen && (
				<EndpointGuide 
					{ ...props }
					onFinish={ () => setOpen( false ) }
				/>
			) }
		</>
	);
};
export default compose( [
	withSelect( ( select ) => {
		const {
			getMarkdownFiles,
			// hasRetrievedMarkdownFiles,
			// getRetrievingMarkdownFilesErrorMessage,
		} = select ( 'graphql-api/markdown-file' );
		const langSources = {
			'welcome-guide': schemaConfigOptionsMarkdown,
			'schema-config-options': welcomeGuideMarkdown,
		}
		return { 
			pages: getMarkdownFiles( langSources )
		};
	} ),
] )( EndpointGuideButton );
// export default EndpointGuideButton;
