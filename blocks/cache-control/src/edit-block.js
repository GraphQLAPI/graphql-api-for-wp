import { compose } from '@wordpress/compose';
import CacheControl from './cache-control';
import { withFieldDirectiveMultiSelectControl } from '@graphqlapi/components';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( CacheControl );
