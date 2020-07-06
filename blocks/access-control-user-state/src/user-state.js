import { __ } from '@wordpress/i18n';
import { Card, CardBody, RadioControl } from '@wordpress/components';
import { IN as USER_STATE_IN, OUT as USER_STATE_OUT } from './user-states';
import { getEditableOnFocusComponentClass } from '@graphqlapi/components';

// ------------------------------------------------------
// IMPORTANT
// Do NOT remove this line below!!!!
// When doing so, executing a block compiled with `npm run build` in the browser does not work!
// The block doesn't get added to `wp.blocks.getBlockTypes()`, and when executing,
// it shows an error in the browser console:
// Uncaught TypeError: e[t] is undefined
// When adding this hack below, the following code gets added to the compiled `build/index.js` file:
	// function(e, t) {
	//     e.exports = function(e, t, n) {
	//         return t in e ? Object.defineProperty(e, t, {
	//             value: n,
	//             enumerable: !0,
	//             configurable: !0,
	//             writable: !0
	//         }) : e[t] = n, e
	//     }
	// }
const fixIncreadiblyWeirdBug = {
	...{},
}
// ------------------------------------------------------

const UserState = ( props ) => {
	const { className, isSelected, setAttributes, attributes: { value } } = props;
	/**
	 * If accessing the block for first time, value will be undefined. Then set it as default to "in"
	 * Can't use the `registerBlockType` configuration to set a default for `value`, because whenever the default value is selected,
	 * Gutenberg doesn't save it to the DB, however we need the value explicitly when extracting the rules from the CPT, that function doesn't know default cases
	 */
	if ( value == undefined ) {
		setAttributes( {
			value: USER_STATE_IN,
		} )
	}
	const options = [
		{
			label: __('Logged-in users', 'graphql-api'),
			value: USER_STATE_IN,
		},
		{
			label: __('Not logged-in users', 'graphql-api'),
			value: USER_STATE_OUT,
		},
	];
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardBody>
					{ isSelected &&
						<RadioControl
							{ ...props }
							// label={ __('User is...', 'graphql-api') }
							options={ options }
							selected={ value }
							onChange={ value => (
								setAttributes( {
									value
								} )
							)}
						/>
					}
					{ !isSelected && (
						<div className={ className+'__read'}>
							{ (value == USER_STATE_IN) &&
								<span>✅ { __('Logged-in users', 'graphql-api') }</span>
							}
							{ (value == USER_STATE_OUT) &&
								<span>❎ { __('Not logged-in users', 'graphql-api') }</span>
							}
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default UserState;
