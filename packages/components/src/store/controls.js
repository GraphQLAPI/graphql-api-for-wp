/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '../../../api-fetch/src';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_TYPE_FIELDS( action ) {
		return fetchGraphQLQuery( action.query );
	},
	RECEIVE_DIRECTIVES( action ) {
		return fetchGraphQLQuery( action.query );
	},
	RECEIVE_ACCESS_CONTROL_LISTS( action ) {
		return fetchGraphQLQuery( action.query );
	},
	RECEIVE_CACHE_CONTROL_LISTS( action ) {
		return fetchGraphQLQuery( action.query );
	},
};

export default controls;
