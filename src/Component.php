<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\ComponentModel\Environment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\Config\ServiceConfiguration;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfiguratorExecuters\EndpointSchemaConfiguratorExecuter;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfiguratorExecuters\PersistedQuerySchemaConfiguratorExecuter;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    
    // const VERSION = '0.1.0';

    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();
        self::initYAMLServices(dirname(__DIR__));
        self::initComponentConfiguration();
        ServiceConfiguration::init();
    }

    protected static function initComponentConfiguration(): void
    {
        /**
         * Enable the schema entity registries, as to retrieve the type/directive resolver classes
         * from the type/directive names, saved in the DB in the ACL/CCL Custom Post Types
         */
        $hookName = ComponentConfigurationHelpers::getHookName(
            ComponentConfiguration::class,
            Environment::ENABLE_SCHEMA_ENTITY_REGISTRIES
        );
        \add_filter(
            $hookName,
            function ($value) {
                return true;
            },
            PHP_INT_MAX,
            1
        );
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot()
    {
        parent::beforeBoot();
        
        // Initialize classes
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers', false);
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        
        // Configure the GraphQL query with Access/Cache Control Lists
        (new PersistedQuerySchemaConfiguratorExecuter())->init();
        (new EndpointSchemaConfiguratorExecuter())->init();
    }
}
