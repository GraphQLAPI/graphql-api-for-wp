/**
 * WordPress imports
 */
const { registerPlugin } = wp.plugins;

/**
 * Internal imports
 */
import DocumentSettingsPanel, { DOCUMENT_SETTINGS_PANEL_NAME } from './document-settings-panel';

/**
 * Registrations
 */
registerPlugin( DOCUMENT_SETTINGS_PANEL_NAME, {
	render: DocumentSettingsPanel,
	icon: 'welcome-view-site',
} );