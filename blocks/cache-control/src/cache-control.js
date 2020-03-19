import { __ } from '@wordpress/i18n';

const CacheControl = ( props ) => {
	const { className } = props;
	return (
		<div className={ className+'__maxage' }>
			Saraza
		</div>
	);
}

export default CacheControl;
