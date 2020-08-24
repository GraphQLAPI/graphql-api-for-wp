<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;

class OperationalFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\multiple-query-execution';
    public const REMOVE_IF_NULL_DIRECTIVE = Plugin::NAMESPACE . '\remove-if-null-directive';
    public const PROACTIVE_FEEDBACK = Plugin::NAMESPACE . '\proactive-feedback';

    public static function getModulesToResolve(): array
    {
        return [
            self::MULTIPLE_QUERY_EXECUTION,
            self::REMOVE_IF_NULL_DIRECTIVE,
            self::PROACTIVE_FEEDBACK,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleSubtype(string $module): ?string
    {
        return 'pioneering';
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::MULTIPLE_QUERY_EXECUTION:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::REMOVE_IF_NULL_DIRECTIVE:
            case self::PROACTIVE_FEEDBACK:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution', 'graphql-api'),
            self::REMOVE_IF_NULL_DIRECTIVE => \__('Remove if Null', 'graphql-api'),
            self::PROACTIVE_FEEDBACK => \__('Proactive Feedback', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::MULTIPLE_QUERY_EXECUTION:
                return \__('Execute multiple GraphQL queries in a single operation', 'graphql-api');
            case self::REMOVE_IF_NULL_DIRECTIVE:
                return \__('Addition of <code>@removeIfNull</code> directive, to remove an output from the response if it is <code>null</code>', 'graphql-api');
            case self::PROACTIVE_FEEDBACK:
                return \__('Usage of the top-level entry <code>extensions</code> to send deprecations, warnings, logs, notices and traces in the response to the query', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::MULTIPLE_QUERY_EXECUTION:
            case self::REMOVE_IF_NULL_DIRECTIVE:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
