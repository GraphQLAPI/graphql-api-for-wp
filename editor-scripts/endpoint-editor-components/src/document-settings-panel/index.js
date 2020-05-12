/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
const { PluginDocumentSettingPanel } = wp.editPost;

/**
 * Internal dependencies
 */
import { MarkdownGuideButton } from '../../../../packages/components/src';
import { getMarkdownContentOrUseDefault } from '../markdown-loader';

/**
 * Name of the Settings Panel
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'endpoint-document-settings-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Welcome Guide', 'graphql-api') }
    >
        <MarkdownGuideButton
            pageFilenames={ [
                'welcome-guide',
                'schema-config-options',
                'lorem-ipsum',
            ] }
            contentLabel={ __('Endpoint guide', 'graphql-api') } 
            buttonLabel={ __('→ Open Guide: “Creating Custom Endpoints”', 'graphql-api') }
            getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
        />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };