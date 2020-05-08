<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\General\BlockRenderingHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\SchemaConfigurationBlockCategory;

abstract class AbstractSchemaConfigPostListBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new SchemaConfigurationBlockCategory();
    }

    abstract protected function getAttributeName(): string;

    abstract protected function getPostType(): string;

    abstract protected function getHeader(): string;

    public function renderBlock(array $attributes, string $content): string
    {
        /**
         * Print the list of all the contained Access Control blocks
         */
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            <h3 class="%s">%s</strong></h3>
            %s
        </div>
EOF;
        $postContentElems = $foundPostListIDs = [];
        if ($postListIDs = $attributes[$this->getAttributeName()]) {
            $postObjects = \get_posts([
                'include' => $postListIDs,
                'posts_per_page' => -1,
                'post_type' => $this->getPostType(),
                'post_status' => [
                    'publish',
                    'draft',
                    'pending',
                ],
            ]);
            foreach ($postObjects as $postObject) {
                $foundPostListIDs[] = $postObject->ID;
                $postContentElems[] = \sprintf(
                    '<code><a href="%s">%s</a></code>%s',
                    \get_permalink($postObject->ID),
                    BlockRenderingHelpers::getCustomPostTitle($postObject),
                    $postObject->post_excerpt ?
                        '<br/><small>' . $postObject->post_excerpt . '</small>'
                        : ''
                );
            }
            // If any ID was not retrieved as an object, it is a deleted post
            $notFoundPostListIDs = array_diff(
                $postListIDs,
                $foundPostListIDs
            );
            foreach ($notFoundPostListIDs as $notFoundPostID) {
                $postContentElems[] = \sprintf(
                    '<code>%s</code>',
                    \sprintf(
                        \__('Undefined item with ID %s', 'graphql-api'),
                        $notFoundPostID
                    )
                );
            }
        }
        $className = $this->getBlockClassName();
        return sprintf(
            $blockContentPlaceholder,
            $className,
            $className . '-front',
            $this->getHeader(),
            $postContentElems ?
                sprintf(
                    '<ul><li>%s</li></ul>',
                    implode('</li><li>', $postContentElems)
                )
                : sprintf(
                    '<em>%s</em>',
                    \__('(not set)', 'graphql-api')
                )
        );
    }
}
