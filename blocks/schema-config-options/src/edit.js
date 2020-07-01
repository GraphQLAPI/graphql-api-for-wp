/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
// import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import SchemaConfigOptionsCard from './schema-config-options-card';

const isPublicPrivateSchemaEnabled = window.graphqlApiSchemaConfigOptions ? window.graphqlApiSchemaConfigOptions.isPublicPrivateSchemaEnabled : true;
const isSchemaNamespacingEnabled = window.graphqlApiSchemaConfigOptions ? window.graphqlApiSchemaConfigOptions.isSchemaNamespacingEnabled : true;

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigOptionsCard
				isPublicPrivateSchemaEnabled={ isPublicPrivateSchemaEnabled }
				isSchemaNamespacingEnabled={ isSchemaNamespacingEnabled }
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
