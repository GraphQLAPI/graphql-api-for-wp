/**
 * External dependencies
 */
import {
	receiveTypesAndFields,
	setTypesAndFields,
	receiveDirectives,
	setDirectives,
} from './actions';


export default {
	* receiveTypesAndFields( state ) {
		const query = `
			query GetTypesAndFields {
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
		const response = yield receiveTypesAndFields( query );
		// console.log('response', response, response.data?.__schema?.types ?? []);
		return setTypesAndFields( response.data?.__schema?.types ?? [] );
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
