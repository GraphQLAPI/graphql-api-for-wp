/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Guide, GuidePage } from '@wordpress/components';
import {
	welcomeGuideMarkdown,
	schemaConfigOptionsMarkdown,
} from '../../docs';

const EndpointGuide = ( props ) => {
	const [ pages, setPages ] = useState([]);
	const lang = 'es'
	const langSources = {
		'welcome-guide': welcomeGuideMarkdown,
		'schema-config-options': schemaConfigOptionsMarkdown,
	}
	useEffect(() => {
		const importPromises = Object.keys(langSources).map(
			fileName => import( /* webpackMode: "eager" */ `../../docs/${ lang }/${ fileName }.md` )
				.then(obj => obj.default)
				// .then( ( { default: _ } ) )
				.catch(err => langSources[ fileName ])
		)
		Promise.all(importPromises).then( values => {
			setPages( values )
		});
	}, []);

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
export default EndpointGuideButton;
