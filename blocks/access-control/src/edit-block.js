import { compose } from '@wordpress/compose';
import AccessControl from './access-control';
import { withFieldDirectiveMultiSelectControl } from '../../../packages/components/src';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( AccessControl );
