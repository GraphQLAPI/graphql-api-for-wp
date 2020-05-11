<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EditorScripts;

use Error;
use Leoloso\GraphQLByPoPWPPlugin\General\GeneralUtils;

/**
 * Base class for a Gutenberg script.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/editor-scripts/{scriptName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 */
abstract class AbstractEditorScript
{
    public const LOCALE_LANG = 'localeLang';
    public const DEFAULT_LANG = 'defaultLang';

    /**
     * Execute this function to initialize the block
     *
     * @return void
     */
    public function init(): void
    {
        \add_action('init', [$this, 'initScript']);
    }

    /**
     * Plugin dir
     *
     * @return string
     */
    abstract protected function getPluginDir(): string;
    /**
     * Plugin URL
     *
     * @return string
     */
    abstract protected function getPluginURL(): string;
    /**
     * Block name
     *
     * @return string
     */
    abstract protected function getScriptName(): string;

    /**
     * Register CSS
     *
     * @return bool
     */
    protected function registerScriptCSS(): bool
    {
        return false;
    }

    /**
     * Script localization name
     *
     * @return string
     */
    final protected function getScriptLocalizationName(): string
    {
        return GeneralUtils::dashesToCamelCase($this->getScriptName());
    }
    
    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocalizedData(): array
    {
        $data = [];
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
     * Dependencies to load before the script
     *
     * @return array
     */
    protected function getScriptDependencies(): array
    {
        return [];
    }

    /**
     * Dependencies to load before the style
     *
     * @return array
     */
    protected function getStyleDependencies(): array
    {
        return [];
    }

    /**
     * Registers all script assets
     */
    public function initScript(): void
    {
        $dir = $this->getScriptDir();
        $scriptName = $this->getScriptName();

        $script_asset_path = "$dir/build/index.asset.php";
        if (!file_exists($script_asset_path)) {
            throw new Error(
                sprintf(
                    \__('You need to run `npm start` or `npm run build` for the "%s" script first.', 'graphql-api'),
                    $scriptName
                )
            );
        }

        $url = $this->getScriptDirURL();
        
        // Load the block scripts and styles
        $index_js     = 'build/index.js';
        $script_asset = require($script_asset_path);
        \wp_register_script(
            $scriptName,
            $url . $index_js,
            array_merge(
                $script_asset['dependencies'],
                $this->getScriptDependencies()
            ),
            $script_asset['version']
        );
        \wp_enqueue_script($scriptName);

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

        /**
         * Register CSS file
         */
        if ($this->registerScriptCSS()) {
            $editor_css = 'style.css';
            \wp_register_style(
                $scriptName,
                $url . $editor_css,
                $this->getStyleDependencies(),
                filemtime("$dir/$editor_css")
            );
            \wp_enqueue_style($scriptName);
        }

        /**
         * Localize the script with custom data
         * Execute on hook "admin_enqueue_scripts" and not now,
         * because `getLocalizedData` might call EndpointHelpers::getAdminGraphQLEndpoint(),
         * which calls ScriptModelScriptConfiguration::namespaceTypesAndInterfaces(),
         * which is initialized during "wp"
         */
        \add_action('admin_enqueue_scripts', function () use ($scriptName) {
            if ($localizedData = $this->getLocalizedData()) {
                \wp_localize_script(
                    $scriptName,
                    $this->getScriptLocalizationName(),
                    $localizedData
                );
            }
        });
    }
}
