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
	category,
	blockTypes,
	selectedFields,
	setAttributes,
} ) {
	const checkedBlockNames = intersection(
		map( blockTypes, 'name' ),
		selectedFields
	);
	const toggleVisible = ( blockName, nextIsChecked ) => {
		if ( nextIsChecked ) {
			setAttributes( { selectedFields: [...selectedFields, blockName] } );
		} else {
			setAttributes( { selectedFields: without(selectedFields, blockName) } );
		}
	};
	const toggleAllVisible = ( nextIsChecked ) => {
		const blockNames = map( blockTypes, 'name' );
		if ( nextIsChecked ) {
			setAttributes( { selectedFields: [...selectedFields, ...blockNames] } );
		} else {
			setAttributes( { selectedFields: without(selectedFields, ...blockNames) } );
		}
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
				label={ <span id={ titleId }>{ category.title }</span> }
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
