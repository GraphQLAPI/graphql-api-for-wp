/**
 * External dependencies
 */
import { request } from 'graphql-request'

/**
 * The endpoint against which to execute GraphQL queries while on the WordPress admin
 */
const GRAPHQL_ADMIN_ENDPOINT = '/api/graphql';

/**
 * Execute a GraphQL Query and return its results
 *
 * @param {string} query The GraphQL query to execute
 * @return {Object} The response from the GraphQL server
 */
const fetchGraphQLQuery = (query, variables) => {
	/**
	 * Return the response always, both in case of success and error
	 */
	return request(`${ window.location.origin }${ GRAPHQL_ADMIN_ENDPOINT }`, query, variables)
		.then(response => response)
		.catch(err => err.response);
};

/**
 * Exports
 */
export default fetchGraphQLQuery;
