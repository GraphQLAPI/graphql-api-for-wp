<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\BlockConstants;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;

/**
 * Base class for configuring the persisted GraphQL query before its execution
 */
abstract class AbstractGraphQLQueryConfigurator implements SchemaConfiguratorInterface
{
    /**
     * Keep a map of all namespaced type names to their resolver classes
     *
     * @var array
     */
    protected $namespacedTypeNameClasses;
    /**
     * Keep a map of all directives names to their resolver classes
     *
     * @var array
     */
    protected $directiveNameClasses;

    /**
     * Lazy load and return the `$namespacedTypeNameClasses` array
     *
     * @return array
     */
    protected function getNamespacedTypeNameClasses(): array
    {
        if (is_null($this->namespacedTypeNameClasses)) {
            $this->initNamespacedTypeNameClasses();
        }
        return $this->namespacedTypeNameClasses;
    }
    /**
     * Initialize the `$namespacedTypeNameClasses` array
     *
     * @return void
     */
    protected function initNamespacedTypeNameClasses(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $typeRegistry = TypeRegistryFacade::getInstance();
        $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
        // For each class, obtain its namespacedTypeName
        $this->namespacedTypeNameClasses = [];
        foreach ($typeResolverClasses as $typeResolverClass) {
            $typeResolver = $instanceManager->getInstance($typeResolverClass);
            $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
            $this->namespacedTypeNameClasses[$typeResolverNamespacedName] = $typeResolverClass;
        }
    }

    /**
     * Lazy load and return the `$directiveNameClasses` array
     *
     * @return array
     */
    protected function getDirectiveNameClasses(): array
    {
        if (is_null($this->directiveNameClasses)) {
            $this->initDirectiveNameClasses();
        }
        return $this->directiveNameClasses;
    }
    /**
     * Initialize the `$directiveNameClasses` array
     *
     * @param string $selectedField
     * @param [type] $value
     * @return array
     */
    protected function initDirectiveNameClasses(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $directiveRegistry = DirectiveRegistryFacade::getInstance();
        $directiveResolverClasses = $directiveRegistry->getDirectiveResolverClasses();
        // For each class, obtain its directive name. Notice that different directives
        // can have the same name (eg: @translate as implemented for Google and Azure),
        // then the mapping goes from name to list of resolvers
        $this->directiveNameClasses = [];
        foreach ($directiveResolverClasses as $directiveResolverClass) {
            $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
            $directiveResolverName = $directiveResolver->getDirectiveName();
            $this->directiveNameClasses[$directiveResolverName][] = $directiveResolverClass;
        }
    }

    /**
     * Create a service configuration entry comprising a field and its value
     * It returns a single array (or null)
     *
     * @param string $selectedField
     * @param mixed $value
     * @return array|null
     */
    protected function getEntryFromField(string $selectedField, $value): ?array
    {
        $namespacedTypeNameClasses = $this->getNamespacedTypeNameClasses();
        // The field is composed by the type namespaced name, and the field name, separated by "."
        // Extract these values
        $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
        $namespacedTypeName = $entry[0];
        $field = $entry[1];
        // From the type, obtain which resolver class processes it
        if ($typeResolverClass = $namespacedTypeNameClasses[$namespacedTypeName]) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [$typeResolverClass, $field, $value];
        }
        return null;
    }
    /**
     * Create the service configuration entries comprising a directive and its value
     * It returns an array of arrays
     *
     * @param string $selectedField
     * @param mixed $value
     * @return array|null
     */
    protected function getEntriesFromDirective(string $selectedDirective, $value): ?array
    {
        $directiveNameClasses = $this->getDirectiveNameClasses();
        // Obtain the directive resolver class from the directive name.
        // If more than one resolver has the same directive name, add all of them
        if ($selectedDirectiveResolverClasses = $directiveNameClasses[$selectedDirective]) {
            $entriesForDirective = [];
            foreach ($selectedDirectiveResolverClasses as $directiveResolverClass) {
                $entriesForDirective[] = [$directiveResolverClass, $value];
            }
            return $entriesForDirective;
        }
        return null;
    }
}
