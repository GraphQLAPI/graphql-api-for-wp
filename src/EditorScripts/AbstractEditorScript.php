<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EditorScripts;

use Error;
use Leoloso\GraphQLByPoPWPPlugin\General\GeneralUtils;
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
    public const LOCALE_LANG = 'localeLang';
    public const DEFAULT_LANG = 'defaultLang';

    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocalizedData(): array
    {
        $data = parent::getLocalizedData();
        // Add the locale language?
        if ($this->addLocalLanguage()) {
            $data[self::LOCALE_LANG] = $this->getLocaleLanguage();
        }
        // Add the default language?
        if ($defaultLang = $this->getDefaultLanguage()) {
            $data[self::DEFAULT_LANG] = $defaultLang;
        }
        return $data;
    }

    /**
     * Add the locale language to the localized data?
     *
     * @return bool
     */
    protected function addLocalLanguage(): bool
    {
        return false;
    }
    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocaleLanguage(): string
    {
        // locale has shape "en_US". Retrieve the language code only: "en"
        $localeParts = explode('_', \get_locale());
        return $localeParts[0];
    }
    /**
     * Default language for the script/component's documentation
     *
     * @return array
     */
    protected function getDefaultLanguage(): ?string
    {
        return null;
    }

    /**
     * In what languages is the documentation available
     *
     * @return array
     */
    protected function getDocLanguages(): array
    {
        $langs = [];
        if ($defaultLang = $this->getDefaultLanguage()) {
            $langs[] = $defaultLang;
        }
        return $langs;
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
         */
        // ---------------------------------------------
        // IMPORTANT: Uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
        // if ($defaultLang = $this->getDefaultLanguage()) {
        //     \wp_register_script(
        //         $scriptName . '-' . $defaultLang,
        //         $url . 'build/docs-' . $defaultLang . '.js',
        //         array_merge(
        //             $script_asset['dependencies'],
        //             $this->getScriptDependencies()
        //         ),
        //         $script_asset['version']
        //     );
        //     \wp_enqueue_script($scriptName . '-' . $defaultLang);
        // }
        // if ($this->addLocalLanguage()) {
        //     $localeLang = $this->getLocaleLanguage();
        //     // Check the current locale has been translated, otherwise if will try to load an unexisting file
        //     // If the locale lang is the same as the default lang, the file has already been loaded
        //     if ($localeLang != $defaultLang && in_array($localeLang, $this->getDocLanguages())) {
        //         \wp_register_script(
        //             $scriptName . '-' . $localeLang,
        //             $url . 'build/docs-' . $localeLang . '.js',
        //             array_merge(
        //                 $script_asset['dependencies'],
        //                 $this->getScriptDependencies()
        //             ),
        //             $script_asset['version']
        //         );
        //         \wp_enqueue_script($scriptName . '-' . $localeLang);
        //     }
        // }
        // ---------------------------------------------
    }
}
