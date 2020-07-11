<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\General\BlockConstants;
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
     * Keep a map of all namespaced interface names to the corresponding type resolver classes
     *
     * @var array
     */
    protected $namespacedInterfaceNameTypeResolverClasses;
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
            $this->initNamespacedTypeAndInterfaceNameClasses();
        }
        return $this->namespacedTypeNameClasses;
    }

    /**
     * Lazy load and return the `$namespacedInterfaceNameTypeResolverClasses` array
     *
     * @return array
     */
    protected function getNamespacedInterfaceNameTypeResolverClasses(): array
    {
        if (is_null($this->namespacedInterfaceNameTypeResolverClasses)) {
            $this->initNamespacedTypeAndInterfaceNameClasses();
        }
        return $this->namespacedInterfaceNameTypeResolverClasses;
    }

    /**
     * Initialize the `$namespacedTypeNameClasses` array
     *
     * @return void
     */
    protected function initNamespacedTypeAndInterfaceNameClasses(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $typeRegistry = TypeRegistryFacade::getInstance();
        $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
        // For each class, obtain its namespacedTypeName
        $this->namespacedTypeNameClasses = [];
        $this->namespacedInterfaceNameTypeResolverClasses = [];
        foreach ($typeResolverClasses as $typeResolverClass) {
            $typeResolver = $instanceManager->getInstance($typeResolverClass);
            $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
            $this->namespacedTypeNameClasses[$typeResolverNamespacedName] = $typeResolverClass;

            // Iterate all interfaces of the type, and add it to the other array
            foreach ($typeResolver->getAllImplementedInterfaceResolverInstances() as $interfaceInstance) {
                $interfaceNamespacedName = $interfaceInstance->getNamespacedInterfaceName();
                $this->namespacedInterfaceNameTypeResolverClasses[$interfaceNamespacedName][] = $typeResolverClass;
            }
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
     * Create a service configuration entry comprising a field and its value,
     * where an entry can involve a namespaced type or a namespaced interface
     *
     * It returns an array with all the entries extracted from it:
     * - If the field involves a type, the entry will be 1
     * - If the field involves an interface, the entry can be many, 1 for each type
     * implementing the interface
     *
     * @param string $selectedField
     * @param mixed $value
     * @return array
     */
    protected function getEntriesFromField(string $selectedField, $value): array
    {
        $namespacedTypeNameClasses = $this->getNamespacedTypeNameClasses();
        // The field is composed by the type namespaced name, and the field name, separated by "."
        // Extract these values
        $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
        // Maybe the namespaced name corresponds to a type, maybe to an interface
        $maybeNamespacedTypeName = $entry[0];
        $maybeNamespacedInterfaceName = $entry[0];
        $field = $entry[1];
        // From the type, obtain which resolver class processes it
        if ($typeResolverClass = $namespacedTypeNameClasses[$maybeNamespacedTypeName]) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [
                [$typeResolverClass, $field, $value],
            ];
        }
        // If it is an interface, add all the types implementing that interface!
        $namespacedInterfaceNameTypeResolverClasses = $this->getNamespacedInterfaceNameTypeResolverClasses();
        if ($typeResolverClasses = $namespacedInterfaceNameTypeResolverClasses[$maybeNamespacedInterfaceName]) {
            // Check `getConfigurationEntries` to understand format of each entry
            $entries = [];
            foreach ($typeResolverClasses as $typeResolverClass) {
                $entries[] = [$typeResolverClass, $field, $value];
            }
            return $entries;
        }

        return [];
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
