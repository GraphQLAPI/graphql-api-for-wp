/**
 * External dependencies
 */
import { filter, uniq } from 'lodash';
import { search as searchIcon } from '@wordpress/icons';
/**
 * WordPress dependencies
 */
import { compose, withState } from '@wordpress/compose';
import { TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

// Addition by Leo
import './style.scss';

/**
 * Internal dependencies
 */
import BlockManagerCategory from './category';
import withErrorMessage from './with-error-message';
import withSpinner from './with-spinner';

function MultiSelectControl( {
	search,
	setState,
	selectedFields,
	setAttributes,
	items,
	showSearch,
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
			<div className="edit-post-manage-blocks-modal__content_search">
				<Button
					isSmall
					icon={ searchIcon }
					onClick={
						() => setState( {
							showSearch: !showSearch
						} )
					}
				>
					{ showSearch ? __( 'Hide search' ) : __( 'Show search' ) }
				</Button>
			</div>
			{ showSearch &&
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
			}
			<div
				tabIndex="0"
				role="region"
				aria-label={ __( 'Available fields' ) }
				className="edit-post-manage-blocks-modal__results"
			>
				{ items.length === 0 && (
					<p className="edit-post-manage-blocks-modal__no-results">
						{ __( 'No items found.' ) }
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
		</div>
	);
}

export default compose( [
	withState( { search: '', showSearch: false } ),
	withSpinner(),
	withErrorMessage(),
] )( MultiSelectControl );
