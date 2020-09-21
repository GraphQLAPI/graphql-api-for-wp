<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\General\RequestParams;
use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;

/**
 * Open documentation within the About page
 */
abstract class AbstractDocAboutMenuPage extends AbstractDocsMenuPage
{
    protected function openInModalWindow(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        $doc = $_REQUEST[RequestParams::DOC];
        $markdownContentParser = MarkdownContentParserFacade::getInstance();
        try {
            return $markdownContentParser->getContent($doc);
        } catch (InvalidArgumentException $e) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Page \'%s\' does not exist', 'graphql-api'),
                    $doc
                )
            );
        }
    }
}
