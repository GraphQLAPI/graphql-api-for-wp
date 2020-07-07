<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\FieldResolvers;

use PoP\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLCacheControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLAccessControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLFieldDeprecationListPostType;
use PoP\CustomPosts\Types\Status;

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
            'fieldDeprecationLists',
            'schemaConfigurations',
        ];
    }

    /**
     * These fields are used only by the application, so no need to
     * expose them to the user
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return boolean
     */
    public function skipAddingToSchemaDefinition(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return true;
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'accessControlLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'cacheControlLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'fieldDeprecationLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'schemaConfigurations' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'accessControlLists' => $translationAPI->__('Access Control Lists', 'graphql-api'),
            'cacheControlLists' => $translationAPI->__('Cache Control Lists', 'graphql-api'),
            'fieldDeprecationLists' => $translationAPI->__('Field Deprecation Lists', 'graphql-api'),
            'schemaConfigurations' => $translationAPI->__('Schema Configurations', 'graphql-api'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                $schemaFieldArgs = array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
                // Remove the "customPostTypes" field argument
                $schemaFieldArgs = array_filter(
                    $schemaFieldArgs,
                    function ($schemaFieldArg) {
                        return $schemaFieldArg[SchemaDefinition::ARGNAME_NAME] != 'customPostTypes';
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
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getQuery(
        TypeResolverInterface $typeResolver,
        $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                $query = [
                    'limit' => -1,
                    'custom-post-status' => [
                        Status::PUBLISHED,
                    ],
                ];
                return $query;
        }
        return [];
    }

    public function resolveValue(
        TypeResolverInterface $typeResolver,
        $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                // Remove the "customPostTypes" field argument
                unset($fieldArgs['customPostTypes']);
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                // Execute for the corresponding field name
                $customPostTypes = [
                    'accessControlLists' => GraphQLAccessControlListPostType::POST_TYPE,
                    'cacheControlLists' => GraphQLCacheControlListPostType::POST_TYPE,
                    'fieldDeprecationLists' => GraphQLFieldDeprecationListPostType::POST_TYPE,
                    'schemaConfigurations' => GraphQLSchemaConfigurationPostType::POST_TYPE,
                ];
                $query['custom-post-types'] = [
                    $customPostTypes[$fieldName],
                ];
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                    // With this flag, the hook will not remove the private CPTs
                    self::QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS => true,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPosts($query, $options);
        }

        return parent::resolveValue(
            $typeResolver,
            $resultItem,
            $fieldName,
            $fieldArgs,
            $variables,
            $expressions,
            $options
        );
    }

    public function resolveFieldTypeResolverClass(
        TypeResolverInterface $typeResolver,
        string $fieldName,
        array $fieldArgs = []
    ): ?string {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                return CustomPostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName, $fieldArgs);
    }
}
