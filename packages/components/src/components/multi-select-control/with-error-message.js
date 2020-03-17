/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Display an error message if loading data failed
 */
const withErrorMessage = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { retrievedTypeFields, retrievingTypeFieldsErrorMessage } = props;
		if (retrievedTypeFields && retrievingTypeFieldsErrorMessage) {
			return <p className="edit-post-manage-blocks-modal__error_message">
				{ retrievingTypeFieldsErrorMessage }
			</p>
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
