import { compose } from '@wordpress/compose';
import CacheControl from './cache-control';
import { withAccessControlList } from '../../../packages/components/src';

export default compose( [
	withAccessControlList(),
] )( CacheControl );
