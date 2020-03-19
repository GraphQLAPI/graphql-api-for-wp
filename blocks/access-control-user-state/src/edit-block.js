import { compose, withState } from '@wordpress/compose';
import UserState from './user-state';
import { withAccessControlGroup } from '../../../packages/components/src';

/**
 * Same constant as in \PoP\UserStateAccessControl\Services\AccessControlGroups::STATE
 */
const ACCESS_CONTROL_GROUP = 'state';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( UserState );
