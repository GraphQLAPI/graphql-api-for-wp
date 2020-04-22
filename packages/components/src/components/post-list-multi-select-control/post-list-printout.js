/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { Card, CardHeader, CardBody, CheckboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { EMPTY_LABEL } from '../../default-configuration';
import withSpinner from '../loading/with-spinner';
import withErrorMessage from '../loading/with-error-message';

/**
 * Print the selected Access Control Lists.
 *
 * @param {Object} props
 */
const PostListPrintout = ( props ) => {
	const { items, selectedItems, emptyLabel, header } = props;
	const emptyLabelString = emptyLabel != undefined ? emptyLabel : EMPTY_LABEL;

	/**
	 * Create a dictionary, with ID as key, and title as the value
	 */
	let itemsDictionary = {};
	items.forEach(function(item) {
		itemsDictionary[ item.id ] = item.title;
	} );
	return (
		<Card { ...props }>
			<CardHeader isShady>{ header }</CardHeader>
			<CardBody>
				{ !! selectedItems.length && selectedItems.map( selectedItemID =>
					<>
						<CheckboxControl
							label={ itemsDictionary[selectedItemID] || __(`(Undefined element with ID ${ selectedItemID })`, 'graphql-api') }
							checked={ true }
							disabled={ true }
						/>
					</>
				) }
				{ !selectedItems.length && (
					emptyLabelString
				) }
			</CardBody>
		</Card>
	);
}

/**
 * Check if the selectedItems are empty, then do not show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerPostListPrintout = ( props ) => {
	const { selectedItems } = props;
	if ( !! selectedItems.length ) {
		return (
			<WithSpinnerPostListPrintout { ...props } />
		)
	}
	return (
		<PostListPrintout { ...props } />
	);
}

/**
 * Add a spinner when loading the typeFieldNames and typeFields is not empty
 */
const WithSpinnerPostListPrintout = compose( [
	withSpinner(),
	withErrorMessage(),
] )( PostListPrintout );

export default MaybeWithSpinnerPostListPrintout;
