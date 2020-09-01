<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolver;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\schema-editing-access';

    /**
     * Setting options
     */
    public const OPTION_EDITING_ACCESS_SCHEME = 'editing-access-scheme';

    public static function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::PLUGIN_MANAGEMENT;
    }

    // public function canBeDisabled(string $module): bool
    // {
    //     switch ($module) {
    //         case self::SCHEMA_EDITING_ACCESS:
    //             return false;
    //     }
    //     return parent::canBeDisabled($module);
    // }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_EDITING_ACCESS:
                return \__('Grant access to users other than admins to edit the GraphQL schema', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     *
     * @param string $module
     * @param string $option
     * @return mixed Anything the setting might be: an array|string|bool|int|null
     */
    public function getSettingsDefaultValue(string $module, string $option)
    {
        $defaultValues = [
            self::SCHEMA_EDITING_ACCESS => [
                self::OPTION_EDITING_ACCESS_SCHEME => UserAuthorization::ACCESS_SCHEME_ADMIN_ONLY,
            ],
        ];
        return $defaultValues[$module][$option];
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @param string $module
     * @return array
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::SCHEMA_EDITING_ACCESS) {
            /**
             * Write Access Scheme
             * If `"admin"`, only the admin can compose a GraphQL query and endpoint
             * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
             * but not publish the query, Editor role can publish it, etc)
             */
            $option = self::OPTION_EDITING_ACCESS_SCHEME;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Editing Access Scheme', 'graphql-api'),
                Properties::DESCRIPTION => \__('Scheme to decide which users can edit the schema (Persisted Queries, Custom Endpoints and related post types) and with what permissions', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    UserAuthorization::ACCESS_SCHEME_ADMIN_ONLY => \__('Admin user(s) only', 'graphql-api'),
                    UserAuthorization::ACCESS_SCHEME_POST => \__('Use same access workflow as for editing posts', 'graphql-api'),
                ],
            ];
        }
        return $moduleSettings;
    }
}
