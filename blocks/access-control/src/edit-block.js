import { compose } from '@wordpress/compose';
import AccessControl from './access-control';
import { withAccessControlList } from '../../../packages/components/src';

export default compose( [
	withAccessControlList(),
] )( AccessControl );
