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
import BlockTypesChecklist from './checklist';

function BlockManagerCategory( {
	instanceId,
	group,
	blockTypes,
	selectedFields,
	setAttributes,
} ) {
	const checkedBlockNames = intersection(
		map( blockTypes, 'value' ),
		selectedFields
	);
	// console.log('group', group, blockTypes, map( blockTypes, 'value' ), selectedFields, checkedBlockNames);
	const toggleVisible = ( blockName, nextIsChecked ) => {
		setAttributes( {
			selectedFields: nextIsChecked ? [...selectedFields, blockName] : without(selectedFields, blockName)
		} );
	};
	const toggleAllVisible = ( nextIsChecked ) => {
		const blockNames = map( blockTypes, 'value' );
		setAttributes( {
			selectedFields: nextIsChecked ? [...selectedFields, ...blockNames] : without(selectedFields, ...blockNames)
		} );
	};

	const titleId =
		'edit-post-manage-blocks-modal__category-title-' + instanceId;

	const isAllChecked = checkedBlockNames.length === blockTypes.length;

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
			<BlockTypesChecklist
				blockTypes={ blockTypes }
				value={ checkedBlockNames }
				onItemChange={ toggleVisible }
			/>
		</div>
	);
}

export default compose( [
	withInstanceId,
] )( BlockManagerCategory );
