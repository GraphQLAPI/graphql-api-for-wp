import { __ } from '@wordpress/i18n';
import { TabPanel } from '@wordpress/components';
import FieldMultiSelectControl from './field-multi-select-control';
import DirectiveMultiSelectControl from './directive-multi-select-control';

const FieldDirectiveTabPanel = ( props ) => {
	const { className, setAttributes, typeFields, directives } = props;
	return (
		<TabPanel
			className={ className + '__tab_panel' }
			activeClass="active-tab"
			tabs={ [
				{
					name: 'tabFields',
					title: __('Fields, by type', 'graphql-api'),
					className: 'tab tab-fields',
				},
				{
					name: 'tabDirectives',
					title: __('(Non-system) Directives', 'graphql-api'),
					className: 'tab tab-directives',
				},
			] }
		>
			{
				( tab ) => tab.name == 'tabFields' ?
					<FieldMultiSelectControl
						selectedItems={ typeFields }
						setAttributes={ setAttributes }
					/> :
					<DirectiveMultiSelectControl
						selectedItems={ directives }
						setAttributes={ setAttributes }
					/>
			}
		</TabPanel>
	);
}

export default FieldDirectiveTabPanel;
