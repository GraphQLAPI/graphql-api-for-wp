<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;

/**
 * Module Documentation menu page
 */
class ModuleDocumentationMenuPage extends AbstractMenuPage
{
    public function print(): void
    {
        // This is crazy: passing ?module=Foo\Bar\module,
        // and then doing $_GET['module'], returns "Foo\\Bar\\module"
        // So parse the URL to extract the "module" param
        $vars = [];
        parse_str($_SERVER['REQUEST_URI'], $vars);
        $module = urldecode($vars['module']);
        /**
         * Hide the menus
         */
        ?>
        <style>
            #adminmenumain,
            #wpadminbar {
                display: none;
            }
            html.wp-toolbar {
                padding-top: 0;
            }
        </style>
        <div
            id="graphql-api-module-docs"
            class="wrap"
        >
            <h1><?php \_e('GraphQL API — Module Documentation', 'graphql-api'); ?></h1>
            <p><?php echo $module ?></p>
        </div>
        <?php
    }
}
