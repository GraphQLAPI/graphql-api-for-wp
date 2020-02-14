<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;

/**
 * GraphiQL page
 */
class GraphiQLPage extends AbstractMenuPage {

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client">Loading...</div>
        <?php
    }

    public function init(): void
    {

    }
}
