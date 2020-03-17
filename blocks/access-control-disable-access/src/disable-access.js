import { __, sprintf } from '@wordpress/i18n';

const DisableAccess = ( props ) => {
	const { className } = props;
	return (
		<div className={ className+'__disable_access' }>
			<p>{ sprintf(
				'%1$s %2$s',
				'⛔️',
				__('Nobody', 'graphql-api')
			) }</p>
		</div>
	);
}

export default DisableAccess;
