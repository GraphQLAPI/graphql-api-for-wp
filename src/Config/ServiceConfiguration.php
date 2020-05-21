<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\Security\UserAuthorization;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\Overrides\GraphiQLWithExplorerBlock;
use PoP\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

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
        if (ComponentConfiguration::useGraphiQLWithExplorer()) {
            ContainerBuilderUtils::injectValuesIntoService(
                'instance_manager',
                'overrideClass',
                GraphiQLBlock::class,
                GraphiQLWithExplorerBlock::class
            );
        }
    }
}
