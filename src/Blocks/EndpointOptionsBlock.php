<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\EndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractQueryExecutionOptionsBlock;

/**
 * Endpoint Options block
 */
class EndpointOptionsBlock extends AbstractQueryExecutionOptionsBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED = 'isGraphiQLEnabled';
    public const ATTRIBUTE_NAME_IS_VOYAGER_ENABLED = 'isVoyagerEnabled';

    protected function getBlockName(): string
    {
        return 'endpoint-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(EndpointBlockCategory::class);
    }

    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);
        $moduleRegistry = ModuleRegistryFacade::getInstance();

        $labels = $this->getBooleanLabels();
        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        if ($moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Expose GraphiQL client?', 'graphql-api'),
                $labels[$attributes[self::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED] ?? true]
            );
        }
        if ($moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS)) {
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Expose the Interactive Schema client?', 'graphql-api'),
                $labels[$attributes[self::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED] ?? true]
            );
        }

        return $blockContent;
    }

    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocalizedData(): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        return array_merge(
            parent::getLocalizedData(),
            [
                'isGraphiQLEnabled' => $moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS),
                'isVoyagerEnabled' => $moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS),
            ]
        );
    }
}
