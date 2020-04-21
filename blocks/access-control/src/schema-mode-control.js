import { __ } from '@wordpress/i18n';
import { Card, CardBody, RadioControl } from '@wordpress/components';
import { DEFAULT_SCHEMA_MODE, PUBLIC_SCHEMA_MODE, PRIVATE_SCHEMA_MODE } from './schema-modes';

const SchemaModeControl = ( props ) => {
	const { className, isSelected, setAttributes, attributes: { schemaMode } } = props;
	const options = [
		{
			label: __('Default mode', 'graphql-api'),
			value: DEFAULT_SCHEMA_MODE,
		},
		{
			label: __('Public mode', 'graphql-api'),
			value: PUBLIC_SCHEMA_MODE,
		},
		{
			label: __('Private mode', 'graphql-api'),
			value: PRIVATE_SCHEMA_MODE,
		},
	];
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardBody>
					{ isSelected &&
						<RadioControl
							{ ...props }
							// label={ __('User is...', 'graphql-api') }
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
								<span>🌝 { __('Default mode', 'graphql-api') }</span>
							}
							{ (schemaMode == PUBLIC_SCHEMA_MODE) &&
								<span>🌕 { __('Public mode', 'graphql-api') }</span>
							}
							{ (schemaMode == PRIVATE_SCHEMA_MODE) &&
								<span>🌑 { __('Private mode', 'graphql-api') }</span>
							}
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaModeControl;
