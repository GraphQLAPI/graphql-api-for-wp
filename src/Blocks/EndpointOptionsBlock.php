<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\EndpointBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;

/**
 * Endpoint Options block
 */
class EndpointOptionsBlock extends AbstractQueryExecutionOptionsBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED = 'isGraphiQLEnabled';
    public const ATTRIBUTE_NAME_IS_VOYAGER_ENABLED = 'isVoyagerEnabled';

    protected function getBlockName(): string
    {
        return 'endpoint-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new EndpointBlockCategory();
    }

    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);

        $labels = $this->getBooleanLabels();
        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Expose GraphiQL client?', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED] ?? true]
        );
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Expose the Interactive Schema client?', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED] ?? true]
        );

        return $blockContent;
    }
}
