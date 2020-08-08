<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use GraphQLAPI\GraphQLAPI\Blocks\GraphiQLBlock;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use GraphQLAPI\GraphQLAPI\Blocks\Overrides\GraphiQLWithExplorerBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    /**
     * Validate that only the right users can access private fields
     *
     * @return void
     */
    protected static function configure()
    {
        self::configureAccessControl();
        self::configureOverridingBlocks();
    }

    /**
     * Validate that only the right users can access private fields
     *
     * @return void
     */
    protected static function configureAccessControl()
    {
        $schemaEditorAccessCapability = UserAuthorization::getSchemaEditorAccessCapability();
        $capabilities = [$schemaEditorAccessCapability];
        ContainerBuilderUtils::injectValuesIntoService(
            'access_control_manager',
            'addEntriesForFields',
            UserRolesAccessControlGroups::CAPABILITIES,
            [
                [RootTypeResolver::class, 'accessControlLists', $capabilities],
                [RootTypeResolver::class, 'cacheControlLists', $capabilities],
                [RootTypeResolver::class, 'fieldDeprecationLists', $capabilities],
            ]
        );
    }

    /**
     * Maybe override blocks
     *
     * @return void
     */
    protected static function configureOverridingBlocks()
    {
        // Maybe use GraphiQL with Explorer
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER)) {
            ContainerBuilderUtils::injectValuesIntoService(
                'instance_manager',
                'overrideClass',
                GraphiQLBlock::class,
                GraphiQLWithExplorerBlock::class
            );
        }
    }
}
