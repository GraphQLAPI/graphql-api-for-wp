<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\ComponentModel\Environment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Config\ServiceConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Facades\Engine\DataloadingEngineFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\EndpointSchemaConfiguratorExecuter;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\CacheFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\PersistedQuerySchemaConfiguratorExecuter;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;

    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\GenericCustomPosts\Component::class,
            \PoP\CommentMetaWP\Component::class,
            \PoP\GraphQL\Component::class,
            \PoP\MediaWP\Component::class,
            \PoP\PostsWP\Component::class,
            \PoP\PagesWP\Component::class,
            \PoP\CustomPostMediaWP\Component::class,
            \PoP\CustomPostMetaWP\Component::class,
            \PoP\TaxonomyQueryWP\Component::class,
            \PoP\PostTagsWP\Component::class,
            \PoP\UserRolesAccessControl\Component::class,
            \PoP\UserRolesWP\Component::class,
            \PoP\UserStateWP\Component::class,
            \PoP\UserMetaWP\Component::class,
            \PoP\BasicDirectives\Component::class, // Needed for if UsefulDirectives is disabled
            \PoP\UsefulDirectives\Component::class,
            \PoP\GraphQLClientsForWP\Component::class,
            \PoP\GraphQLEndpointForWP\Component::class,
        ];
    }

    /**
     * Initialize services
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::initYAMLServices(dirname(__DIR__));
        self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema);
        // Register the Cache services, if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(CacheFunctionalityModuleResolver::CONFIGURATION_CACHE)) {
            self::initYAMLServices(dirname(__DIR__), '', 'cache-services.yaml');
        }
        self::initComponentConfiguration();
        ServiceConfiguration::initialize();
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
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize classes
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers', false);
        ContainerBuilderUtils::registerModuleResolversFromNamespace(__NAMESPACE__ . '\\ModuleResolvers');
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        // Enable the CacheControl, if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::CACHE_CONTROL)) {
            // Unless previewing the query
            if (!\is_preview()) {
                $dataloadingEngine = DataloadingEngineFacade::getInstance();
                $dataloadingEngine->addMandatoryDirectives([
                    CacheControlDirectiveResolver::getDirectiveName(),
                ]);
            }
        }

        // Configure the GraphQL query with Access/Cache Control Lists
        (new PersistedQuerySchemaConfiguratorExecuter())->init();
        (new EndpointSchemaConfiguratorExecuter())->init();
    }
}
