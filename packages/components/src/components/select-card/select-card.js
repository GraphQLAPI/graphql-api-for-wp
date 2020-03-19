/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * External dependencies
 */
import Select from 'react-select';


const SelectCard = ( props ) => {
	const { label, items, className, setAttributes, isSelected, attributes: { value } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const options = items.map(item => ( { value: item, label: item } ) )
	const selectedValues = value.map(val => ( { value: val, label: val } ) )
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ label }
				</CardHeader>
				<CardBody>
					{ isSelected &&
						<Select
							defaultValue={ selectedValues }
							options={ options }
							isMulti
							closeMenuOnSelect={ false }
							onChange={ selectedOptions =>
								// Extract the attribute "value"
								setAttributes( {
									value: selectedOptions.map(option => option.value)
								} )
							}
						/>
					}
					{ !isSelected && !!value.length && (
						<div className={ className+'__label-group'}>
							{ value.map( val =>
								<div className={ className+'__label-item'}>
									{ val }
								</div>
							) }
						</div>
					) }
					{ !isSelected && !value.length && (
						__('---', 'graphql-api')
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SelectCard;
