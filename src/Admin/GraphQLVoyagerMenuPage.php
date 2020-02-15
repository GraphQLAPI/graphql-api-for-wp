<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;
use Leoloso\GraphQLByPoPWPPlugin\Admin\EnqueueReactMenuPageTrait;
use Leoloso\GraphQLByPoPWPPlugin\General\EndpointHelpers;

/**
 * Voyager page
 */
class GraphQLVoyagerMenuPage extends AbstractMenuPage {

    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="voyager" class="voyager-client"><?php echo __('Loading...', 'graphql-by-pop') ?></div>
        <?php
    }

    protected function getScreenID(): ?string
    {
        return 'graphql_by_pop_voyager';
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueAssets(): void
    {
        // CSS
        wp_enqueue_style(
            'graphql-by-pop-voyager-client',
            GRAPHQL_BY_POP_PLUGIN_URL.'assets/css/voyager-client.css',
            array(),
            GRAPHQL_BY_POP_VERSION
        );
        wp_enqueue_style(
            'graphql-by-pop-voyager',
            GRAPHQL_BY_POP_PLUGIN_URL.'assets/css/vendors/voyager.css',
            array(),
            GRAPHQL_BY_POP_VERSION
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        wp_enqueue_script(
            'graphql-by-pop-voyager',
            GRAPHQL_BY_POP_PLUGIN_URL.'assets/js/vendors/voyager.min.js',
            array('graphql-by-pop-react-dom'),
            GRAPHQL_BY_POP_VERSION,
            true
        );
        wp_enqueue_script(
            'graphql-by-pop-voyager-client',
            GRAPHQL_BY_POP_PLUGIN_URL.'assets/js/voyager-client.js',
            array('graphql-by-pop-voyager'),
            GRAPHQL_BY_POP_VERSION,
            true
        );

        // Load data into the script
        wp_localize_script(
            'graphql-by-pop-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => wp_create_nonce('wp_rest'),
                'endpoint' => EndpointHelpers::getGraphQLEndpointURL(),
            )
        );
    }
}
