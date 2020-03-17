import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import DisableAccess from './disable-access';
import { withAccessControlList } from '../../../packages/components/src';
import './style.scss';

export default compose( [
	withAccessControlList(),
] )( DisableAccess );
