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
	InfoTooltip,
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
							<InfoTooltip
								{ ...props }
								text={ __('Default: use value from Settings. Public: fields/directives are always visible. Private: fields/directives are hidden unless rules are satisfied.', 'graphql-api') }
							/>
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
							<InfoTooltip
								{ ...props }
								text={ __('Add a unique namespace to types and interfaces to avoid conflicts', 'graphql-api') }
							/>
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
