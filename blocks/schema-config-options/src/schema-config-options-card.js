/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody, RadioControl, Notice } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	SchemaModeControl,
	LinkableInfoTooltip,
	getEditableOnFocusComponentClass,
	SETTINGS_VALUE_LABEL,
} from '@graphqlapi/components';
import {
	ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT,
	ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
	ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
} from './namespacing-values';

const SchemaConfigOptionsCard = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes: {
			useNamespacing,
		},
		isPublicPrivateSchemaEnabled = true,
		isSchemaNamespacingEnabled = true,
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT,
		},
		{
			label: __('Use namespacing', 'graphql-api'),
			value: ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
		},
		{
			label: __('Do not use namespacing', 'graphql-api'),
			value: ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
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
					{ ! isPublicPrivateSchemaEnabled && ! isSchemaNamespacingEnabled && (
						<Notice status="warning" isDismissible={ false }>
							{ __('All options for the Schema Configuration are disabled', 'graphql-api') }
						</Notice>
					) }
					{ isPublicPrivateSchemaEnabled && (
						<div className={ `${ className }__schema_mode` }>
							<em>{ __('Public/Private Schema:', 'graphql-api') }</em>
							<LinkableInfoTooltip
								{ ...props }
								text={ __('Public: field/directives are always visible. Private: field/directives are hidden unless rules are satisfied.', 'graphql-api') }
								href="https://graphql-api.com/documentation/#schema-mode"
							/ >
							<SchemaModeControl
								{ ...props }
								attributeName="defaultSchemaMode"
							/>
						</div>
					) }
					{ isPublicPrivateSchemaEnabled && isSchemaNamespacingEnabled && (
						<hr />
					) }
					{ isSchemaNamespacingEnabled && (
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
									{ ( useNamespacing == ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT || !optionValues.includes(useNamespacing) ) &&
										<span>⭕️ { __('Default', 'graphql-api') }</span>
									}
									{ useNamespacing == ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED &&
										<span>✅ { __('Use namespacing', 'graphql-api') }</span>
									}
									{ useNamespacing == ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED &&
										<span>❌ { __('Do not use namespacing', 'graphql-api') }</span>
									}
								</>
							) }
							{ isSelected &&
								<RadioControl
									{ ...props }
									options={ options }
									selected={ useNamespacing }
									onChange={ newValue => (
										setAttributes( {
											useNamespacing: newValue
										} )
									)}
								/>
							}
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigOptionsCard;
