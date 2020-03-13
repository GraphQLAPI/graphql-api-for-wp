
export function setTypesAndFields( typesAndFields ) {
	return {
		type: 'SET_TYPES_AND_FIELDS',
		typesAndFields,
	};
};

export function receiveTypesAndFields( path ) {
	return {
		type: 'RECEIVE_TYPES_AND_FIELDS',
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
