/**
 * External dependencies
 */
import fetch from 'isomorphic-fetch';

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
const fetchGraphQLQuery = (query) => {
	const content = {
		query: query,
	};
	return fetch( `${ window.location.origin }${ GRAPHQL_ADMIN_ENDPOINT }`, {
		method: 'post',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify( content ),
	} ).then( ( response ) => response.json() );
};

/**
 * Exports
 */
export default fetchGraphQLQuery;
