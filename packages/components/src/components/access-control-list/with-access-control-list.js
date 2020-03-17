/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import FieldDirectiveTabPanel from './field-directive-tab-panel';
import './style.scss';

const getElementList = ( elements, className ) => {
	return elements.length ? (
		<ul className={ className+'__item_data__list' }>
			{ elements.map(element => <li><code>{ element }</code></li>)}
		</ul>
	) : (
		__('None selected', 'graphql-api')
	);
}

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
									<>
										<p>
											<u>{ __('Fields:', 'graphql-api') }</u> { getElementList( typeFields, className ) }
										</p>
										<p>
											<u>{ __('Directives:', 'graphql-api') }</u> { getElementList( directives, className ) }
										</p>
									</>
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
