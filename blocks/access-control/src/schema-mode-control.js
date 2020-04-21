/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody, RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { DEFAULT_SCHEMA_MODE, PUBLIC_SCHEMA_MODE, PRIVATE_SCHEMA_MODE } from './schema-modes';
import { LinkableInfoTooltip } from '../../../packages/components/src';

const SchemaModeControl = ( props ) => {
	const { className, isSelected, setAttributes, attributes: { schemaMode } } = props;
	const options = [
		{
			label: __('Default', 'graphql-api'),
			value: DEFAULT_SCHEMA_MODE,
		},
		{
			label: __('Public', 'graphql-api'),
			value: PUBLIC_SCHEMA_MODE,
		},
		{
			label: __('Private', 'graphql-api'),
			value: PRIVATE_SCHEMA_MODE,
		},
	];
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	const documentationLink = 'https://graphql-api.com/documentation/#schema-mode'
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Schema mode:', 'graphql-api') }
					<LinkableInfoTooltip
						text={ __('Default: use mode saved in settings. Public: field/directives are always visible. Private: field/directives are hidden unless rules are satisfied.', 'graphql-api') }
						href={ documentationLink }
					/ >
				</CardHeader>
				<CardBody>
					{ isSelected &&
						<RadioControl
							{ ...props }
							options={ options }
							selected={ schemaMode }
							onChange={ schemaMode => (
								setAttributes( {
									schemaMode
								} )
							)}
						/>
					}
					{ !isSelected && (
						<div className={ className+'__read'}>
							{ (schemaMode == DEFAULT_SCHEMA_MODE) &&
								<span>🌝 { __('Default', 'graphql-api') }</span>
							}
							{ (schemaMode == PUBLIC_SCHEMA_MODE) &&
								<span>🌕 { __('Public', 'graphql-api') }</span>
							}
							{ (schemaMode == PRIVATE_SCHEMA_MODE) &&
								<span>🌑 { __('Private', 'graphql-api') }</span>
							}
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaModeControl;
