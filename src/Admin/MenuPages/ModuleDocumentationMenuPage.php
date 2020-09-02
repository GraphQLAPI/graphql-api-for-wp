<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\General\RequestParams;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Module Documentation menu page
 */
class ModuleDocumentationMenuPage extends AbstractMenuPage
{
    use GraphQLAPIMenuPageTrait;

    public function getMenuPageSlug(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var ModulesMenuPage
         */
        $modulesMenuPage = $instanceManager->getInstance(ModulesMenuPage::class);
        return $modulesMenuPage->getMenuPageSlug();
    }

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
        $title = $moduleResolver->getName($module);
        $documentation = $moduleResolver->getDocumentation($module);
        ?>
        <div
            id="graphql-api-module-docs"
            class="modal-window-content-wrapper"
        >
            <!--h1><?php echo $title ?></h1-->
            <?php echo $documentation ?>
        </div>
        <?php
    }

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
