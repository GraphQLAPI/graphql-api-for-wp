<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\General\RequestParams;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use InvalidArgumentException;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Module Documentation menu page
 */
class ModuleDocumentationMenuPage extends AbstractDocsMenuPage
{
    public function getMenuPageSlug(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var ModulesMenuPage
         */
        $modulesMenuPage = $instanceManager->getInstance(ModulesMenuPage::class);
        return $modulesMenuPage->getMenuPageSlug();
    }

    protected function getContentToPrint(): string
    {
        // This is crazy: passing ?module=Foo\Bar\module,
        // and then doing $_GET['module'], returns "Foo\\Bar\\module"
        // So parse the URL to extract the "module" param
        $vars = [];
        parse_str($_SERVER['REQUEST_URI'], $vars);
        $module = urldecode($vars[RequestParams::MODULE]);
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        try {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
        } catch (InvalidArgumentException $e) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, module \'%s\' does not exist', 'graphql-api'),
                    $module
                )
            );
        }
        if (!$moduleResolver->hasDocumentation($module)) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, module \'%s\' has no documentation', 'graphql-api'),
                    $moduleResolver->getName($module)
                )
            );
        }
        return $moduleResolver->getDocumentation($module);
    }
}
