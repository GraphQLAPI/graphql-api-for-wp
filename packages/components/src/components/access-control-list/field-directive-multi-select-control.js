import { __ } from '@wordpress/i18n';
import FieldMultiSelectControl from './field-multi-select-control';

const FieldDirectiveMultiSelectControl = ( props ) => {
	const { className, setAttributes, selectedFields, accessControlGroup } = props;
	// Temporary code for testing!
	setAttributes( { accessControlGroup: 'disabled'} )
	return (
		<div className={ className+'__controls' }>
			<p>{ __('Fields:', 'graphql-api') }</p>
			<div className="edit-post-manage-blocks-modal">
				<FieldMultiSelectControl
					selectedFields={ selectedFields }
					setAttributes={ setAttributes }
				/>
			</div>
			<p>{ __('Directives:', 'graphql-api') }</p>
		</div>
	);
}

export default FieldDirectiveMultiSelectControl;
