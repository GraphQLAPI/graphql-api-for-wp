import { compose, withState } from '@wordpress/compose';
import UserState from './user-state';
import withAccessControlGroup from '../../access-control/src/with-access-control-group';

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
