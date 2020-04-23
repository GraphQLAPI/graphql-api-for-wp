/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Display an error message if loading data failed
 */
const withErrorMessage = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { hasRetrievedItems, errorMessage } = props;
		if (hasRetrievedItems && errorMessage) {
			return <div className="multi-select-control__error_message">
				{ errorMessage }
			</div>
		}

		return (
			<WrappedComponent
				{ ...props }
			/>
		);
	},
	'withErrorMessage'
);

export default withErrorMessage;
