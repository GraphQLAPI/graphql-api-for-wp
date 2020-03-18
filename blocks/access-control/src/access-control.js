import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { getBlockTypes } from '@wordpress/blocks';

/**
 * Category containing all Access Control blocks, as defined in \Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory::ACCESS_CONTROL_BLOCK_CATEGORY
 */
const ACCESS_CONTROL_BLOCK_CATEGORY = 'graphql-api-access-control';

const AccessControl = ( props ) => {
	const { className } = props;
	/**
	 * Only allow blocks under the "Access Control" category, except for this self block
	 */
	const allowedBlocks = getBlockTypes().filter(
		blockType => blockType.category == ACCESS_CONTROL_BLOCK_CATEGORY && blockType.name != 'graphql-api/access-control'
	).map(blockType => blockType.name)
	return (
		<div className={ className+'__who' }>
			<InnerBlocks
				allowedBlocks={ allowedBlocks }
			/>
		</div>
	);
}

export default AccessControl;
