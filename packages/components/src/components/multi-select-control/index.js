/**
 * External dependencies
 */
import { filter, uniq } from 'lodash';

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

const TYPE_FIELD_SEPARATOR = '.';

function MultiSelectControl( {
	search,
	setState,
	selectedFields,
	setAttributes,
	items,
	retrievedTypeFields,
	retrievingTypeFieldsErrorMessage,
	directives,
	retrievedDirectives,
	retrievingDirectivesErrorMessage,
} ) {
	// Filtering occurs here (as opposed to `withSelect`) to avoid wasted
	// wasted renders by consequence of `Array#filter` producing a new
	// value reference on each call.
	// If the type matches the search, return all fields. Otherwise, return all fields that match the search
	if (search) {
		search = search.toLowerCase();
		items = items.filter(
			( item ) => item.group.includes(search) || item.title.includes(search)
		);
	}
	const groups = uniq(items.map(
		( item ) => item.group
	))

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
						{ items.length === 0 && (
							<p className="edit-post-manage-blocks-modal__no-results">
								{ __( 'No item found.' ) }
							</p>
						) }
						{ groups.map( ( group ) => (
							<BlockManagerCategory
								key={ group }
								group={ group }
								items={ filter( items, {
									group: group,
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
