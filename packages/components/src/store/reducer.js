/**
 * WordPress dependencies
 */
import { combineReducers } from '@wordpress/data';

/**
 * Reducer returning an array of fields and directives.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
export const fieldsAndDirectives = (
	state = {
		fieldsAndDirectives: {}
	},
	action
) => {
	switch ( action.type ) {
		case 'SET_FIELDS_AND_DIRECTIVES':
			return {
				...state,
				fieldsAndDirectives: action.fieldsAndDirectives,
			};
	}
	return state;
};


export default combineReducers( {
	fieldsAndDirectives,
} );
