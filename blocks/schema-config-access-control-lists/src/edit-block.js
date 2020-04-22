/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
// import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import { AccessControlListEditableOnFocusMultiSelectControl } from '../../../packages/components/src';

const EditBlock = ( props ) => {
	const { isSelected, className } = props;
	return (
		<div class={ className }>
			<AccessControlListEditableOnFocusMultiSelectControl
				accessControlComponentClassName={ `nested-component editable-on-focus is-selected-${ isSelected }` }
				{ ...props}
			/>
		</div>
	)
}

export default EditBlock;
