import { compose } from '@wordpress/compose';
import CacheControl from './cache-control';
import { withFieldDirectiveMultiSelectControl } from '../../../packages/components/src';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( CacheControl );
