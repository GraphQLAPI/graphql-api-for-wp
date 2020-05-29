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
     * Path URL to append to the local images referenced in the markdown file
     *
     * @param string $module
     * @return string|null
     */
    protected function getImagePathURL(string $module): ?string
    {
        return null;
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
            $htmlContents = (new Parsedown())->text($markdownContents);
            // Add the path to the images
            if ($imagePathURL = $this->getImagePathURL($module)) {
                $htmlContents = $this->appendPathURLToImages($imagePathURL, $htmlContents);
            }
            return $htmlContents;
        }
        return null;
    }

    /**
     * Convert relative paths to absolute paths for image URLs
     *
     * @param string $imagePathURL
     * @param string $htmlContents
     * @return string
     */
    protected function appendPathURLToImages(string $imagePathURL, string $htmlContents): string
    {
        return preg_replace_callback(
            '/<img.*src="(.*?)".*?>/',
            function ($matches) use ($imagePathURL) {
                // If the image has an absolute route, then no need
                if (
                    substr($matches[1], 0, strlen('http://')) == 'http://'
                    || substr($matches[1], 0, strlen('https://')) == 'https://'
                ) {
                    return $matches[0];
                }
                $imageURL = \trailingslashit($imagePathURL) . $matches[1];
                return str_replace(
                    "src=\"{$matches[1]}\"",
                    "src=\"{$imageURL}\"",
                    $matches[0]
                );
            },
            $htmlContents
        );
    }
}
