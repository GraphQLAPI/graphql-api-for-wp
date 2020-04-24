/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import CacheControlListMultiSelectControl from './ccl-multi-select-control';
import CacheControlListPrintout from './ccl-printout';
import { getEditableOnFocusComponentClass } from '../base-styles'

const CacheControlListEditableOnFocusMultiSelectControl = ( props ) => {
	const { isSelected, attributes: { cacheControlLists } } = props;
	const className = 'graphql-api-cache-control-list-select';
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ className }>
			<div className={ componentClassName }>
				{ isSelected &&
					<CacheControlListMultiSelectControl
						{ ...props }
						selectedItems={ cacheControlLists }
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
