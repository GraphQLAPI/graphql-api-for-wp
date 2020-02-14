<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;

/**
 * GraphiQL page
 */
class GraphQLVoyagerMenuPage extends AbstractMenuPage {

    public function print(): void
    {
        ?>
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
    }
}
