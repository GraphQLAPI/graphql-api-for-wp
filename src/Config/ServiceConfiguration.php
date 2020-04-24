<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoP\AccessControl\Schema\SchemaModes;

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
        if ($capabilities = ['manage_options']) {
            ContainerBuilderUtils::injectValuesIntoService(
                'access_control_manager',
                'addEntriesForFields',
                UserRolesAccessControlGroups::CAPABILITIES,
                [
                    [RootTypeResolver::class, 'accessControlLists', $capabilities, SchemaModes::PRIVATE_SCHEMA_MODE],
                    [RootTypeResolver::class, 'cacheControlLists', $capabilities, SchemaModes::PRIVATE_SCHEMA_MODE],
                    [RootTypeResolver::class, 'fieldDeprecationLists', $capabilities, SchemaModes::PRIVATE_SCHEMA_MODE],
                ]
            );
        }
    }
}
