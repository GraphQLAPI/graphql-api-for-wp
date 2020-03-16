/**
 * External dependencies
 */
import { partial } from 'lodash';

/**
 * WordPress dependencies
 */
import { CheckboxControl } from '@wordpress/components';

function BlockTypesChecklist( { blockTypes, value, onItemChange } ) {
	return (
		<ul className="edit-post-manage-blocks-modal__checklist">
			{ blockTypes.map( ( blockType ) => (
				<li
					key={ blockType.value }
					className="edit-post-manage-blocks-modal__checklist-item"
				>
					<CheckboxControl
						label={ blockType.title }
						checked={ value.includes( blockType.value ) }
						onChange={ partial( onItemChange, blockType.value ) }
					/>
				</li>
			) ) }
		</ul>
	);
}

export default BlockTypesChecklist;
