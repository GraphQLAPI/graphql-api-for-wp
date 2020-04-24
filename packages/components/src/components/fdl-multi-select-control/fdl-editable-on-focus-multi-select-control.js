/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import FieldDeprecationListMultiSelectControl from './fdl-multi-select-control';
import FieldDeprecationListPrintout from './fdl-printout';
import { getEditableOnFocusComponentClass } from '../base-styles'

const FieldDeprecationListEditableOnFocusMultiSelectControl = ( props ) => {
	const { isSelected, attributes: { fieldDeprecationLists } } = props;
	const className = 'graphql-api-field-deprecation-list-select';
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ className }>
			<div className={ componentClassName }>
				{ isSelected &&
					<FieldDeprecationListMultiSelectControl
						{ ...props }
						selectedItems={ fieldDeprecationLists }
						className={ className }
					/>
				}
				{ !isSelected && (
					<FieldDeprecationListPrintout
						{ ...props }
						selectedItems={ fieldDeprecationLists }
						className={ className }
					/>
				) }
			</div>
		</div>
	);
}

export default FieldDeprecationListEditableOnFocusMultiSelectControl;
