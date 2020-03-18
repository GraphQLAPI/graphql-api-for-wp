/**
 * Internal dependencies
 */
import { createHigherOrderComponent, compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import FieldDirectiveTabPanel from './field-directive-tab-panel';
import FieldDirectivePrintout from './field-directive-printout';

/**
 * Display an error message if loading data failed
 */
const withAccessControlList = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { setAttributes, isSelected, attributes: { typeFields, directives } } = props;
		const className = 'graphql-api-access-control-list';
		return (
			<div className={ className }>
				<div className={ className+'__items' }>
					<div className={ className+'__item' }>
						<div className={ className+'__item_data' }>
							<div className={ className+'__item_data_for' }>
								<div className={ className+'__item_data__title' }>
									<strong>{ __('Define access for:', 'graphql-api') }</strong>
								</div>
								{ isSelected &&
									<FieldDirectiveTabPanel
										typeFields={ typeFields }
										directives={ directives }
										setAttributes={ setAttributes }
										className={ className }
									/>
								}
								{ !isSelected && (
									<FieldDirectivePrintout
										typeFields={ typeFields }
										directives={ directives }
										className={ className }
									/>
								) }
							</div>
							<div className={ className+'__item_data_who' }>
								<div className={ className+'__item_data__title' }>
									<strong>{ __('Who can access:', 'graphql-api') }</strong>
								</div>
								<div className={ className+'__item_data__who' }>
									<WrappedComponent
										className={ className }
										{ ...props }
									/>
								</div>
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
