/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import FieldDirectiveMultiSelectControl from './field-directive-multi-select-control';
import './style.scss';

/**
 * Display an error message if loading data failed
 */
const withAccessControlList = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { className, setAttributes, attributes: { selectedFields } } = props;
		return (
			<div className={ className }>
				<div className={ className+'__items' }>
					<div className={ className+'__item' }>
						<div className={ className+'__item_data' }>
							<div className={ className+'__item_data_for' }>
								<p className={ className+'__item_data__title' }><strong>{ __('Define access for:', 'graphql-api') }</strong></p>
								<FieldDirectiveMultiSelectControl
									selectedFields={ selectedFields }
									setAttributes={ setAttributes }
									className={ className }
								/>
							</div>
							<div className={ className+'__item_data_who' }>
								<p className={ className+'__item_data__title' }><strong>{ __('Who can access:', 'graphql-api') }</strong></p>
								<WrappedComponent
									{ ...props }
								/>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	},
	'withAccessControlList'
);

export default withAccessControlList;
