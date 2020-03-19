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

const UserCapabilities = ( props ) => {
	const { capabilities, className, setAttributes, isSelected, attributes: { value } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const options = capabilities.map(capability => ( { value: capability, label: capability } ) )
	const selectedValues = value.map(val => ( { value: val, label: val } ) )
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Users with any of these capabilities:', 'graphql-api') }
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
					{ !isSelected && !!value.length && (
						<div className={ className+'__label-group'}>
							{ value.map( val =>
								<div className={ className+'__label-item'}>
									{ val }
								</div>
							) }
						</div>
					) }
					{ !isSelected && !value.length && (
						__('---', 'graphql-api')
					) }
				</CardBody>
			</Card>
		</div>
	);
}

const WithSpinnerUserCapabilities = compose( [
	withSpinner(),
	withErrorMessage(),
] )( UserCapabilities );

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
		<UserCapabilities { ...props } />
	);
}

export default compose( [
	withSelect( ( select ) => {
		const {
			getCapabilities,
			hasRetrievedCapabilities,
			getRetrievingCapabilitiesErrorMessage,
		} = select ( 'graphql-api/access-control-user-capabilities' );
		return {
			capabilities: getCapabilities(),
			hasRetrievedItems: hasRetrievedCapabilities(),
			errorMessage: getRetrievingCapabilitiesErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerUserCapabilities );
