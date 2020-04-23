/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { getEditableOnFocusComponentClass } from '../base-styles'

/**
 * External dependencies
 */
import Select from 'react-select';

/**
 * Internal dependencies
 */
import './style.scss';


const SelectCard = ( props ) => {
	const { label, items, className, setAttributes, isSelected, attributes: { value } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const options = items.map(item => ( { value: item, label: item } ) )
	const selectedValues = value.map(val => ( { value: val, label: val } ) )
	const componentClassName = 'graphql-api-select-card';
	const componentClass = `${ componentClassName } ${ getEditableOnFocusComponentClass(isSelected) }`;
	/**
	 * Optional props
	 */
	const isMulti = props.isMulti != undefined ? props.isMulti : true;
	const closeMenuOnSelect = props.closeMenuOnSelect != undefined ? props.closeMenuOnSelect : false;
	return (
		<div className={ componentClass }>
			<Card { ...props }>
				<CardHeader isShady>
					{ label }
				</CardHeader>
				<CardBody>
					{ isSelected &&
						<Select
							defaultValue={ selectedValues }
							options={ options }
							isMulti={ isMulti }
							closeMenuOnSelect={ closeMenuOnSelect }
							onChange={ selectedOptions =>
								// Extract the attribute "value"
								setAttributes( {
									value: (selectedOptions || []).map(option => option.value)
								} )
							}
						/>
					}
					{ !isSelected && !!value.length && (
						<div className={ `${ className }__label-group ${ componentClassName }__label-group` }>
							{ value.map( val =>
								<div className={ `${ className }__label-item ${ componentClassName }__label-item` }>
									{ val }
								</div>
							) }
						</div>
					) }
					{ !isSelected && !value.length && (
						<em>{ __('(not set)', 'graphql-api') }</em>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SelectCard;
