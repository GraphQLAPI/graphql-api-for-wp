/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import MultiSelectControl from '../multi-select-control';

const DirectiveMultiSelectControl = compose( [
	withState( { attributeName: 'directives' } ),
	withSelect( ( select ) => {
		const {
			getDirectives,
			hasRetrievedDirectives,
			getRetrievingDirectivesErrorMessage,
		} = select ( 'leoloso/graphql-api' );
		/**
		 * Convert the directives array to this structure:
		 * [{group:"Directives",title:"directiveName",value:"directiveName"},...]
		 */
		const items = getDirectives().map( directive => (
			{
				group: __('Directives', 'graphql-api'),
				title: directive,
				value: directive,
			}
		) );
		return {
			items,
			hasRetrievedItems: hasRetrievedDirectives(),
			errorMessage: getRetrievingDirectivesErrorMessage(),
		};
	} ),
] )( MultiSelectControl );

export default DirectiveMultiSelectControl;
