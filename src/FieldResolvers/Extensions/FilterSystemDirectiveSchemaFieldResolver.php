<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\FieldResolvers\Extensions;

use PoP\API\Schema\SchemaDefinition;
use PoP\GraphQL\TypeResolvers\SchemaTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\DirectiveResolvers\SkipDirectiveResolver;
use PoP\Engine\DirectiveResolvers\IncludeDirectiveResolver;
use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;
use PoP\GraphQL\FieldResolvers\SchemaFieldResolver;
use PoP\GraphQL\Schema\SchemaDefinitionHelpers;

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
     * Only use this fieldResolver when parameter `skipSystemDirectives` is provided. Otherwise, use the default implementation
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @param array $fieldArgs
     * @return boolean
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        return $fieldName == 'directives' && isset($fieldArgs['skipSystemDirectives']);
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
                            SchemaDefinition::ARGNAME_NAME => 'skipSystemDirectives',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Skip the system directives', 'graphql-api'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
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
                if ($fieldArgs['skipSystemDirectives']) {
                    // System directives are: "@skip", "@include" and "@cacheControl"
                    $systemDirectiveResolverClasses = [
                        SkipDirectiveResolver::class,
                        IncludeDirectiveResolver::class,
                        AbstractCacheControlDirectiveResolver::class,
                    ];
                    // Calculate the directive IDs
                    $systemDirectiveIDs = array_map(
                        function ($directiveResolverClass) {
                            // To retrieve the ID, use the same method to calculate the ID used when creating a new Directive instance (which we can't do here, since it has side-effects)
                            $directiveSchemaDefinitionPath = [
                                SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES,
                                $directiveResolverClass::getDirectiveName(),
                            ];
                            return SchemaDefinitionHelpers::getID($directiveSchemaDefinitionPath);
                        },
                        $systemDirectiveResolverClasses
                    );
                    $directiveIDs = array_diff(
                        $directiveIDs,
                        $systemDirectiveIDs
                    );
                }
                return $directiveIDs;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
