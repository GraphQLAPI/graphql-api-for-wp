/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import { ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	withCard,
	withEditableOnFocus,
	LinkableInfoTooltip,
} from '../../../packages/components/src';

const PersistedQueryOptions = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isEnabled,
			acceptVariablesAsURLParams,
		}
	} = props;
	return (
		<>
			<div className={ `${ className }__enabled` }>
				<em>{ __('Enabled?', 'graphql-api') }</em>
				{ !isSelected && (
					<>
						<br />
						{ isEnabled ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }` }
					</>
				) }
				{ isSelected &&
					<ToggleControl
						{ ...props }
						label={ isEnabled ? __('Yes', 'graphql-api') : __('No', 'graphql-api') }
						checked={ isEnabled }
						onChange={ newValue => setAttributes( {
							isEnabled: newValue,
						} ) }
					/>
				}
			</div>
			<hr />
			<div className={ `${ className }__variables_enabled` }>
				<em>{ __('Accept variables as URL params?', 'graphql-api') }</em>
				<LinkableInfoTooltip
					{ ...props }
					text={ __('Allow URL params to be the input for variables in the query', 'graphql-api') }
					href="https://graphql-api.com/documentation/#persisted-query-variables"
				/>
				{ !isSelected && (
					<>
						<br />
						{ acceptVariablesAsURLParams ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }` }
					</>
				) }
				{ isSelected &&
					<ToggleControl
						{ ...props }
						label={ acceptVariablesAsURLParams ? __('Yes', 'graphql-api') : __('No', 'graphql-api') }
						checked={ acceptVariablesAsURLParams }
						onChange={ newValue => setAttributes( {
							acceptVariablesAsURLParams: newValue,
						} ) }
					/>
				}
			</div>
		</>
	);
}

export default compose( [
	withState( {
		header: __('Options', 'graphql-api'),
	} ),
	withEditableOnFocus(),
	withCard(),
] )( PersistedQueryOptions );
