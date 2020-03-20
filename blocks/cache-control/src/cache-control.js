import { __ } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody } from '@wordpress/components';

const CacheControl = ( props ) => {
	const { className, setAttributes, attributes: { cacheMaxAge } } = props;
	return (
		<Card { ...props }>
			<CardHeader isShady>{ __('Cache-Control header', 'graphql-api') }</CardHeader>
			<CardBody>
				<TextControl
					label={ __('Max-age (in seconds)', 'graphql-api') }
					// help={ __('The Cache-Control header will contain the minimum max-age value from all fields/directives involved in the request, or \'no-store\' if the max-age is 0', 'graphql-api') }
					type="text"
					value={ cacheMaxAge }
					className={ className+'__maxage' }
					onChange={ newValue =>
						setAttributes( {
							cacheMaxAge: newValue,
						} )
					}
				/>
			</CardBody>
		</Card>
	);
}

export default CacheControl;
