/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { TYPE_FIELD_SEPARATOR_FOR_DB, TYPE_FIELD_SEPARATOR_FOR_PRINT } from './block-constants';
import withSpinner from '../loading/with-spinner';
import withErrorMessage from '../loading/with-error-message';

/**
 * Print the selected fields and directives.
 * Watch out: object `typeFields` contains the namespaced type as value, such as `PoP_ComponentModel_Root.users`, which is not proper
 * Then, convert this value to what the user expects: `Root/users`. Because of this formatting, we need to execute a call against the server,
 * to fetch the information of how the type name and type namespaced name
 *
 * @param {Object} props
 */
const FieldDirectivePrintout = ( props ) => {
	const { typeFields, directives, typeFieldNames } = props;
	const groupFieldsUnderTypeForPrint = true;

	/**
	 * Create a dictionary, with typeName as key, and an array with all its fields as the value
	 */
	let combinedTypeFieldNames = {};
	typeFields.forEach(function(typeField) {
		const typeFieldEntry = typeFieldNames[ typeField ];
		combinedTypeFieldNames[ typeFieldEntry.typeName ] = combinedTypeFieldNames[ typeFieldEntry.typeName ] || [];
		combinedTypeFieldNames[ typeFieldEntry.typeName ].push( typeFieldEntry.field );
	} );
	return (
		<Card { ...props }>
			<CardHeader isShady>{ __('Fields, by type:', 'graphql-api') }</CardHeader>
			<CardBody>
				{ !! typeFields.length && (
						( !groupFieldsUnderTypeForPrint && typeFields.map( typeField =>
							<>
								✅ { `${ typeFieldNames[ typeField ].typeName }${ TYPE_FIELD_SEPARATOR_FOR_PRINT }${ typeFieldNames[ typeField ].field }` }<br/>
							</>
						)
					) || ( groupFieldsUnderTypeForPrint && Object.keys(combinedTypeFieldNames).map( typeName =>
						<>
							<strong>{ typeName }</strong><br/>
							{ combinedTypeFieldNames[ typeName ].map( field =>
								<>
									✅ { `${ field }` }<br/>
								</>
							) }
						</>
					) )
				) }
				{ !typeFields.length && (
					__('---', 'graphql-api')
				) }
			</CardBody>
			<CardHeader isShady>{ __('(Non-system) Directives:', 'graphql-api') }</CardHeader>
			<CardBody>
				{ !! directives.length && directives.map( directive =>
					<>
						✅ { directive }<br/>
					</>
				) }
				{ !directives.length && (
					__('---', 'graphql-api')
				) }
			</CardBody>
		</Card>
	);
}

/**
 * Check if the typeFields are empty, then do not show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerFieldDirectivePrintout = ( props ) => {
	const { typeFields } = props;
	if ( !! typeFields.length ) {
		return (
			<WithSpinnerFieldDirectivePrintout { ...props } />
		)
	}
	return (
		<FieldDirectivePrintout { ...props } />
	);
}

/**
 * Add a spinner when loading the typeFieldNames and typeFields is not empty
 */
const WithSpinnerFieldDirectivePrintout = compose( [
	withSpinner(),
	withErrorMessage(),
] )( FieldDirectivePrintout );

export default compose( [
	withSelect( ( select ) => {
		const {
			getTypeFields,
			hasRetrievedTypeFields,
			getRetrievingTypeFieldsErrorMessage,
		} = select ( 'graphql-api/components' );
		/**
		 * Convert typeFields object, from this structure:
		 * [{type:"Type", fields:["field1", "field2",...]},...]
		 * To this one:
		 * {namespacedTypeName.field:"typeName/field",...}
		 */
		const reducer = (accumulator, currentValue) => Object.assign(accumulator, currentValue);
		const typeFieldNames = getTypeFields().flatMap(function(typeItem) {
			return typeItem.fields.flatMap(function(field) {
				return {
					[`${ typeItem.typeNamespacedName }${ TYPE_FIELD_SEPARATOR_FOR_DB }${ field }`]: {
						typeName: typeItem.typeName,
						field: field,
					},
				}
			})
		}).reduce(reducer, {});
		return {
			typeFieldNames,
			hasRetrievedItems: hasRetrievedTypeFields(),
			errorMessage: getRetrievingTypeFieldsErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerFieldDirectivePrintout );
