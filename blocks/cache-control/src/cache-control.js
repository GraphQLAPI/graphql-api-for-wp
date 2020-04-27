/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody, Tooltip, Icon, ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { LinkableInfoTooltip, getEditableOnFocusComponentClass } from '../../../packages/components/src';

const CacheControl = ( props ) => {
	const { className, setAttributes, isSelected, attributes: { cacheControlMaxAge } } = props;
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	const documentationLink = 'https://graphql-api.com/documentation/#cache-control'
	return (
		<div className={ componentClassName }>
			<Card>
				<CardHeader isShady>
					{ __('Cache-Control max-age', 'graphql-api') }
					<LinkableInfoTooltip
						{ ...props }
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
									cacheControlMaxAge: Number(newValue),
								} )
							}
						/>
					) }
					{ !isSelected && (
						<span>
							{ cacheControlMaxAge == null && (
								<em>{ __('(not set)', 'graphql-api') }</em>
							) }
							{ cacheControlMaxAge != null && (
								<>
									{ cacheControlMaxAge == 0 && (
										sprintf(
											__('%s seconds (%s)', 'graphql-api'),
											cacheControlMaxAge,
											'no-store'
										)
									) }
									{ cacheControlMaxAge != 0 && (
										sprintf(
											__('%s seconds', 'graphql-api'),
											cacheControlMaxAge
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
