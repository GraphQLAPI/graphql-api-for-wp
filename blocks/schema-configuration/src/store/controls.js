/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '../../../../packages/api-fetch/src';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_SCHEMA_CONFIGURATIONS( action ) {
		return fetchGraphQLQuery( action.query );
	},
};

export default controls;
