export function receiveTypeFields( state, keepScalarTypes = false, keepIntrospectionTypes = false ) {
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

export function fetchedTypeFields( state ) {
	const { fetchedTypeFields } = state;
	return fetchedTypeFields;
};

export function receiveDirectives( state ) {
	const { directives } = state;
	return directives;
};

export function fetchedDirectives( state ) {
	const { fetchedDirectives } = state;
	return fetchedDirectives;
};
