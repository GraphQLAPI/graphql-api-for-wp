/**
 * External dependencies
 */
import { receiveFieldsAndDirectives, setFieldsAndDirectives } from './actions';


export default {
	* receiveFieldsAndDirectives( state ) {
		const query = `
			query IntrospectionQuery {
				__schema {
					types {
						name
						fields(includeDeprecated: true) {
							name
						}
					}
					directives {
						name
					}
				}
			}
		`
		const fieldsAndDirectives = yield receiveFieldsAndDirectives( query );
		return setFieldsAndDirectives( fieldsAndDirectives.data?.__schema ?? [] );
	},
};
