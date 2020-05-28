<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\PersistedQueryBlockCategory;

/**
 * GraphiQL block
 */
class GraphiQLBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_QUERY = 'query';
    public const ATTRIBUTE_NAME_VARIABLES = 'variables';

    protected function getBlockName(): string
    {
        return 'graphiql';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PersistedQueryBlockCategory::class);
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => EndpointHelpers::getAdminGraphQLEndpoint(),
                'defaultQuery' => $this->getDefaultQuery(),
            ]
        );
    }

    /**
     * GraphiQL default query
     *
     * @return string
     */
    protected function getDefaultQuery(): string
    {
        return \__(<<<EOT
# Welcome to GraphiQL
#
# GraphiQL is an in-browser tool for writing, validating, and
# testing GraphQL queries.
#
# Type queries into this side of the screen, and you will see intelligent
# typeaheads aware of the current GraphQL type schema and live syntax and
# validation errors highlighted within the text.
#
# GraphQL queries typically start with a "{" character. Lines that starts
# with a # are ignored.
#
# An example GraphQL query might look like:
#
#     {
#       field(arg: "value") {
#         subField
#       }
#     }
#
# Keyboard shortcuts:
#
#  Prettify Query:  Shift-Ctrl-P (or press the prettify button above)
#
#     Merge Query:  Shift-Ctrl-M (or press the merge button above)
#
#       Run Query:  Ctrl-Enter (or press the play button above)
#
#   Auto Complete:  Ctrl-Space (or just start typing)
#

EOT, 'graphql-api');
    }

    public function renderBlock(array $attributes, string $content): string
    {
        $content = sprintf(
            '<div class="%s">',
            $this->getBlockClassName() . ' ' . $this->getAlignClass()
        );
        $query = $attributes[self::ATTRIBUTE_NAME_QUERY];
        $variables = $attributes[self::ATTRIBUTE_NAME_VARIABLES];
        $content .= sprintf(
            '<p><strong>%s</strong></p>',
            \__('GraphQL Query:', 'graphql-api')
        ) . (
            $query ? sprintf(
                '<pre><code class="prettyprint language-graphql">%s</code></pre>',
                $query
            ) : sprintf(
                '<p><em>%s</em></p>',
                \__('(Not set)', 'graphql-api')
            )
        );
        if ($variables) {
            $content .= sprintf(
                '<p><strong>%s</strong></p>',
                \__('Variables:', 'graphql-api')
            ) . sprintf(
                '<pre><code class="prettyprint language-json">%s</code></pre>',
                $variables
            );
        }
        $content .= '</div>';
        return $content;
    }
}
