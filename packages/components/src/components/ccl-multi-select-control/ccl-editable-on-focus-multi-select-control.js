/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import CacheControlListMultiSelectControl from './ccl-multi-select-control';
import CacheControlListPrintout from './ccl-printout';

const CacheControlListEditableOnFocusMultiSelectControl = ( props ) => {
	const { setAttributes, isSelected, attributes: { cacheControlLists } } = props;
	const className = 'graphql-api-cache-control-list-select';
	const componentClassName = `nested-component editable-on-focus is-selected-${ isSelected }`;
	return (
		<div className={ className }>
			<div className={ componentClassName }>
				{ isSelected &&
					<CacheControlListMultiSelectControl
						{ ...props }
						selectedItems={ cacheControlLists }
						setAttributes={ setAttributes }
						className={ className }
					/>
				}
				{ !isSelected && (
					<CacheControlListPrintout
						{ ...props }
						selectedItems={ cacheControlLists }
						className={ className }
					/>
				) }
			</div>
		</div>
	);
}

export default CacheControlListEditableOnFocusMultiSelectControl;
