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
] )( MultiSelectControl );

export default AccessControlListMultiSelectControl;
