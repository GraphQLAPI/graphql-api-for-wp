import { __ } from '@wordpress/i18n';
import FieldMultiSelectControl from './field-multi-select-control';

const FieldDirectiveMultiSelectControl = ( props ) => {
	const { className, setAttributes, selectedFields } = props;
	return (
		<div className={ className+'__controls' }>
			<div className="edit-post-manage-blocks-modal">
				<em>{ __('Fields (by type):', 'graphql-api') }</em>
				<FieldMultiSelectControl
					selectedFields={ selectedFields }
					setAttributes={ setAttributes }
				/>
			</div>
			<div className="edit-post-manage-blocks-modal">
				<em>{ __('Directives:', 'graphql-api') }</em>
			</div>
		</div>
	);
}

export default FieldDirectiveMultiSelectControl;
