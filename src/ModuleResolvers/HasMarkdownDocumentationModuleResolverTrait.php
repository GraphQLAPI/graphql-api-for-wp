<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use Parsedown;

trait HasMarkdownDocumentationModuleResolverTrait
{
    /**
     * The name of the Markdown filename.
     * By default, it's the same as the slug
     *
     * @param string $module
     * @return string
     */
    public function getMarkdownFilename(string $module): ?string
    {
        return $this->getSlug($module) . '.md';
    }

    /**
     * Where the markdown file is stored
     *
     * @param string $module
     * @return string
     */
    abstract function getMarkdownFileDir(string $module): string;

    /**
     * Does the module have HTML Documentation?
     *
     * @param string $module
     * @return bool
     */
    public function hasDocumentation(string $module): bool
    {
        return !empty($this->getMarkdownFilename($module));
    }

    /**
     * HTML Documentation for the module
     *
     * @param string $module
     * @return string|null
     */
    public function getDocumentation(string $module): ?string
    {
        if ($markdownFilename = $this->getMarkdownFilename($module)) {
            $markdownFile = \trailingslashit($this->getMarkdownFileDir($module)) . $markdownFilename;
            $markdownContents = file_get_contents($markdownFile);
            return (new Parsedown())->text($markdownContents);
        }
        return null;
    }
}
