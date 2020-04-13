<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\QueryExecution\AccessControlGraphQLQueryConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\QueryExecution\CacheControlGraphQLQueryConfigurator;
use PoP\ComponentModel\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Environment;
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
    }

    protected static function initComponentConfiguration(): void
    {
        /**
         * Enable the schema entity registries, as to retrieve the type/directive resolver classes
         * from the type/directive names, saved in the DB in the ACL/CCL Custom Post Types
         */
        $hookName = AbstractComponentConfiguration::getHookName(
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
        // Attach the Extensions with a higher priority, so it executes first
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers\\Extensions', false, 100);
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
        (new AccessControlGraphQLQueryConfigurator())->init();
        (new CacheControlGraphQLQueryConfigurator())->init();
    }
}
