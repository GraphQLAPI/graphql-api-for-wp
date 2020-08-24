<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';

    /**
     * Setting options
     */
    public const OPTION_SCHEMA_CONFIGURATION_ID = 'schema-configuration-id';
    public const OPTION_USE_NAMESPACING = 'use-namespacing';

    /**
     * Setting option values
     */
    public const OPTION_VALUE_NO_VALUE_ID = 0;

    public static function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_NAMESPACING,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_NAMESPACING:
                return [
                    [
                        self::SCHEMA_CONFIGURATION,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
                return \__('Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'graphql-api');
            case self::SCHEMA_NAMESPACING:
                return \__('Automatically namespace types and interfaces with a vendor/project name, to avoid naming collisions', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_NAMESPACING:
                return false;
        }
        return parent::isEnabledByDefault($module);
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
            self::SCHEMA_CONFIGURATION => [
                self::OPTION_SCHEMA_CONFIGURATION_ID => self::OPTION_VALUE_NO_VALUE_ID,
            ],
            self::SCHEMA_NAMESPACING => [
                self::OPTION_USE_NAMESPACING => false,
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
        if ($module == self::SCHEMA_CONFIGURATION) {
            $whereModules = [];
            $maybeWhereModules = [
                EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
            ];
            foreach ($maybeWhereModules as $maybeWhereModule) {
                if ($moduleRegistry->isModuleEnabled($maybeWhereModule)) {
                    $whereModules[] = '▹ ' . $this->getName($maybeWhereModule);
                }
            }
            // Build all the possible values by fetching all the Schema Configuration posts
            $possibleValues = [
                self::OPTION_VALUE_NO_VALUE_ID => \__('None', 'graphql-api'),
            ];
            if ($customPosts = \get_posts([
                    'posts_per_page' => -1,
                    'post_type' => GraphQLSchemaConfigurationPostType::POST_TYPE,
                    'post_status' => 'publish',
                ])
            ) {
                foreach ($customPosts as $customPost) {
                    $possibleValues[$customPost->ID] = $customPost->post_title;
                }
            }
            $option = self::OPTION_SCHEMA_CONFIGURATION_ID;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default Schema Configuration', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Schema Configuration to use when option <code>"Default"</code> is selected (in %s)', 'graphql-api'),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModules
                    )
                ),
                Properties::TYPE => Properties::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        } elseif ($module == self::SCHEMA_NAMESPACING) {
            $option = self::OPTION_USE_NAMESPACING;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use namespacing?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Automatically namespace types and interfaces in the schema', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
