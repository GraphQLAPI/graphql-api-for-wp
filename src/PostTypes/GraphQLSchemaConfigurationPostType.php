<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PostTypes;

use GraphQLAPI\GraphQLAPI\PostTypes\AbstractPostType;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigOptionsBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigAccessControlListBlock;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigFieldDeprecationListBlock;

class GraphQLSchemaConfigurationPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-schemaconfig';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('Schema Configuration', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Schema Configurations', 'graphql-api');
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergTemplate(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $template = [];
        // Add blocks depending on being enabled by module
        $blockClassModules = [
            SchemaConfigAccessControlListBlock::class => FunctionalityModuleResolver::ACCESS_CONTROL,
            SchemaConfigCacheControlListBlock::class => FunctionalityModuleResolver::CACHE_CONTROL,
            SchemaConfigFieldDeprecationListBlock::class => FunctionalityModuleResolver::FIELD_DEPRECATION,
        ];
        foreach ($blockClassModules as $blockClass => $module) {
            if ($moduleRegistry->isModuleEnabled($module)) {
                $block = $instanceManager->getInstance($blockClass);
                $template[] = [$block->getBlockFullName()];
            }
        }
        // Add the Configuration block always
        $schemaConfigOptionsBlock = $instanceManager->getInstance(SchemaConfigOptionsBlock::class);
        $template[] = [$schemaConfigOptionsBlock->getBlockFullName()];
        return $template;
    }

    /**
     * Indicates if to lock the Gutenberg templates
     *
     * @return boolean
     */
    protected function lockGutenbergTemplate(): bool
    {
        return true;
    }
}
