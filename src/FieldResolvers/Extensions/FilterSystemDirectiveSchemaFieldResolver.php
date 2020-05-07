<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\FieldResolvers\Extensions;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\GraphQL\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\GraphQL\TypeResolvers\SchemaTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\GraphQL\FieldResolvers\SchemaFieldResolver;
use PoP\Engine\DirectiveResolvers\SkipDirectiveResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\DirectiveResolvers\IncludeDirectiveResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;
use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

class FilterSystemDirectiveSchemaFieldResolver extends SchemaFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(SchemaTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'directives',
        ];
    }

    /**
     * Only use this fieldResolver when parameter `ofTypes` is provided.
     * Otherwise, use the default implementation
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @param array $fieldArgs
     * @return boolean
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        return $fieldName == 'directives' && isset($fieldArgs['ofTypes']);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'directives' => $translationAPI->__('All directives registered in the data graph, allowing to remove the system directives', 'graphql-api'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'directives':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'ofTypes',
                            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ENUM),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Include only directives of provided types', 'graphql-api'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                            SchemaDefinition::ARGNAME_ENUMVALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                [
                                    DirectiveTypes::QUERY,
                                    DirectiveTypes::SCHEMA,
                                    DirectiveTypes::SCRIPTING,
                                    DirectiveTypes::SYSTEM,
                                ]
                            ),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $schema = $resultItem;
        switch ($fieldName) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofTypes = $fieldArgs['ofTypes']) {
                    $instanceManager = InstanceManagerFacade::getInstance();
                    $directiveRegistry = DirectiveRegistryFacade::getInstance();
                    $ofTypeDirectiveResolverClasses = array_filter(
                        $directiveRegistry->getDirectiveResolverClasses(),
                        function ($directiveResolverClass) use ($instanceManager, $ofTypes) {
                            $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
                            return in_array($directiveResolver->getDirectiveType(), $ofTypes);
                        }
                    );
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = array_map(
                        function ($directiveResolverClass) {
                            // To retrieve the ID, use the same method to calculate the ID
                            // used when creating a new Directive instance
                            // (which we can't do here, since it has side-effects)
                            $directiveSchemaDefinitionPath = [
                                SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES,
                                $directiveResolverClass::getDirectiveName(),
                            ];
                            return SchemaDefinitionHelpers::getID($directiveSchemaDefinitionPath);
                        },
                        $ofTypeDirectiveResolverClasses
                    );
                    return array_intersect(
                        $directiveIDs,
                        $ofTypeDirectiveIDs
                    );
                }
                return $directiveIDs;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
