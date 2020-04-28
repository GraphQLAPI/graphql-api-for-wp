/**
 * External dependencies
 */
import { request } from 'graphql-request'

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

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
	 * Add the successful response under key "data", which is stripped by "graphql-request"
	 */
	return request(`${ window.location.origin }${ GRAPHQL_ADMIN_ENDPOINT }`, query, variables)
		.then(response => ({
			data: response
		}))
		.catch(
			/**
			 * If it is a 500 response, return its error message under entry "errors"
			 */
			err => err.response.status == 500 ? {
				errors: [ {
					message: `${ __('[Internal Server Error (500)]:', 'graphql-api') } ${ err.response.message }`
				} ],
			} : err.response
		);
};

/**
 * Exports
 */
export default fetchGraphQLQuery;
