export function getTypeFields( state, keepScalarTypes = false, keepIntrospectionTypes = false ) {
	let { typeFields } = state;
	/**
	 * Each element in typeFields has this shape:
	 * {
	 * "type": string
	 * "fields": array|null
	 * }
	 * Scalar types are those with no fields
	 */
	if ( !keepScalarTypes ) {
		typeFields = typeFields.filter(element => element.fields != null);
	}
	/**
	 * Introspection types (eg: __Schema, __Directive, __Type, etc) start with "__"
	 */
	if ( !keepIntrospectionTypes ) {
		typeFields = typeFields.filter(element => !element.type.startsWith('__'));
	}
	return typeFields;
};

export function retrievedTypeFields( state ) {
	return state.retrievedTypeFields;
};

export function getRetrievingTypeFieldsErrorMessage( state ) {
	return state.retrievingTypeFieldsErrorMessage;
};

export function getDirectives( state ) {
	return state.directives;
};

export function retrievedDirectives( state ) {
	return state.retrievedDirectives;
};

export function getRetrievingDirectivesErrorMessage( state ) {
	return state.retrievingDirectivesErrorMessage;
};
