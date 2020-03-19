import { __ } from '@wordpress/i18n';
import { Card, CardBody, CardHeader } from '@wordpress/components';
import Select from 'react-select';

const UserRoles = ( props ) => {
	const { className, setAttributes, isSelected, attributes: { value } } = props;
	const options = [
		{ value: 'ocean', label: 'Ocean' },
		{ value: 'blue', label: 'Blue' },
		{ value: 'purple', label: 'Purple' },
		{ value: 'red', label: 'Red' },
		{ value: 'orange', label: 'Orange' },
		{ value: 'yellow', label: 'Yellow' },
		{ value: 'green', label: 'Green' },
		{ value: 'forest', label: 'Forest' },
		{ value: 'slate', label: 'Slate' },
		{ value: 'silver', label: 'Silver' },
	];
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const selectedValues = value.map(val => ( { value: val, label: val } ) )
	return (
		<div className={ className+'__user_roles' }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('The user has any of these roles', 'graphql-api') }
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
					{ !isSelected && (
						<div className={ className+'__label-group'}>
							{ value.map( val =>
								<div className={ className+'__label-item'}>
									{ val }
								</div>
							) }
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default UserRoles;
