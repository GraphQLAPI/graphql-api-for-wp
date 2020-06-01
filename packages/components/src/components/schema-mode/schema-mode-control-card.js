/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { getEditableOnFocusComponentClass } from '../base-styles'
import { LinkableInfoTooltip } from '../linkable-info-tooltip';
import SchemaModeControl from './schema-mode-control';

const SchemaModeControlCard = ( props ) => {
	const { isSelected } = props;
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	const documentationLink = 'https://graphql-api.com/documentation/#schema-mode'
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Public/Private Schema', 'graphql-api') }
					<LinkableInfoTooltip
						{ ...props }
						text={ __('Default: use mode saved in settings. Public: field/directives are always visible. Private: field/directives are hidden unless rules are satisfied.', 'graphql-api') }
						href={ documentationLink }
					/ >
				</CardHeader>
				<CardBody>
					<SchemaModeControl
						{ ...props }
					/>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaModeControlCard;
