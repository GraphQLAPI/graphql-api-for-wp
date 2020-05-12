<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EditorScripts;

use Leoloso\GraphQLByPoPWPPlugin\General\EditorHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Scripts\AbstractScript;

/**
 * Base class for a Gutenberg script.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/editor-scripts/{scriptName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 */
abstract class AbstractEditorScript extends AbstractScript
{
    use HasDocumentationScriptTrait;

    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            $this->getDocsLocalizedData()
        );
    }

    /**
     * URL to the script
     *
     * @return string
     */
    protected function getScriptDirURL(): string
    {
        return $this->getPluginURL() . '/editor-scripts/' . $this->getScriptName() . '/';
    }

    /**
     * Where is the script stored
     *
     * @return string
     */
    protected function getScriptDir(): string
    {
        return $this->getPluginDir() . '/editor-scripts/' . $this->getScriptName();
    }

    /**
     * Post types for which to register the script
     *
     * @return array
     */
    protected function getAllowedPostTypes(): array
    {
        return [];
    }

    /**
     * Registers all script assets
     */
    public function initScript(): void
    {
        /**
         * Maybe only load the script when creating/editing for some CPT only
         */
        if (\is_admin()) {
            if ($postTypes = $this->getAllowedPostTypes()) {
                if (!in_array(EditorHelpers::getEditingPostType(), $postTypes)) {
                    return;
                }
            }
        }

        parent::initScript();

        /**
         * Register the documentation (from under folder "docs/"), for the locale and the default language
         * IMPORTANT: Uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
         */
        // $this->registerDocumentationScripts();
    }

    /**
     * Register the documentation (from under folder "docs/"), for the locale and the default language
     */
    public function registerDocumentationScripts(): void
    {
        $dir = $this->getScriptDir();
        $scriptName = $this->getScriptName();
        $script_asset_path = "$dir/build/index.asset.php";
        $url = $this->getScriptDirURL();
        $script_asset = require($script_asset_path);

        if ($defaultLang = $this->getDefaultLanguage()) {
            \wp_register_script(
                $scriptName . '-' . $defaultLang,
                $url . 'build/docs-' . $defaultLang . '.js',
                array_merge(
                    $script_asset['dependencies'],
                    $this->getScriptDependencies()
                ),
                $script_asset['version']
            );
            \wp_enqueue_script($scriptName . '-' . $defaultLang);
        }
        if ($this->addLocalLanguage()) {
            $localeLang = $this->getLocaleLanguage();
            // Check the current locale has been translated, otherwise if will try to load an unexisting file
            // If the locale lang is the same as the default lang, the file has already been loaded
            if ($localeLang != $defaultLang && in_array($localeLang, $this->getDocLanguages())) {
                \wp_register_script(
                    $scriptName . '-' . $localeLang,
                    $url . 'build/docs-' . $localeLang . '.js',
                    array_merge(
                        $script_asset['dependencies'],
                        $this->getScriptDependencies()
                    ),
                    $script_asset['version']
                );
                \wp_enqueue_script($scriptName . '-' . $localeLang);
            }
        }
    }
}
