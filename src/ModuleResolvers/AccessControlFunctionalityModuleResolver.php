<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoP\AccessControl\Schema\SchemaModes;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class AccessControlFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }

    public const PUBLIC_PRIVATE_SCHEMA = Plugin::NAMESPACE . '\public-private-schema';
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\access-control';
    public const ACCESS_CONTROL_RULE_DISABLE_ACCESS = Plugin::NAMESPACE . '\access-control-rule-disable-access';
    public const ACCESS_CONTROL_RULE_USER_STATE = Plugin::NAMESPACE . '\access-control-rule-user-state';
    public const ACCESS_CONTROL_RULE_USER_ROLES = Plugin::NAMESPACE . '\access-control-rule-user-roles';
    public const ACCESS_CONTROL_RULE_USER_CAPABILITIES = Plugin::NAMESPACE . '\access-control-rule-user-capabilities';

    /**
     * Setting options
     */
    public const OPTION_MODE = 'mode';
    public const OPTION_ENABLE_GRANULAR = 'granular';

    public static function getModulesToResolve(): array
    {
        return [
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            self::ACCESS_CONTROL_RULE_USER_STATE,
            self::ACCESS_CONTROL_RULE_USER_ROLES,
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
            self::PUBLIC_PRIVATE_SCHEMA,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleSubtype(string $module): ?string
    {
        return 'accesscontrol';
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::ACCESS_CONTROL:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::PUBLIC_PRIVATE_SCHEMA:
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
            case self::ACCESS_CONTROL_RULE_USER_STATE:
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema', 'graphql-api'),
            self::ACCESS_CONTROL => \__('Access Control', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS => \__('Access Control Rule: Disable Access', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_STATE => \__('Access Control Rule: User State', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_ROLES => \__('Access Control Rule: User Roles', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES => \__('Access Control Rule: User Capabilities', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::PUBLIC_PRIVATE_SCHEMA:
                return \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'graphql-api');
            case self::ACCESS_CONTROL:
                return \__('Set-up rules to define who can access the different fields and directives from a schema', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
                return \__('Remove access to the fields and directives', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_STATE:
                return \__('Allow or reject access to the fields and directives based on the user being logged-in or not', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
                return \__('Allow or reject access to the fields and directives based on the user having a certain role', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return \__('Allow or reject access to the fields and directives based on the user having a certain capability', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool
    {
        switch ($module) {
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
            case self::ACCESS_CONTROL_RULE_USER_STATE:
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return false;
        }
        return $this->upstreamHasDocumentation($module);
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
            self::PUBLIC_PRIVATE_SCHEMA => [
                self::OPTION_MODE => SchemaModes::PUBLIC_SCHEMA_MODE,
                self::OPTION_ENABLE_GRANULAR => true,
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
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::PUBLIC_PRIVATE_SCHEMA) {
            $whereModules = [
                SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                self::ACCESS_CONTROL,
            ];
            $whereModuleNames = array_map(
                function ($whereModule) use ($moduleRegistry) {
                    return '▹ ' . $moduleRegistry->getModuleResolver($whereModule)->getName($whereModule);
                },
                $whereModules
            );
            $option = self::OPTION_MODE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default visibility', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Visibility to use for fields and directives in the schema when option <code>"%s"</code> is selected (in %s)', 'graphql-api'),
                    ComponentConfiguration::getSettingsValueLabel(),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModuleNames
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                    SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
                ],
            ];
            $option = self::OPTION_ENABLE_GRANULAR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable granular control?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Enable to select the visibility for a set of fields/directives when editing the Access Control List', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
