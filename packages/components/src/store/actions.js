
export function setFieldsAndDirectives( fieldsAndDirectives ) {
	return {
		type: 'SET_FIELDS_AND_DIRECTIVES',
		fieldsAndDirectives,
	};
};

export function receiveFieldsAndDirectives( path ) {
	return {
		type: 'RECEIVE_FIELDS_AND_DIRECTIVES',
		path,
	};
};
