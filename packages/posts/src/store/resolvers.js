/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

/**
 * External dependencies
 */
import {
	receiveAccessControlLists,
	setAccessControlLists,
} from './action-creators';

/**
 * Custom Post Type name
 * Same value as Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType::POST_TYPE
 */
const ACCESS_CONTROL_LIST_POST_TYPE = 'graphql-acl';

/**
 * GraphQL query to fetch the list of Access Control Lists from the GraphQL schema
 */
const noTitleLabel = __('(No title)', 'graphql-api');
export const FETCH_ACCESS_CONTROL_LISTS_GRAPHQL_QUERY = `
	query GetAccessControlLists {
		posts(postTypes: ["${ ACCESS_CONTROL_LIST_POST_TYPE }"]) {
			id
			title @default(value: "${ noTitleLabel }", condition: IS_EMPTY)
		}
	}
`

/**
 * If the response contains error(s), return a concatenated error message
 *
 * @param {Object} response A response object from the GraphQL server
 * @return {string|null} The error message or nothing
 */
const maybeGetErrorMessage = (response) => {
	if (response.errors && response.errors.length) {
		return sprintf(
			__(`There were errors when retrieving data: %s`, 'graphql-api'),
			response.errors.map(error => error.message).join(';')
		);
	}
	return null;
}

export { maybeGetErrorMessage };
export default {
	/**
	 * Fetch the Access Control Lists from the GraphQL server
	 */
	* getAccessControlLists() {

		const response = yield receiveAccessControlLists( FETCH_ACCESS_CONTROL_LISTS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			setAccessControlLists( [], maybeErrorMessage );
			return;
		}
		return setAccessControlLists( response.data?.posts );
	},
};
