import { __, sprintf } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody } from '@wordpress/components';

const CacheControl = ( props ) => {
	const { className, setAttributes, isSelected, attributes: { cacheMaxAge } } = props;
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	return (
		<div className={ componentClassName }>
			<Card>
				<CardHeader isShady>{ __('Cache-Control header', 'graphql-api') }</CardHeader>
				<CardBody>
					{ isSelected && (
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
					) }
					{ !isSelected && (
						<span>
							{ !cacheMaxAge && (
								__('---', 'graphql-api')
							) }
							{ cacheMaxAge == '0' && (
								sprintf(
									__('%s seconds (%s)', 'graphql-api'),
									cacheMaxAge,
									'no-store'
								)
							) }
							{ !!cacheMaxAge && cacheMaxAge != '0' && (
								sprintf(
									__('%s seconds', 'graphql-api'),
									cacheMaxAge
								)
							) }
						</span>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default CacheControl;
