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

const WithSpinnerUserRoles = compose( [
	withSpinner(),
	withErrorMessage(),
] )( SelectCard );

/**
 * Check if the roles have not been fetched yet, and editing the component (isSelected => true), then show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerUserRoles = ( props ) => {
	const { isSelected, roles } = props;
	if ( !roles?.length && isSelected ) {
		return (
			<WithSpinnerUserRoles { ...props } />
		)
	}
	return (
		<SelectCard { ...props } />
	);
}

export default compose( [
	withState( {
		label: __('Users with any of these roles:', 'graphql-api'),
	} ),
	withSelect( ( select ) => {
		const {
			getRoles,
			hasRetrievedRoles,
			getRetrievingRolesErrorMessage,
		} = select ( 'graphql-api/access-control-user-roles' );
		const roles = getRoles();
		return {
			roles,
			items: roles,
			hasRetrievedItems: hasRetrievedRoles(),
			errorMessage: getRetrievingRolesErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerUserRoles );
