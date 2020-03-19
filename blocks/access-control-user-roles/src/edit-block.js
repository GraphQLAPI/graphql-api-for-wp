import { compose, withState } from '@wordpress/compose';
import UserRoles from './user-roles';
import { withAccessControlGroup } from '../../../packages/components/src';

/**
 * Same constant as in \PoP\UserRolesAccessControl\Services\AccessControlGroups::STATE
 */
const ACCESS_CONTROL_GROUP = 'state';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( UserRoles );
