/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody, ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	SchemaModeControl,
	LinkableInfoTooltip,
	getEditableOnFocusComponentClass,
} from '../../../packages/components/src';

const SchemaConfigOptionsCard = ( props ) => {
	const { isSelected, className, setAttributes, attributes: { useNamespacing } } = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Options', 'graphql-api') }
					<LinkableInfoTooltip
						{ ...props }
						text={ __('Select the default behavior of the Schema', 'graphql-api') }
						href="https://graphql-api.com/documentation/#schema-config-options"
					/ >
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__schema_mode` }>
						<em>{ __('Default Schema Mode:', 'graphql-api') }</em>
						<LinkableInfoTooltip
							{ ...props }
							text={ __('Public: field/directives are always visible. Private: field/directives are hidden unless rules are satisfied.', 'graphql-api') }
							href="https://graphql-api.com/documentation/#schema-mode"
						/ >
						<SchemaModeControl
							{ ...props }
							attributeName="defaultSchemaMode"
							addDefault={ false }
						/>
					</div>
					<hr />
					<div className={ `${ className }__namespacing` }>
						<em>{ __('Namespace Types and Interfaces?', 'graphql-api') }</em>
						<LinkableInfoTooltip
							{ ...props }
							text={ __('Prepend types and interfaces using the PHP package\'s owner and name', 'graphql-api') }
							href="https://graphql-api.com/documentation/#namespacing"
						/ >
						{ !isSelected && (
							<>
								<br />
								{ useNamespacing ? `✅ ${ __('Enabled', 'graphql-api') }` : `❌ ${ __('Disabled', 'graphql-api') }` }
							</>
						) }
						{ isSelected &&
							<ToggleControl
								{ ...props }
								label={ useNamespacing ? __('Namespacing enabled', 'graphql-api') : __('Namespacing disabled', 'graphql-api') }
								checked={ useNamespacing }
								onChange={ newValue => setAttributes( {
									useNamespacing: newValue,
								} ) }
							/>
						}
					</div>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigOptionsCard;
