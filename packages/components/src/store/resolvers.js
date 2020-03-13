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
	* receiveTypeFields( state ) {
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
		// console.log('response', response, response.data?.__schema?.types ?? []);
		return setTypeFields( response.data?.__schema?.types ?? [] );
	},
	* receiveDirectives( state ) {
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
		return setDirectives( response.data?.__schema?.directives ?? [] );
	},
};
