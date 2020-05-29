<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\General\RequestParams;

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
        $module = urldecode($vars[RequestParams::MODULE]);
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        if (is_null($moduleResolver)) {
            _e(sprintf(
                \__('Ops, module \'%s\' does not exist', 'graphql-api'),
                $module
            ));
            return;
        }
        if (!$moduleResolver->hasDocumentation($module)) {
            _e(sprintf(
                \__('Ops, module \'%s\' has no documentation', 'graphql-api'),
                $moduleResolver->getName($module)
            ));
            return;
        }
        $documentation = $moduleResolver->getDocumentation($module);
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
            <?php echo $documentation ?>
        </div>
        <?php
    }
}
