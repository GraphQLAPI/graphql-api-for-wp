/**
 * External dependencies
 */
import { filter } from 'lodash';

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
	fetchedTypeFields,
	directives,
	fetchedDirectives,
} ) {
	return (
		<div className="edit-post-manage-blocks-modal__content">
			{ fetchedTypeFields && (
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
			{ !fetchedTypeFields && (
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
		} = select( 'core/blocks' );
		const {
			receiveTypeFields,
			fetchedTypeFields,
			receiveDirectives,
			fetchedDirectives,
		} = select ( 'leoloso/graphql-api' );
		// console.log('receiveFieldsAndDirectives', receiveTypeFields(), receiveDirectives());
		// console.log('fetchedTypeFields', fetchedTypeFields());
		return {
			blockTypes: getBlockTypes(),
			categories: getCategories(),
			typeFields: receiveTypeFields(),
			fetchedTypeFields: fetchedTypeFields(),
			directives: receiveDirectives(),
			fetchedDirectives: fetchedDirectives(),
		};
	} ),
] )( MultiSelectControl );
