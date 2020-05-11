/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
const { PluginDocumentSettingPanel } = wp.editPost;

/**
 * Internal dependencies
 */
import './style.scss';
import EndpointGuideButton from './guide';
import { InfoModalButton } from '../../../../packages/components/src';

/**
 * Name of the Settings Panel
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'endpoint-document-settings-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Welcome Guide', 'graphql-api') }
    >
        <EndpointGuideButton />
        Here some text
        <InfoModalButton
            content="sarlanga"
            title="maranga"
        />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };