<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\General\EndpointHelpers;
use Leoloso\GraphQLByPoPWPPlugin\General\URLParamHelpers;

/**
 * GraphiQL block
 */
class GraphiQLBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_QUERY = 'query';
    public const ATTRIBUTE_NAME_VARIABLES = 'variables';

    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'graphiql';
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
                'endpoint' => EndpointHelpers::getGraphQLEndpoint(),
            ]
        );
    }

    public function renderBlock(array $attributes, string $content): string
    {
        $content = sprintf(
            '<div class="%s">',
            $this->getBlockClassName() . ' ' . $this->getAlignClass()
        );
        $query = $attributes[self::ATTRIBUTE_NAME_QUERY];
        $variables = $attributes[self::ATTRIBUTE_NAME_VARIABLES];
        if ($query) {
            if (true) {
                $url = 'http://playground.localhost:8888/graphiql/';
                // We need to reproduce the `encodeURIComponent` JavaScript function, because that's how the GraphiQL client adds the parameters to the URL
                // Important! We can't use function `add_query_arg` because it re-encodes the URL!
                // So build the URL manually
                $url .= (strpos($url, '?') === false ? '?' : '&') . 'query=' . URLParamHelpers::encodeURIComponent($query);
                // Add variables parameter always (empty if no variables defined), so that GraphiQL doesn't use a cached one
                $url .= '&variables=' . ($variables ? URLParamHelpers::encodeURIComponent($variables) : '');
                $content .= sprintf(
                    '<p class="alignright"><a href="%s">%s</a></p>',
                    $url,
                    __('View query in GraphiQL', 'graphql-api')
                );
            }
            $content .= sprintf(
                '<p><strong>%s</strong></p>',
                __('GraphQL Query:', 'graphql-api')
            ) . sprintf(
                '<pre><code class="prettyprint language-graphql">%s</code></pre>',
                $query
            );
        }
        if ($variables) {
            $content .= sprintf(
                '<p><strong>%s</strong></p>',
                __('Variables:', 'graphql-api')
            ) . sprintf(
                '<pre><code class="prettyprint language-json">%s</code></pre>',
                $variables
            );
        }
        $content .= '</div>';
        return $content;
    }
}
