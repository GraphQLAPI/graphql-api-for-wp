<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\General\BlockConstants;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

trait WithTypeFieldControlBlockTrait
{
    /**
     * Convert the typeFields from the format saved in the post: "typeNamespacedName.fieldName",
     * to the one suitable for printing on the page, to show the user: "typeName/fieldName"
     *
     * @param array $typeFields
     * @return array
     */
    public function getTypeFieldsForPrint(array $typeFields): array
    {
        $groupFieldsUnderTypeForPrint = ComponentConfiguration::groupFieldsUnderTypeForPrint();
        $instanceManager = InstanceManagerFacade::getInstance();
        $typeRegistry = TypeRegistryFacade::getInstance();
        $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
        // For each class, obtain its namespacedTypeName
        $namespacedTypeNameNames = [];
        $namespacedInterfaceNames = [];
        foreach ($typeResolverClasses as $typeResolverClass) {
            $typeResolver = $instanceManager->getInstance($typeResolverClass);
            $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
            $namespacedTypeNameNames[$typeResolverNamespacedName] = $typeResolver->getTypeName();

            // Iterate all interfaces of the type, and add it to the other array
            foreach ($typeResolver->getAllImplementedInterfaceResolverInstances() as $interfaceInstance) {
                $interfaceNamespacedName = $interfaceInstance->getNamespacedInterfaceName();
                $namespacedInterfaceNames[$interfaceNamespacedName] = $interfaceInstance->getInterfaceName();
            }
        }
        $typeFieldsForPrint = [];
        foreach ($typeFields as $selectedField) {
            // The field is composed by the type namespaced name, and the field name, separated by "."
            // Extract these values
            $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
            $namespacedTypeOrInterfaceName = $entry[0];
            $field = $entry[1];
            // It can either be a type, or an interface. If not, return the same element
            $typeOrInterfaceName =
                $namespacedTypeNameNames[$namespacedTypeOrInterfaceName]
                ?? $namespacedInterfaceNames[$namespacedTypeOrInterfaceName]
                ?? $namespacedTypeOrInterfaceName;
            /**
             * If $groupFieldsUnderTypeForPrint is true, combine all types under their shared typeName
             * If $groupFieldsUnderTypeForPrint is false, replace namespacedTypeName for typeName and "." for "/"
             * */
            if ($groupFieldsUnderTypeForPrint) {
                $typeFieldsForPrint[$typeOrInterfaceName][] = $field;
            } else {
                $typeFieldsForPrint[] = $typeOrInterfaceName . BlockConstants::TYPE_FIELD_SEPARATOR_FOR_PRINT . $field;
            }
        }
        return $typeFieldsForPrint;
    }
}
