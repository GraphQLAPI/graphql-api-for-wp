/**
 * External dependencies
 */
import { without, map, intersection } from 'lodash';

/**
 * WordPress dependencies
 */
import { compose, withInstanceId } from '@wordpress/compose';
import { CheckboxControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import MultiSelectControlGroupChecklist from './checklist';

function MultiSelectControlGroup( {
	instanceId,
	group,
	items,
	selectedItems,
	setAttributes,
	attributeName,
} ) {
	const checkedBlockNames = intersection(
		map( items, 'value' ),
		selectedItems
	);
	// console.log('group', group, items, map( items, 'value' ), selectedItems, checkedBlockNames);
	const toggleVisible = ( blockName, nextIsChecked ) => {
		setAttributes( {
			[ attributeName ]: nextIsChecked ? [...selectedItems, blockName] : without(selectedItems, blockName)
		} );
	};
	const toggleAllVisible = ( nextIsChecked ) => {
		const itemValues = map( items, 'value' );
		setAttributes( {
			[ attributeName ]: nextIsChecked ? [...selectedItems, ...itemValues] : without(selectedItems, ...itemValues)
		} );
	};

	const titleId =
		'edit-post-manage-blocks-modal__category-title-' + instanceId;

	const isAllChecked = checkedBlockNames.length === items.length;

	let ariaChecked;
	if ( isAllChecked ) {
		ariaChecked = 'true';
	} else if ( checkedBlockNames.length > 0 ) {
		ariaChecked = 'mixed';
	} else {
		ariaChecked = 'false';
	}

	return (
		<div
			role="group"
			aria-labelledby={ titleId }
			className="edit-post-manage-blocks-modal__category"
		>
			<CheckboxControl
				checked={ isAllChecked }
				onChange={ toggleAllVisible }
				className="edit-post-manage-blocks-modal__category-title"
				aria-checked={ ariaChecked }
				label={ <span id={ titleId }>{ group }</span> }
			/>
			<MultiSelectControlGroupChecklist
				items={ items }
				value={ checkedBlockNames }
				onItemChange={ toggleVisible }
			/>
		</div>
	);
}

export default compose( [
	withInstanceId,
] )( MultiSelectControlGroup );
