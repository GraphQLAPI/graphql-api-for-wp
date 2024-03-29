/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@graphqlapi/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_SCHEMA_CONFIGURATIONS( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
			action.query
		);
	},
};

export default controls;
