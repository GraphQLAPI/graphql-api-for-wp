/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './store';
import { withErrorMessage } from '../../../packages/components/src';
import { withSpinner } from '../../../packages/components/src';
import { SelectCard } from '../../../packages/components/src';

const WithSpinnerUserCapabilities = compose( [
	withSpinner(),
	withErrorMessage(),
] )( SelectCard );

/**
 * Check if the capabilities have not been fetched yet, and editing the component (isSelected => true), then show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerUserCapabilities = ( props ) => {
	const { isSelected, capabilities } = props;
	if ( !capabilities?.length && isSelected ) {
		return (
			<WithSpinnerUserCapabilities { ...props } />
		)
	}
	return (
		<SelectCard { ...props } />
	);
}

export default compose( [
	withState( {
		label: __('Users with any of these capabilities:', 'graphql-api'),
	} ),
	withSelect( ( select ) => {
		const {
			getCapabilities,
			hasRetrievedCapabilities,
			getRetrievingCapabilitiesErrorMessage,
		} = select ( 'graphql-api/access-control-user-capabilities' );
		const capabilities = getCapabilities();
		return {
			capabilities,
			items: capabilities,
			hasRetrievedItems: hasRetrievedCapabilities(),
			errorMessage: getRetrievingCapabilitiesErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerUserCapabilities );
