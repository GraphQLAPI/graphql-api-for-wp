/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { TYPE_FIELD_SEPARATOR } from './field-multi-select-control';
import withSpinner from '../multi-select-control/with-spinner';
import withErrorMessage from '../multi-select-control/with-error-message';

const getElementList = ( elements, className ) => {
	return elements.length ? (
		<span className={ className+'__item_data__list' }>
			{ elements.map(element => <span><br/>✅ { element }</span>)}
		</span>
	) : (
		__('None selected', 'graphql-api')
	);
}

const FieldDirectivePrintout = ( props ) => {
	const { typeFields, directives, className, typeFieldNames } = props;
	return (
		<Card { ...props }>
			<CardHeader isShady>{ __('Fields, by type', 'graphql-api') }</CardHeader>
			<CardBody>
				{ !! typeFields.length && typeFields.map( typeField =>
					<>
						{ typeFieldNames[ typeField ] }<br/>
					</>
				) }
				{ !typeFields.length && (
					__('No fields selected', 'graphql-api')
				) }
			</CardBody>
			<CardHeader isShady>{ __('(Non-system) Directives', 'graphql-api') }</CardHeader>
			<CardBody>
				{ !! directives.length && directives.map( directive =>
					<>
						{ directive }<br/>
					</>
				) }
				{ !directives.length && (
					__('No directives selected', 'graphql-api')
				) }
			</CardBody>
		</Card>
	);
}

// export default withAccessControlList;
export default compose( [
	withSelect( ( select ) => {
		const {
			getTypeFields,
			hasRetrievedTypeFields,
			getRetrievingTypeFieldsErrorMessage,
		} = select ( 'leoloso/graphql-api' );
		/**
		 * Convert typeFields object, from this structure:
		 * [{type:"Type", fields:["field1", "field2",...]},...]
		 * To this one:
		 * {namespacedTypeName.field:"typeName/field",...}
		 */
		const TYPE_FIELD_NAME_SEPARATOR = '/';
		const reducer = (accumulator, currentValue) => Object.assign(accumulator, currentValue);
		const typeFieldNames = getTypeFields().flatMap(function(typeItem) {
			return typeItem.fields.flatMap(function(field) {
				return {
					[`${ typeItem.typeNamespacedName }${ TYPE_FIELD_SEPARATOR }${ field }`]: `${ typeItem.typeName }${ TYPE_FIELD_NAME_SEPARATOR }${ field }`,
				}
			})
		}).reduce(reducer, {});
		return {
			typeFieldNames,
			hasRetrievedItems: hasRetrievedTypeFields(),
			errorMessage: getRetrievingTypeFieldsErrorMessage(),
		};
	} ),
	withSpinner(),
	withErrorMessage(),
] )( FieldDirectivePrintout );
