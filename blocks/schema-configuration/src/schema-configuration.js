/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { /*withErrorMessage, withSpinner, */SelectCard } from '../../../packages/components/src';

const GetLabelForNotFoundValue = ( val ) => __(`(Undefined item with ID ${ val })`, 'graphql-api');

const SchemaConfigurationSelectCard = ( props ) => {
	const { queryPostParent, schemaConfigurations, attributes: { schemaConfiguration } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 * Convert the schemaConfigurations array to this structure:
	 * [{label:"schemaConfiguration.title",value:"schemaConfiguration.id"},...]
	 */
	const schemaConfigurationOptions = schemaConfigurations.map( schemaConfiguration => (
		{
			// label: `→ ${ schemaConfiguration.title }`,
			label: schemaConfiguration.title,
			value: schemaConfiguration.id,
		}
	) );
	/**
	 * If this query has a parent, then add option "Inherit from parent"
	 */
	const metaOptions = ( schemaConfiguration == -2 || queryPostParent ?
		[
			{
				label: `🛑 ${ __('Inherit from parent', 'graphql-api') }`,
				value: -2,
			}
		]
		: []
	).concat([
		{
			label: `⭕️ ${ __('Default', 'graphql-api') }`,
			value: 0,
		},
		{
			label: `❌ ${ __('None', 'graphql-api') }`,
			value: -1,
		},
	])

	const options = metaOptions.concat(
		schemaConfigurationOptions
	);
	const groupedOptions = [
		{
		  label: '',
		  options: metaOptions,
		},
		{
		  label: '',
		  options: schemaConfigurationOptions,
		},
	  ];
	/**
	 * Create a dictionary, with ID as key, and title as the value
	 */
	/**
	 * React Select expects to pass the same elements from the options as defaultValue,
	 * including the label
	 * { value: ..., label: ... },
	 */
	const defaultValue = schemaConfiguration != null ?
		options.filter( option => option.value == schemaConfiguration ).shift() :
		null;
	/**
	 * Check if the schema configurations have not been fetched yet,
	 * or if there are selected items (for which we need the data to know the label),
	 * then show the spinner
	 */
	const maybeShowSpinnerOrError = !schemaConfigurations?.length || schemaConfiguration != null;
	return (
		<SelectCard
			{ ...props }
			isMulti={ false }
			attributeName="schemaConfiguration"
			options={ groupedOptions/*options*/ }
			defaultValue={ defaultValue }
			getLabelForNotFoundValueCallback={ GetLabelForNotFoundValue }
			maybeShowSpinnerOrError={ maybeShowSpinnerOrError }
		/>
	);
}

// const WithSpinnerSchemaConfiguration = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( SchemaConfigurationSelectCard );

// /**
//  * Check if the schema configurations have not been fetched yet,
//  * or if there are selected items (for which we need the data to know the label),
//  * then show the spinner
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerSchemaConfiguration = ( props ) => {
// 	const { schemaConfigurations, attributes: { schemaConfiguration } } = props;
// 	if ( !schemaConfigurations?.length || schemaConfiguration != null ) {
// 		return (
// 			<WithSpinnerSchemaConfiguration { ...props } />
// 		)
// 	}
// 	return (
// 		<SchemaConfigurationSelectCard { ...props } />
// 	);
// }

export default compose( [
	withState( {
		label: __('Schema configuration:', 'graphql-api'),
	} ),
	withSelect( ( select ) => {
		const {
			getSchemaConfigurations,
			hasRetrievedSchemaConfigurations,
			getRetrievingSchemaConfigurationsErrorMessage,
		} = select ( 'graphql-api/schema-configuration' );
		return {
			schemaConfigurations: getSchemaConfigurations(),
			hasRetrievedItems: hasRetrievedSchemaConfigurations(),
			errorMessage: getRetrievingSchemaConfigurationsErrorMessage(),
		};
	} ),
	withSelect( ( select ) => {
		const { getEditedPostAttribute } = select(
			'core/editor'
		);
		return {
			queryPostParent: getEditedPostAttribute( 'parent' ),
		};
	} ),
] )( SchemaConfigurationSelectCard/*MaybeWithSpinnerSchemaConfiguration*/ );
