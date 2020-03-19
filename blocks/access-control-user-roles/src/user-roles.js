import { __ } from '@wordpress/i18n';
import { Card, CardBody, CardHeader } from '@wordpress/components';
import Select from 'react-select';

const UserRoles = ( props ) => {
	const { className, setAttributes, attributes: { value } } = props;
	const colourOptions = [
		{ value: 'ocean', label: 'Ocean', color: '#00B8D9', isFixed: true },
		{ value: 'blue', label: 'Blue', color: '#0052CC', isDisabled: true },
		{ value: 'purple', label: 'Purple', color: '#5243AA' },
		{ value: 'red', label: 'Red', color: '#FF5630', isFixed: true },
		{ value: 'orange', label: 'Orange', color: '#FF8B00' },
		{ value: 'yellow', label: 'Yellow', color: '#FFC400' },
		{ value: 'green', label: 'Green', color: '#36B37E' },
		{ value: 'forest', label: 'Forest', color: '#00875A' },
		{ value: 'slate', label: 'Slate', color: '#253858' },
		{ value: 'silver', label: 'Silver', color: '#666666' },
	  ];
	  const groupedOptions = [
		{
		  label: 'Colours',
		  options: colourOptions,
		},
		{
		  label: 'Flavours',
		  options: flavourOptions,
		},
	  ];
	const flavourOptions = [
		{ value: 'vanilla', label: 'Vanilla', rating: 'safe' },
		{ value: 'chocolate', label: 'Chocolate', rating: 'good' },
		{ value: 'strawberry', label: 'Strawberry', rating: 'wild' },
		{ value: 'salted-caramel', label: 'Salted Caramel', rating: 'crazy' },
	  ];
	const groupStyles = {
		display: 'flex',
		alignItems: 'center',
		justifyContent: 'space-between',
	  };
	  const groupBadgeStyles = {
		backgroundColor: '#EBECF0',
		borderRadius: '2em',
		color: '#172B4D',
		display: 'inline-block',
		fontSize: 12,
		fontWeight: 'normal',
		lineHeight: '1',
		minWidth: 1,
		padding: '0.16666666666667em 0.5em',
		textAlign: 'center',
	  };

	  const formatGroupLabel = data => (
		<div style={groupStyles}>
		  <span>{data.label}</span>
		  <span style={groupBadgeStyles}>{data.options.length}</span>
		</div>
	  );
	return (
		<div className={ className+'__user_roles' }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('The user has any of these roles', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<Select
						defaultValue={ colourOptions[1] }
						options={ groupedOptions }
						formatGroupLabel={ formatGroupLabel }
						isMulti
						closeMenuOnSelect={ false }
					/>
				</CardBody>
			</Card>
		</div>
	);
}

export default UserRoles;
