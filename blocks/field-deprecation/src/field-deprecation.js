/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody, Tooltip, Icon, ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { LinkableInfoTooltip, getEditableOnFocusComponentClass } from '../../../packages/components/src';

const FieldDeprecation = ( props ) => {
	const {
		className,
		setAttributes,
		isSelected,
		attributes: { deprecationReason },
		disableHeader
	} = props;
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	const documentationLink = 'https://graphql-api.com/documentation/#cache-control'
	return (
		<div className={ componentClassName }>
			<Card>
				{ ! disableHeader && (
					<CardHeader isShady>
						{ __('Deprecation reason', 'graphql-api') }
						<LinkableInfoTooltip
							text={ __('Deprecated fields must not be queried anymore. The reason can indicate what replacement to use instead', 'graphql-api') }
							href={ documentationLink }
						/ >
					</CardHeader>
				) }
				<CardBody>
					{ isSelected && (
						<TextControl
							// label={ __('Deprecation Reason', 'graphql-api') }
							type="text"
							value={ deprecationReason }
							className={ className+'__reason' }
							onChange={ newValue =>
								setAttributes( {
									deprecationReason: newValue,
								} )
							}
						/>
					) }
					{ !isSelected && (
						<>
							{ !! deprecationReason && (
								<span>{ deprecationReason }</span>
							) }
							{ ! deprecationReason && (
								<em>{ __('(not set)', 'graphql-api') }</em>
							) }
						</>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default FieldDeprecation;
