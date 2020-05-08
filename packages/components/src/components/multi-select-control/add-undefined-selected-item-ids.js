/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { __, sprintf } from '@wordpress/i18n';

const AddUndefinedSelectedItemIDs = withSelect(
	( select, { items, selectedItems } ) => {
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
					title: sprintf(
						__('(Undefined or unpublished item with ID %s)', 'graphql-api'),
						undefinedSelectedItemID
					),
					value: undefinedSelectedItemID,
				}
			) ) )
		};
	}
)

export default AddUndefinedSelectedItemIDs;
