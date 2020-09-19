<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use GraphQLAPI\GraphQLAPI\Blocks\GraphiQLBlock;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
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
        self::overrideServiceClasses();
    }

    /**
     * Validate that only the right users can access private fields
     *
     * @return void
     */
    protected static function configureAccessControl(): void
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
    protected static function configureOverridingBlocks(): void
    {
        // Maybe use GraphiQL with Explorer
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER) && $userSettingsManager->getSetting(
            ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
            ClientFunctionalityModuleResolver::OPTION_USE_GRAPHIQL_EXPLORER_IN_ADMIN_PERSISTED_QUERIES
        )) {
            ContainerBuilderUtils::injectValuesIntoService(
                'instance_manager',
                'overrideClass',
                GraphiQLBlock::class,
                GraphiQLWithExplorerBlock::class
            );
        }
    }

    /**
     * Override service classes
     */
    protected static function overrideServiceClasses(): void
    {
        ContainerBuilderUtils::injectValuesIntoService(
            'instance_manager',
            'overrideClass',
            \GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient::class,
            \GraphQLAPI\GraphQLAPI\Clients\GraphiQLClient::class
        );
        ContainerBuilderUtils::injectValuesIntoService(
            'instance_manager',
            'overrideClass',
            \GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient::class,
            \GraphQLAPI\GraphQLAPI\Clients\GraphiQLWithExplorerClient::class
        );
    }
}
