/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Modal } from '@wordpress/components';
import {
	welcomeGuideMarkdown,
} from '../markdown';

const EndpointModal = ( props ) => {
	return (
		<Modal 
			{ ...props }
			contentLabel={ __('Endpoint modal', 'graphql-api') } 
			title={ __('Endpoint modal', 'graphql-api') } 
		>
			<div
				dangerouslySetInnerHTML={ { __html: welcomeGuideMarkdown } } 
			/>
		</Modal>
	)
}
const EndpointModalButton = ( props ) => {
	const [ isOpen, setOpen ] = useState( false );
	return (
		<>
			<Button isSecondary onClick={ () => setOpen( true ) }>
				{ __('Open modal', 'graphql-api') }
			</Button>
			{ isOpen && (
				<EndpointModal 
					{ ...props }
					onRequestClose={ () => setOpen( false ) }
				/>
			) }
		</>
	);
};
export default EndpointModalButton;
