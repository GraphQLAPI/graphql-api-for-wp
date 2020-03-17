/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import MultiSelectControl from '../multi-select-control';

const TYPE_FIELD_SEPARATOR = '.';

export default compose( [
	withSelect( ( select ) => {
		const {
			getTypeFields,
			retrievedTypeFields,
			getRetrievingTypeFieldsErrorMessage,
			getDirectives,
			retrievedDirectives,
			getRetrievingDirectivesErrorMessage,
		} = select ( 'leoloso/graphql-api' );
		//
		/**
		 * Convert typeFields object, from this structure:
		 * [{type:"Type", fields:["field1", "field2",...]},...]
		 * To this one:
		 * [{group:"typeName",title:"field1",value:"typeName/field1"},...]
		 */
		const items = getTypeFields().flatMap(function(typeItem) {
			return typeItem.fields.flatMap(function(field) {
				return [{
					group: typeItem.typeName,
					title: field,
					value: `${ typeItem.typeNamespacedName }${ TYPE_FIELD_SEPARATOR }${ field }`,
				}]
			})
		});
		// console.log('items', items);

		return {
			items,
			retrievedTypeFields: retrievedTypeFields(),
			retrievingTypeFieldsErrorMessage: getRetrievingTypeFieldsErrorMessage(),
			directives: getDirectives(),
			retrievedDirectives: retrievedDirectives(),
			retrievingDirectivesErrorMessage: getRetrievingDirectivesErrorMessage(),
		};
	} ),
] )( MultiSelectControl );
