<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EditorScripts;

use GraphQLAPI\GraphQLAPI\General\LocaleUtils;
use GraphQLAPI\GraphQLAPI\General\DocumentationConstants;

/**
 * Add translatable documentation to the script.
 * The JS/CSS assets for each block is contained in folder {pluginDir}/editor-scripts/{scriptName}, and follows
 * the architecture from @wordpress/create-block package
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-create-block/
 */
trait HasDocumentationScriptTrait
{
    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getDocsLocalizedData(): array
    {
        $data = [];
        // Add the locale language?
        if ($this->addLocalLanguage()) {
            $data[DocumentationConstants::LOCALE_LANG] = LocaleUtils::getLocaleLanguage();
        }
        // Add the default language?
        if ($defaultLang = $this->getDefaultLanguage()) {
            $data[DocumentationConstants::DEFAULT_LANG] = $defaultLang;
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
     * Register the documentation (from under folder "docs/"), for the locale and the default language
     */
    protected function registerDocumentationScripts(
        string $scriptName,
        string $url,
        ?array $dependencies = [],
        ?string $version = null
    ): void {
        if ($defaultLang = $this->getDefaultLanguage()) {
            \wp_register_script(
                $scriptName . '-' . /** @scrutinizer ignore-type */ $defaultLang,
                $url . 'build/docs-' . /** @scrutinizer ignore-type */ $defaultLang . '.js',
                $dependencies,
                $version,
                true
            );
            \wp_enqueue_script($scriptName . '-' . $defaultLang);
        }
        if ($this->addLocalLanguage()) {
            $localeLang = LocaleUtils::getLocaleLanguage();
            // Check the current locale has been translated, otherwise if will try to load an unexisting file
            // If the locale lang is the same as the default lang, the file has already been loaded
            if ($localeLang != $defaultLang && in_array($localeLang, $this->getDocLanguages())) {
                \wp_register_script(
                    $scriptName . '-' . $localeLang,
                    $url . 'build/docs-' . $localeLang . '.js',
                    $dependencies,
                    $version,
                    true
                );
                \wp_enqueue_script($scriptName . '-' . $localeLang);
            }
        }
    }
}
