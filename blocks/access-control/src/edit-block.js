import { compose } from '@wordpress/compose';
import AccessControl from './access-control';
import { withFieldDirectiveMultiSelectControl } from '@graphqlapi/components';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( AccessControl );
