import { compose, withState } from '@wordpress/compose';
import DisableAccess from './disable-access';
import { withAccessControlList } from '../../../packages/components/src';
import './style.scss';

const ACCESS_CONTROL_GROUP = 'disabled';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlList(),
] )( DisableAccess );
