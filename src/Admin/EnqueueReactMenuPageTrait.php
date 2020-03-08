<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

trait EnqueueReactMenuPageTrait {

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        wp_enqueue_script(
            'graphql-by-pop-react',
            \GRAPHQL_BY_POP_PLUGIN_URL.'assets/js/vendors/react.min.js',
            array(),
            \GRAPHQL_BY_POP_VERSION,
            $addInFooter
        );
        wp_enqueue_script(
            'graphql-by-pop-react-dom',
            \GRAPHQL_BY_POP_PLUGIN_URL.'assets/js/vendors/react-dom.min.js',
            array('graphql-by-pop-react'),
            \GRAPHQL_BY_POP_VERSION,
            $addInFooter
        );
    }
}
