/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { Spinner } from '@wordpress/components';

/**
 * Display an error message if loading data failed
 */
const withSpinner = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { retrievedTypeFields } = props;
		if (!retrievedTypeFields) {
			return <Spinner />
		}

		return (
			<WrappedComponent
				{ ...props }
			/>
		);
	},
	'withSpinner'
);

export default withSpinner;
