/**
 * External dependencies
 */
import fetch from 'isomorphic-fetch';

const controls = {
	RECEIVE_FIELDS_AND_DIRECTIVES( action ) {
		console.log('action', action, action.path);
		let content = {
			query: action.path,
		};
		return fetch( `${ window.location.origin }/api/graphql`, {
			method: 'post',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify( content ),
		} ).then( ( response ) => response.json() );
		// return fetch( `${ window.location.origin }/api/graphql/?query=${ action.path.replace(/\s/g,'') }`, {
		// 	// method: 'post',
		// 	headers: { 'Content-Type': 'application/json' },
		// 	// body: JSON.stringify( content ),
		// } ).then( ( response ) => response.json() );
	},
};

export default controls;
