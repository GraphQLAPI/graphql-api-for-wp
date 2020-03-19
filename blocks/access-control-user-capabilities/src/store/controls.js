/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '../../../../packages/api-fetch/src';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_CAPABILITIES( action ) {
		return fetchGraphQLQuery( action.query );
	},
};

export default controls;
