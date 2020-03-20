import { compose, withState } from '@wordpress/compose';
import UserRoles from './user-roles';
import withAccessControlGroup from '../../access-control/src/with-access-control-group';

/**
 * Same constant as in \PoP\UserRolesAccessControl\Services\AccessControlGroups::ROLES
 */
const ACCESS_CONTROL_GROUP = 'roles';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( UserRoles );
