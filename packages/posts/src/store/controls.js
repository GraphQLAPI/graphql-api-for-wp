/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '../../../api-fetch/src';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_ACCESS_CONTROL_LISTS( action ) {
		return fetchGraphQLQuery( action.query );
	},
};

export default controls;
