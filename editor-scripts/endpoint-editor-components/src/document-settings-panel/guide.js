/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ExternalLink, Button, Guide, GuidePage } from '@wordpress/components';

const EndpointGuide = ( props ) => {
	return (
		<Guide { ...props } >
			<GuidePage>
				<h2>{ __('Tutorial video', 'graphql-api') }</h2>
				<video width="640" controls>
					<source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4" />
					{ __('Your browser does not support the video tag.', 'graphql-api') }
				</video> 
				<p>
					<ExternalLink
						href="https://vimeo.com/413503485"
					>
						{ __('Watch in Vimeo', 'graphql-api') }
					</ExternalLink>
				</p>
			</GuidePage>
			<GuidePage>
				<h1>{ __('Schema Configuration', 'graphql-api') }</h1>
				<p>Lorem ipsum</p>
			</GuidePage>
			<GuidePage>
				<h1>{ __('Options', 'graphql-api') }</h1>
				<p>Lorem ipsum</p>
			</GuidePage>
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
