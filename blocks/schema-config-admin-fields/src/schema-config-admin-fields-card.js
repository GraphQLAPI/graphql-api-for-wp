/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody, RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	InfoTooltip,
	getEditableOnFocusComponentClass,
	SETTINGS_VALUE_LABEL,
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_ENABLED,
	ATTRIBUTE_VALUE_DISABLED,
} from '@graphqlapi/components';

const SchemaConfigAdminFieldsCard = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes: {
			enabledConst,
		},
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: __('Add "admin" fields to the schema', 'graphql-api'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not add admin fields', 'graphql-api'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Schema Admin Fields', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__admin_schema` }>
						<em>{ __('Add admin fields to the schema?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Add "admin" fields to the GraphQL schema (such as "Root.postsForAdmin", "User.roles", and others), which expose private data', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
									<span>🟡 { __('Default', 'graphql-api') }</span>
								}
								{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
									<span>✅ { __('Add "admin" fields', 'graphql-api') }</span>
								}
								{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
									<span>❌ { __('Do not add admin fields', 'graphql-api') }</span>
								}
							</>
						) }
						{ isSelected &&
							<RadioControl
								{ ...props }
								options={ options }
								selected={ enabledConst }
								onChange={ newValue => (
									setAttributes( {
										enabledConst: newValue
									} )
								)}
							/>
						}
					</div>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigAdminFieldsCard;