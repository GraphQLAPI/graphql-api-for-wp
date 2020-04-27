/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	SchemaModeControl,
	LinkableInfoTooltip,
	getEditableOnFocusComponentClass,
} from '../../../packages/components/src';

const SchemaConfigOptionsCard = ( props ) => {
	const { isSelected } = props;
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	const documentationLink = 'https://graphql-api.com/documentation/#schema-config-options'
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Options', 'graphql-api') }
					<LinkableInfoTooltip
						text={ __('Select the default behavior of the Schema', 'graphql-api') }
						href={ documentationLink }
					/ >
				</CardHeader>
				<CardBody>
					<strong>{ __('Default Schema Mode:', 'graphql-api') }</strong>
					<LinkableInfoTooltip
						text={ __('Public: field/directives are always visible. Private: field/directives are hidden unless rules are satisfied.', 'graphql-api') }
						href={ documentationLink }
					/ >
					<SchemaModeControl
						{ ...props }
						attributeName="defaultSchemaMode"
						addDefault={ false }
					/>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigOptionsCard;
