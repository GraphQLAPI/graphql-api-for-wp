/**
 * External dependencies
 */
import fetch from 'isomorphic-fetch';

const fetchGraphQLQuery = (query) => {
	// console.log('query', query);
	const content = {
		query: query,
	};
	return fetch( `${ window.location.origin }/api/graphql`, {
		method: 'post',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify( content ),
	} ).then( ( response ) => response.json() );
};

const controls = {
	RECEIVE_TYPES_AND_FIELDS( action ) {
		return fetchGraphQLQuery(action.path);
	},
	RECEIVE_DIRECTIVES( action ) {
		return fetchGraphQLQuery(action.path);
	},
};

export default controls;
