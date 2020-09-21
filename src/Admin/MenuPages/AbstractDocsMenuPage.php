<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;

/**
 * Docs menu page
 */
abstract class AbstractDocsMenuPage extends AbstractMenuPage
{
    use GraphQLAPIMenuPageTrait;

    public function print(): void
    {
        ?>
        <div
            class="modal-window-content-wrapper"
        >
            <?php echo $this->getContentToPrint() ?>
        </div>
        <?php
    }

    abstract protected function getContentToPrint(): string;

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        /**
         * Hide the menus
         */
        \wp_enqueue_style(
            'graphql-api-hide-admin-bar',
            \GRAPHQL_API_URL . 'assets/css/hide-admin-bar.css',
            array(),
            \GRAPHQL_API_VERSION
        );
        /**
         * Styles for content within the modal window
         */
        \wp_enqueue_style(
            'graphql-api-modal-window-content',
            \GRAPHQL_API_URL . 'assets/css/modal-window-content.css',
            array(),
            \GRAPHQL_API_VERSION
        );

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-tabpanel',
            \GRAPHQL_API_URL . 'assets/css/tabpanel.css',
            array(),
            \GRAPHQL_API_VERSION
        );
        \wp_enqueue_script(
            'graphql-api-tabpanel',
            \GRAPHQL_API_URL . 'assets/js/tabpanel.js',
            array('jquery'),
            \GRAPHQL_API_VERSION
        );
    }
}
