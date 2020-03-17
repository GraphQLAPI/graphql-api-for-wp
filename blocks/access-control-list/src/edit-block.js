import { compose, withState } from '@wordpress/compose';
import DisableAccess from './disable-access';
import { withAccessControlList } from '../../../packages/components/src';
import './style.scss';

/**
 * Same constant as in \PoP\AccessControl\Services\AccessControlGroups::DISABLED
 */
const ACCESS_CONTROL_GROUP = 'disabled';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlList(),
] )( DisableAccess );
