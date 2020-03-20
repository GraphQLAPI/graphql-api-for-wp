<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\QueryExecution\AccessControlGraphQLQueryConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\QueryExecution\CacheControlGraphQLQueryConfigurator;

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
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__.'\\FieldResolvers\\Extensions', false, 100);
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
