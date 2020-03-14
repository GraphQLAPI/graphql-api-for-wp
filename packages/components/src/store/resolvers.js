/**
 * External dependencies
 */
import {
	receiveTypeFields,
	setTypeFields,
	receiveDirectives,
	setDirectives,
} from './actions';


export default {
	* getTypeFields( state ) {
		const query = `
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
		`
		const response = yield receiveTypeFields( query );
		/**
		 * If there were erros when executing the query, return an empty list, and show the error
		 */
		if (response.errors && response.errors.length) {
			return setTypeFields( [] );
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
		// console.log('typeFields', typeFields);
		return setTypeFields( typeFields );
	},
	* getDirectives( state ) {
		const query = `
			query GetDirectives {
				__schema {
					directives {
						name
					}
				}
			}
		`
		const response = yield receiveDirectives( query );
		/**
		 * If there were erros when executing the query, return an empty list, and show the error
		 */
		if (response.errors && response.errors.length) {
			return setDirectives( [] );
		}
		/**
		 * Convert the response to an array directly, removing the "name" key
		 */
		const directives = response.data?.__schema?.directives.map(element => element.name);
		// console.log('directives', directives);
		return setDirectives( directives );
	},
};
