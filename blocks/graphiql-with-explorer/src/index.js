/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import GraphiQLWithExplorer from './GraphiQLWithExplorer.js';

/**
 * Use the settings from the original block, just overriding the edit function
 */
import { blockTypeSettings } from '../../graphiql/src/block-type-settings';

let explorerBlockTypeSettings = blockTypeSettings;
explorerBlockTypeSettings.edit = GraphiQLWithExplorer;

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( 'graphql-api/graphiql', explorerBlockTypeSettings);
