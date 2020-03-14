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
export const FETCH_DIRECTIVES_GRAPHQL_QUERY = `
	query GetDirectives {
		__schema {
			directives {
				name
			}
		}
	}
`
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
	* getTypeFields( keepScalarTypes = false, keepIntrospectionTypes = false ) {

		const response = yield receiveTypeFields( FETCH_TYPE_FIELDS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and show the error
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setTypeFields( [], maybeErrorMessage );
		}
		/**
		 * Convert the response to an array with this structure:
		 * {
		 * "type": string
		 * "fields": array|null
		 * }
		 */
		const typeFields = response.data?.__schema?.types.map(element => ({
			type: element.name,
			fields: element.fields == null ? null : element.fields.map(subelement => subelement.name),
		}));
		return setTypeFields( typeFields );
	},
	* getDirectives() {

		const response = yield receiveDirectives( FETCH_DIRECTIVES_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and show the error
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			setDirectives( [], maybeErrorMessage );
			return;
		}
		/**
		 * Convert the response to an array directly, removing the "name" key
		 */
		const directives = response.data?.__schema?.directives.map(element => element.name);
		return setDirectives( directives );
	},
};
