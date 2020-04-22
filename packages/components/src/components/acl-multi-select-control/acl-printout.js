/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Card, CardHeader, CardBody, CheckboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import withSpinner from '../loading/with-spinner';
import withErrorMessage from '../loading/with-error-message';
import { EMPTY_LABEL } from '../../default-configuration';

/**
 * Print the selected Access Control Lists.
 *
 * @param {Object} props
 */
const AccessControlListPrintout = ( props ) => {
	const { accessControlListEntries, accessControlLists, emptyLabel } = props;
	const emptyLabelString = emptyLabel != undefined ? emptyLabel : EMPTY_LABEL;

	/**
	 * Create a dictionary, with ID as key, and name as the value
	 */
	let accessControlListsDictionary = {};
	accessControlListEntries.forEach(function(accessControlList) {
		accessControlListsDictionary[ accessControlList.id ] = accessControlList.name;
	} );
	return (
		<Card { ...props }>
			<CardHeader isShady>{ __('Access Control Lists:', 'graphql-api') }</CardHeader>
			<CardBody>
				{ !! accessControlLists.length && accessControlLists.map( selectedAccessControlListID =>
					<>
						<CheckboxControl
							label={ `${ accessControlListsDictionary[selectedAccessControlListID] }` }
							checked={ true }
							disabled={ true }
						/>
					</>
				) }
				{ !accessControlLists.length && (
					emptyLabelString
				) }
			</CardBody>
		</Card>
	);
}

/**
 * Check if the accessControlLists are empty, then do not show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerAccessControlListPrintout = ( props ) => {
	const { accessControlLists } = props;
	if ( !! accessControlLists.length ) {
		return (
			<WithSpinnerAccessControlListPrintout { ...props } />
		)
	}
	return (
		<AccessControlListPrintout { ...props } />
	);
}

/**
 * Add a spinner when loading the typeFieldNames and typeFields is not empty
 */
const WithSpinnerAccessControlListPrintout = compose( [
	withSpinner(),
	withErrorMessage(),
] )( AccessControlListPrintout );

export default compose( [
	withSelect( ( select ) => {
		const {
			getAccessControlLists,
			hasRetrievedAccessControlLists,
			getRetrievingAccessControlListsErrorMessage,
		} = select ( 'graphql-api/components' );
		return {
			accessControlListEntries: getAccessControlLists(),
			hasRetrievedItems: hasRetrievedAccessControlLists(),
			errorMessage: getRetrievingAccessControlListsErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerAccessControlListPrintout );
