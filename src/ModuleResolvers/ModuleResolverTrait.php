<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\General\LocaleUtils;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;

    /**
     * Where the markdown file localized to the user's language is stored
     *
     * @param string $module
     * @return string
     */
    public function getLocalizedMarkdownFileDir(string $module): string
    {
        return $this->getMarkdownFileDir($module, LocaleUtils::getLocaleLanguage());
    }

    /**
     * Where the default markdown file (for if the localized language is not available) is stored
     * Default language for documentation: English
     *
     * @param string $module
     * @return string
     */
    public function getDefaultMarkdownFileDir(string $module): string
    {
        return $this->getMarkdownFileDir(
            $module,
            $this->getDefaultDocumentationLanguage()
        );
    }

    /**
     * Default language for documentation: English
     *
     * @param string $module
     * @return string
     */
    public function getDefaultDocumentationLanguage(): string
    {
        return 'en';
    }

    /**
     * Undocumented function
     *
     * @param string $module
     * @param string $lang
     * @return string
     */
    protected function getMarkdownFileDir(string $module, string $lang): string
    {
        return constant('GRAPHQL_API_DIR') . "/docs/${lang}/modules";
    }

    /**
     * Path URL to append to the local images referenced in the markdown file
     *
     * @param string $module
     * @return string|null
     */
    protected function getDefaultMarkdownFileURL(string $module): string
    {
        $lang = $this->getDefaultDocumentationLanguage();
        return constant('GRAPHQL_API_URL') . "docs/${lang}/modules";
    }
}
