<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

trait EnqueueReactMenuPageTrait
{

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        \wp_enqueue_script(
            'graphql-api-react',
            \GRAPHQL_BY_POP_PLUGIN_URL . 'assets/js/vendors/react.min.js',
            array(),
            \GRAPHQL_BY_POP_VERSION,
            $addInFooter
        );
        \wp_enqueue_script(
            'graphql-api-react-dom',
            \GRAPHQL_BY_POP_PLUGIN_URL . 'assets/js/vendors/react-dom.min.js',
            array('graphql-api-react'),
            \GRAPHQL_BY_POP_VERSION,
            $addInFooter
        );
    }
}
