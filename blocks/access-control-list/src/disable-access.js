import { __ } from '@wordpress/i18n';

const DisableAccess = ( props ) => {
	const { className } = props;
	return (
		<div className={ className+'__disable_access' }>
			<p>Saraza barabadanga</p>
		</div>
	);
}

export default DisableAccess;
