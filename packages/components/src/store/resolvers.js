/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

/**
 * External dependencies
 */
import {
	receiveTypeFields,
	setTypeFields,
	receiveDirectives,
	setDirectives,
} from './action-creators';

/**
 * GraphQL query to fetch the list of types and their fields from the GraphQL schema
 */
export const FETCH_TYPE_FIELDS_GRAPHQL_QUERY = `
	query GetTypeFields {
		__schema {
			types {
				name
				fields(includeDeprecated: true) {
					name
				}
			}
		}
	}
`;

/**
 * GraphQL query to fetch the list of directives from the GraphQL schema
 */
export const FETCH_DIRECTIVES_GRAPHQL_QUERY = `
	query GetDirectives {
		__schema {
			directives {
				name
			}
		}
	}
`

/**
 * If the response contains error(s), return a concatenated error message
 *
 * @param {Object} response A response object from the GraphQL server
 * @return {string|null} The error message or nothing
 */
const maybeGetErrorMessage = (response) => {
	if (response.errors && response.errors.length) {
		return sprintf(
			__(`There were errors when retrieving data: %s`, 'graphql-api'),
			response.errors.map(error => error.message).join(';')
		);
	}
	return null;
}
export default {
	/**
	 * Fetch the typeFields from the GraphQL server
	 *
	 * @param {bool} keepScalarTypes Keep the scalar types in the typeFields object
	 * @param {bool} keepIntrospectionTypes Keep the introspection types (__Type, __Directive, __Field, etc) in the typeFields object
	 */
	* getTypeFields( keepScalarTypes = false, keepIntrospectionTypes = false ) {

		const response = yield receiveTypeFields( FETCH_TYPE_FIELDS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setTypeFields( [], maybeErrorMessage );
		}
		/**
		 * Convert the response to an array with this structure:
		 * {
		 *   "type": string (where currently is "type.name")
		 *   "fields": array|null (where currently is "type.fields.name")
		 * }
		 */
		const typeFields = response.data?.__schema?.types?.map(element => ({
			type: element.name,
			fields: element.fields == null ? null : element.fields.map(subelement => subelement.name),
		})) || [];
		return setTypeFields( typeFields );
	},

	/**
	 * Fetch the directives from the GraphQL server
	 */
	* getDirectives() {

		const response = yield receiveDirectives( FETCH_DIRECTIVES_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			setDirectives( [], maybeErrorMessage );
			return;
		}
		/**
		 * Flatten the response to an array containing the directive name directly (extracting them from under the "name" key)
		 */
		const directives = response.data?.__schema?.directives?.map(element => element.name) || [];
		return setDirectives( directives );
	},
};
