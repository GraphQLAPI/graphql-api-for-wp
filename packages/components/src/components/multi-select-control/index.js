/**
 * External dependencies
 */
import { filter, flatMap } from 'lodash';

/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { TextControl, Spinner } from '@wordpress/components';
import { __, _n } from '@wordpress/i18n';

// Addition by Leo
import './style.scss';

/**
 * Internal dependencies
 */
import BlockManagerCategory from './category';

function MultiSelectControl( {
	search,
	setState,
	blockTypes,
	categories,
	selectedFields,
	setAttributes,
	typeFields,
	retrievedTypeFields,
	retrievingTypeFieldsErrorMessage,
	directives,
	retrievedDirectives,
	retrievingDirectivesErrorMessage,
	isMatchingSearchTerm,
} ) {
	// Filtering occurs here (as opposed to `withSelect`) to avoid wasted
	// wasted renders by consequence of `Array#filter` producing a new
	// value reference on each call.
	blockTypes = blockTypes.filter(
		( blockType ) => ! search || isMatchingSearchTerm( blockType, search )
	);
	// // If the type matches the search, return all fields
	// // Otherwise, return all fields that match the search
	// if (search) {
	// 	search = search.toLowerCase();
	// 	typeFields = typeFields.map(
	// 		( typeFieldsItem ) => typeFieldsItem.toLowerCase().include(search) ?
	// 			typeFieldsItem :
	// 			typeFieldsItem.fields.filter(
	// 				( typeFieldsItemField ) => typeFieldsItemField.toLowerCase().include(search)
	// 			)
	// 	);
	// 	typeFields = typeFields.filter(
	// 		( typeFieldsItem ) => isMatchingSearchTerm( blockType, search )
	// 	);
	// }

	return (
		<div className="edit-post-manage-blocks-modal__content">
			{ retrievedTypeFields && retrievingTypeFieldsErrorMessage && (
				<p className="edit-post-manage-blocks-modal__error_message">
					{ retrievingTypeFieldsErrorMessage }
				</p>
			) }
			{ retrievedTypeFields && !retrievingTypeFieldsErrorMessage && (
				<>
					<TextControl
						type="search"
						label={ __( 'Search' ) }
						value={ search }
						onChange={ ( nextSearch ) =>
							setState( {
								search: nextSearch,
							} )
						}
						className="edit-post-manage-blocks-modal__search"
					/>
					<div
						tabIndex="0"
						role="region"
						aria-label={ __( 'Available block types' ) }
						className="edit-post-manage-blocks-modal__results"
					>
						{ blockTypes.length === 0 && (
							<p className="edit-post-manage-blocks-modal__no-results">
								{ __( 'No blocks found.' ) }
							</p>
						) }
						{ categories.map( ( category ) => (
							<BlockManagerCategory
								key={ category.slug }
								category={ category }
								blockTypes={ filter( blockTypes, {
									category: category.slug,
								} ) }
								selectedFields={ selectedFields }
								setAttributes={ setAttributes }
							/>
						) ) }
					</div>
				</>
			) }
			{ !retrievedTypeFields && (
				<Spinner />
			) }
		</div>
	);
}

export default compose( [
	withState( { search: '' } ),
	withSelect( ( select ) => {
		const {
			getBlockTypes,
			getCategories,
			isMatchingSearchTerm,
		} = select( 'core/blocks' );
		// console.log('getBlockTypes', getBlockTypes());
		// console.log('getCategories', getCategories());
		const {
			getTypeFields,
			retrievedTypeFields,
			getRetrievingTypeFieldsErrorMessage,
			getDirectives,
			retrievedDirectives,
			getRetrievingDirectivesErrorMessage,
		} = select ( 'leoloso/graphql-api' );
		// console.log('receiveFieldsAndDirectives', getTypeFields(), getDirectives());
		// console.log('retrievedTypeFields', retrievedTypeFields());
		//
		/**
		 * Convert typeFields object, from this structure:
		 * [{type:"Type", fields:["field1", "field2",...]},...]
		 * To this one:
		 * [{group:"Type",name:"field1",value:"field1"},...]
		 */
		const items = getTypeFields().flatMap(function(typeItem) {
			return typeItem.fields.flatMap(function(field) {
				return [{
					group: typeItem.type,
					name: field,
					value: field,
				}]
			})
		});
		// console.log('items', items);

		return {
			blockTypes: getBlockTypes(),
			categories: getCategories(),
			isMatchingSearchTerm,
			typeFields: getTypeFields(),
			retrievedTypeFields: retrievedTypeFields(),
			retrievingTypeFieldsErrorMessage: getRetrievingTypeFieldsErrorMessage(),
			directives: getDirectives(),
			retrievedDirectives: retrievedDirectives(),
			retrievingDirectivesErrorMessage: getRetrievingDirectivesErrorMessage(),
		};
	} ),
] )( MultiSelectControl );
