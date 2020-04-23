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
	const { label, options, defaultValue, className, setAttributes, isSelected, attributes, attributeName } = props;
	/**
	 * Optional props
	 */
	const isMulti = props.isMulti != undefined ? props.isMulti : true;
	/**
	 * By default, if not defined, use the opposite value to isMulti
	 */
	const closeMenuOnSelect = props.closeMenuOnSelect != undefined ? props.closeMenuOnSelect : !isMulti;
	/**
	 * The attribute to update is passed through `attributeName`
	 */
	const value = isMulti ? attributes[ attributeName ] : ( attributes[ attributeName ] ? [attributes[ attributeName ]] : [])
	/**
	 * Create a dictionary, with value as key, and label as the value
	 */
	let valueLabelDictionary = {};
	value.forEach(function(val) {
		// var entry = (options || []).filter( option => option.value == val ).shift();
		// valueLabelDictionary[ val ] = entry ? entry.label : _(`(Undefined item with ID ${ val }`, 'graphql-api');
		valueLabelDictionary[ val ] = val;
	} );
	const componentClassName = 'graphql-api-select-card';
	const componentClass = `${ componentClassName } ${ getEditableOnFocusComponentClass(isSelected) }`;
	return (
		<div className={ componentClass }>
			<Card { ...props }>
				<CardHeader isShady>
					{ label }
				</CardHeader>
				<CardBody>
					{ isSelected &&
						<Select
							defaultValue={ defaultValue }
							options={ options }
							isMulti={ isMulti }
							closeMenuOnSelect={ closeMenuOnSelect }
							onChange={ selected =>
								setAttributes( {
									[ attributeName ]: isMulti ?
										(selected || []).map(option => option.value) :
										selected.value
								} )
							}
						/>
					}
					{ !isSelected && !!value.length && (
						<div className={ `${ className }__label-group ${ componentClassName }__label-group` }>
							{ value.map( val =>
								<div className={ `${ className }__label-item ${ componentClassName }__label-item` }>
									{ valueLabelDictionary[ val ] }
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
