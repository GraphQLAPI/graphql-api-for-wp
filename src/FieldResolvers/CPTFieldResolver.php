<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\FieldResolvers;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;

class CPTFieldResolver extends AbstractQueryableFieldResolver
{
    /**
     * Option to tell the hook to not remove the private CPTs when querying
     */
    public const QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS = 'allow-querying-private-cpts';

    public static function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'accessControlLists',
            'cacheControlLists',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'accessControlLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'cacheControlLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'accessControlLists' => $translationAPI->__('Access Control Lists', 'graphql-api'),
            'cacheControlLists' => $translationAPI->__('Cache Control Lists', 'graphql-api'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
                $schemaFieldArgs = array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
                // Remove the "postTypes" field argument
                $schemaFieldArgs = array_filter(
                    $schemaFieldArgs,
                    function ($schemaFieldArg) {
                        return $schemaFieldArg[SchemaDefinition::ARGNAME_NAME] != 'postTypes';
                    }
                );
                break;
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
                $query = [
                    'limit' => -1,
                    'post-status' => [
                        \POP_POSTSTATUS_PUBLISHED,
                    ],
                ];
                return $query;
        }
        return [];
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
                // Remove the "postTypes" field argument
                unset($fieldArgs['postTypes']);
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                // Execute for the corresponding field name
                $postTypes = [
                    'accessControlLists' => GraphQLAccessControlListPostType::POST_TYPE,
                    'cacheControlLists' => GraphQLCacheControlListPostType::POST_TYPE,
                ];
                $query['post-types'] = [
                    $postTypes[$fieldName],
                ];
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                    // With this flag, the hook will not remove the private CPTs
                    self::QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS => true,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPosts($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName, $fieldArgs);
    }
}
