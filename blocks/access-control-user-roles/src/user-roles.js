/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * External dependencies
 */
import Select from 'react-select';

/**
 * Internal dependencies
 */
import './store';
import { withErrorMessage } from '../../../packages/components/src';
import { withSpinner } from '../../../packages/components/src';

const UserRoles = ( props ) => {
	const { roles, className, setAttributes, isSelected, attributes: { value } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const options = roles.map(role => ( { value: role, label: role } ) )
	const selectedValues = value.map(val => ( { value: val, label: val } ) )
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Users with any of these roles:', 'graphql-api') }
				</CardHeader>
				<CardBody>
					{ isSelected &&
						<Select
							defaultValue={ selectedValues }
							options={ options }
							isMulti
							closeMenuOnSelect={ false }
							onChange={ selectedOptions =>
								// Extract the attribute "value"
								setAttributes( {
									value: selectedOptions.map(option => option.value)
								} )
							}
						/>
					}
					{ !isSelected && (
						<div className={ className+'__label-group'}>
							{ value.map( val =>
								<div className={ className+'__label-item'}>
									{ val }
								</div>
							) }
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

const WithSpinnerUserRoles = compose( [
	withSpinner(),
	withErrorMessage(),
] )( UserRoles );

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
		<UserRoles { ...props } />
	);
}

export default compose( [
	withSelect( ( select ) => {
		const {
			getRoles,
			hasRetrievedRoles,
			getRetrievingRolesErrorMessage,
		} = select ( 'graphql-api/access-control-user-roles' );
		return {
			roles: getRoles(),
			hasRetrievedItems: hasRetrievedRoles(),
			errorMessage: getRetrievingRolesErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerUserRoles );
