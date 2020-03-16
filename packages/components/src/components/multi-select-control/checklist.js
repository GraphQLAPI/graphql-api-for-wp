/**
 * External dependencies
 */
import { partial } from 'lodash';

/**
 * WordPress dependencies
 */
import { CheckboxControl } from '@wordpress/components';

function BlockTypesChecklist( { items, value, onItemChange } ) {
	return (
		<ul className="edit-post-manage-blocks-modal__checklist">
			{ items.map( ( item ) => (
				<li
					key={ item.value }
					className="edit-post-manage-blocks-modal__checklist-item"
				>
					<CheckboxControl
						label={ item.title }
						checked={ value.includes( item.value ) }
						onChange={ partial( onItemChange, item.value ) }
					/>
				</li>
			) ) }
		</ul>
	);
}

export default BlockTypesChecklist;
