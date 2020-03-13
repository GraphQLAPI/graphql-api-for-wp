
export function setTypeFields( typeFields ) {
	return {
		type: 'SET_TYPE_FIELDS',
		typeFields,
	};
};

export function receiveTypeFields( path ) {
	return {
		type: 'RECEIVE_TYPE_FIELDS',
		path,
	};
};

export function setDirectives( directives ) {
	return {
		type: 'SET_DIRECTIVES',
		directives,
	};
};

export function receiveDirectives( path ) {
	return {
		type: 'RECEIVE_DIRECTIVES',
		path,
	};
};
