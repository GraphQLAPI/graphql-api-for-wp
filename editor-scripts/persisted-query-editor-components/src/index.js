/**
 * WordPress imports
 */
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal imports
 */
import DocumentSettingsPanel, { DOCUMENT_SETTINGS_PANEL_NAME } from './document-settings-panel';

// ------------------------------------------------------
// IMPORTANT: THIS IS A HACK TO FIX A BUG
// @see: https://github.com/WordPress/gutenberg/issues/23607
// Do NOT remove this line below!!!!
// When doing so, executing a block compiled with `npm run build` in the browser does not work!
// The block doesn't get added to `wp.blocks.getBlockTypes()`, and when executing,
// it shows an error in the browser console:
// Uncaught TypeError: e[t] is undefined
// When adding this hack below, the following code gets added to the compiled `build/index.js` file:
// ```
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
// ```
const fixIncrediblyWeirdBug = {
	...{},
}
// ------------------------------------------------------

/**
 * Registrations
 */
registerPlugin( DOCUMENT_SETTINGS_PANEL_NAME, {
	render: DocumentSettingsPanel,
	icon: 'welcome-view-site',
} );
