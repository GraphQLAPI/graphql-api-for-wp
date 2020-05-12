/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { Button, Guide, GuidePage } from '@wordpress/components';

const EndpointGuide = ( props ) => {
	const {
		pageFilenames,
		getMarkdownContentCallback,
	} = props;
	const [ pages, setPages ] = useState([]);
	useEffect(() => {
		const importPromises = pageFilenames.map(
			fileName => getMarkdownContentCallback( fileName )
		)
		Promise.all(importPromises).then( values => {
			setPages( values )
		});
	}, []);

	return (
		<Guide
			{ ...props }
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
	const {
		guideName,
		buttonLabel = guideName ? sprintf(
			__('→ Open Guide: “%s”', 'graphql-api'),
			guideName
		) : __('→ Open Guide', 'graphql-api'),
	} = props;
	const [ isOpen, setOpen ] = useState( false );
	return (
		<>
			<Button isTertiary onClick={ () => setOpen( true ) }>
				{ buttonLabel }
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
export default EndpointGuideButton;
