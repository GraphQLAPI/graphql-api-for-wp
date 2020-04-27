import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { getBlockTypes } from '@wordpress/blocks';
import BLOCK_NAME from './block-name.js';
import { SchemaMode } from '../../../packages/components/src';

/**
 * Category containing all Access Control blocks, as defined in \Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory::ACCESS_CONTROL_BLOCK_CATEGORY
 */
const ACCESS_CONTROL_BLOCK_CATEGORY = 'graphql-api-access-control';

const AccessControl = ( props ) => {
	const { className, enableIndividualControlForSchemaMode } = props;
	/**
	 * Only allow blocks under the "Access Control" category, except for this self block
	 */
	const allowedBlocks = getBlockTypes().filter(
		blockType => blockType.category == ACCESS_CONTROL_BLOCK_CATEGORY && blockType.name != BLOCK_NAME
	).map(blockType => blockType.name)
	/**
	 * Add component SchemaMode only if option "individual schema mode" is enabled
	 */
	return (
		<>
			{ enableIndividualControlForSchemaMode &&
				<div className={ className+'__schema_mode' }>
					<SchemaMode
						{ ...props }
						attributeName="schemaMode"
						addDefault={ true }
					/>
				</div>
			}
			<div className={ className+'__who' }>
				<InnerBlocks
					allowedBlocks={ allowedBlocks }
				/>
			</div>
		</>
	);
}

export default AccessControl;
