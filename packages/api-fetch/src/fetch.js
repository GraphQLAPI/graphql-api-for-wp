/**
 * External dependencies
 */
import fetch from 'isomorphic-fetch';

const fetchGraphQLQuery = (query) => {
	const content = {
		query: query,
	};
	return fetch( `${ window.location.origin }/api/graphql`, {
		method: 'post',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify( content ),
	} ).then( ( response ) => response.json() );
};

export default fetchGraphQLQuery;
