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
	const { className, setAttributes, isSelected, attributes: { cacheMaxAge } } = props;
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	// We store the value as string instead of as integer, because we can't define 'integer|null' for the attribute, and the empty and '0' values are different
	const cacheMaxAgeInt = parseInt(cacheMaxAge);
	const documentationLink = 'https://graphql-api.com/documentation/#cache-control'
	return (
		<div className={ componentClassName }>
			<Card>
				<CardHeader isShady>
					{ __('Cache-Control header', 'graphql-api') }
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
							{ !!cacheMaxAge && (
								<>
									{ cacheMaxAgeInt === 0 && (
										sprintf(
											__('%s seconds (%s)', 'graphql-api'),
											cacheMaxAgeInt,
											'no-store'
										)
									) }
									{ cacheMaxAgeInt !== 0 && (
										sprintf(
											__('%s seconds', 'graphql-api'),
											cacheMaxAgeInt
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
