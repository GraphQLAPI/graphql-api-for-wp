/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import AccessControlListMultiSelectControl from './acl-multi-select-control';
import AccessControlListPrintout from './acl-printout';
import { getEditableOnFocusComponentClass } from '../base-styles'
import { withCard } from '../card'

const AccessControlListEditableOnFocusMultiSelectControlInner = ( props ) => {
	const { isSelected, className, attributes: { accessControlLists } } = props;
	return (
		<>
			{ isSelected &&
				<AccessControlListMultiSelectControl
					{ ...props }
					selectedItems={ accessControlLists }
					className={ className }
				/>
			}
			{ !isSelected && (
				<AccessControlListPrintout
					{ ...props }
					selectedItems={ accessControlLists }
					className={ className }
				/>
			) }
		</>
	);
}

const AccessControlListEditableOnFocusMultiSelectControl = ( props ) => {
	const { isSelected } = props;
	const className = 'graphql-api-access-control-list-select';
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ className }>
			<div className={ componentClassName }>
				<AccessControlListEditableOnFocusMultiSelectControlInner
					{ ...props }
					className={ className }
				/>
			</div>
		</div>
	);
}

export default compose( [
	withState( { header: __('Access Control Lists', 'graphql-api') } ),
	withCard(),
] )( AccessControlListEditableOnFocusMultiSelectControl );
