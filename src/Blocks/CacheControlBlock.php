<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;

/**
 * Cache Control block
 */
class CacheControlBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    /**
     * When saving access control for a field, the format is "typeNamespacedName.fieldName"
     */
    public const TYPE_FIELD_SEPARATOR = '.';

    protected function getBlockName(): string
    {
        return 'cache-control';
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock($attributes, $content): string
	{
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName().'-front';
        $typeFields = $attributes['typeFields'] ?? [];
        $directives = $attributes['directives'] ?? [];
        $fieldTypeContent = $directiveContent = '---';
        if ($typeFields) {
            $instanceManager = InstanceManagerFacade::getInstance();
            $typeRegistry = TypeRegistryFacade::getInstance();
            $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
            // For each class, obtain its namespacedTypeName
            $namespacedTypeNameNames = [];
            foreach ($typeResolverClasses as $typeResolverClass) {
                $typeResolver = $instanceManager->getInstance($typeResolverClass);
                $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
                $namespacedTypeNameNames[$typeResolverNamespacedName] = $typeResolver->getTypeName();
            }
            $fieldTypeContent = sprintf(
                '<ul><li>%s</li></ul>',
                implode('</li><li>', array_map(
                    function($selectedField) use($namespacedTypeNameNames) {
                        // The field is composed by the type namespaced name, and the field name, separated by "."
                        // Extract these values
                        $entry = explode(self::TYPE_FIELD_SEPARATOR, $selectedField);
                        $namespacedTypeName = $entry[0];
                        $field = $entry[1];
                        $typeName = $namespacedTypeNameNames[$namespacedTypeName] ?? $namespacedTypeName;
                        return $typeName.'/'.$field;
                    },
                    $typeFields
                ))
            );
        }
        if ($directives) {
            $directiveContent = sprintf(
                '<ul><li>%s</li></ul>',
                implode('</li><li>', $directives)
            );
        }
        $blockDataPlaceholder = <<<EOT
            <p><strong>%s</strong></p>
            %s
            <p><strong>%s</strong></p>
            %s
EOT;
        $blockDataContent = sprintf(
            $blockDataPlaceholder,
            __('Fields, by type', 'graphql-api'),
            $fieldTypeContent,
            __('(Non-system) Directives', 'graphql-api'),
            $directiveContent
        );
        $blockContentPlaceholder = <<<EOT
        <div class="%s">
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
        </div>
EOT;
        $blockCacheContent = 'Lorem ipsum';
        return sprintf(
            $blockContentPlaceholder,
            $className.' '.$this->getAlignClass(),
            $className.'__data',
            $className.'__title',
            __('Define cache for:', 'graphql-api'),
            $blockDataContent,
            $className.'__who',
            $className.'__title',
            __('Cache max age (in seconds):', 'graphql-api'),
            $blockCacheContent
        );
	}
}
