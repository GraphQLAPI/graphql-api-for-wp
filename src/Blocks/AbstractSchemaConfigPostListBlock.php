<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

abstract class AbstractSchemaConfigPostListBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function isDynamicBlock(): bool
    {
        return true;
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
            <p><strong>%s</strong></p>
            %s
        </div>
EOF;
        $postContentElems = [];
        if ($postListIDs = $attributes[$this->getAttributeName()]) {
            $postObjects = \get_posts([
                'include' => $postListIDs,
                'posts_per_page' => -1,
                'post_type' => $this->getPostType(),
            ]);
            foreach ($postObjects as $postObject) {
                $postContentElems[] = \sprintf(
                    '<code><a href="%s">%s</a></code>%s',
                    \get_permalink($postObject->ID),
                    $postObject->post_title,
                    $postObject->post_excerpt ?
                        '<br/><small>' . $postObject->post_excerpt . '</small>'
                        : ''
                );
            }
        }
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName(),
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
