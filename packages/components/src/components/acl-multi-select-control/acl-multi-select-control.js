/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import MultiSelectControl from '../multi-select-control';

const AccessControlListMultiSelectControl = compose( [
	withState( { attributeName: 'accessControlLists' } ),
	withSelect( ( select ) => {
		const {
			getAccessControlLists,
			hasRetrievedAccessControlLists,
			getRetrievingAccessControlListsErrorMessage,
		} = select ( 'graphql-api/components' );
		/**
		 * Convert the accessControlLists array to this structure:
		 * [{group:"AccessControlLists",title:"accessControlList.title",value:"accessControlList.id"},...]
		 */
		const items = getAccessControlLists().map( accessControlList => (
			{
				group: __('Access Control Lists', 'graphql-api'),
				title: accessControlList.title,
				value: accessControlList.id,
			}
		) );
		return {
			items,
			hasRetrievedItems: hasRetrievedAccessControlLists(),
			errorMessage: getRetrievingAccessControlListsErrorMessage(),
		};
	} ),
	withSelect( ( select, { items, selectedItems } ) => {
		/**
		 * If the selectedItems contain IDs that do not exist in the entries
		 * (eg: because those objects where deleted) then add them again to the entries,
		 * so that the user can unselect them (otherwise, they stay in the state forever)
		 */
		const itemValues = items.map( item => item.value );
		const undefinedSelectedItemIDs = selectedItems.filter( selectedItemID => !itemValues.includes(selectedItemID) );
		return {
			items: items.concat(undefinedSelectedItemIDs.map( undefinedSelectedItemID => (
				{
					group: __('Undefined elements', 'graphql-api'),
					title: __(`(Undefined element with ID ${ undefinedSelectedItemID })`, 'graphql-api'),
					value: undefinedSelectedItemID,
				}
			) ) )
		};
	} ),
] )( MultiSelectControl );

export default AccessControlListMultiSelectControl;
