/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody, Tooltip, Icon, ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { LinkableInfoTooltip } from '../../../packages/components/src';

const CacheControl = ( props ) => {
	const { className, setAttributes, isSelected, attributes: { cacheControlMaxAge } } = props;
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	// We store the value as string instead of as integer, because we can't define 'integer|null' for the attribute, and the empty and '0' values are different
	const cacheControlMaxAgeInt = parseInt(cacheControlMaxAge);
	const documentationLink = 'https://graphql-api.com/documentation/#cache-control'
	return (
		<div className={ componentClassName }>
			<Card>
				<CardHeader isShady>
					{ __('Cache-Control max-age', 'graphql-api') }
					<LinkableInfoTooltip
						text={ __('The Cache-Control header will contain the minimum max-age value from all fields/directives involved in the request, or "no-store" if the max-age is 0', 'graphql-api') }
						href={ documentationLink }
					/ >
				</CardHeader>
				<CardBody>
					{ isSelected && (
						<TextControl
							label={ __('Max-age (in seconds)', 'graphql-api') }
							type="text"
							value={ cacheControlMaxAge }
							className={ className+'__maxage' }
							onChange={ newValue =>
								setAttributes( {
									cacheControlMaxAge: newValue,
								} )
							}
						/>
					) }
					{ !isSelected && (
						<span>
							{ !cacheControlMaxAge && (
								__('---', 'graphql-api')
							) }
							{ !!cacheControlMaxAge && (
								<>
									{ cacheControlMaxAgeInt === 0 && (
										sprintf(
											__('%s seconds (%s)', 'graphql-api'),
											cacheControlMaxAgeInt,
											'no-store'
										)
									) }
									{ cacheControlMaxAgeInt !== 0 && (
										sprintf(
											__('%s seconds', 'graphql-api'),
											cacheControlMaxAgeInt
										)
									) }
								</>
							) }
						</span>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default CacheControl;
