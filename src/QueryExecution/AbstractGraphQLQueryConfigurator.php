<?php
namespace Leoloso\GraphQLByPoPWPPlugin\QueryExecution;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;

abstract class AbstractGraphQLQueryConfigurator
{
    protected $namespacedTypeNameClasses;
    protected $directiveNameClasses;

    public function init(): void
    {
        if (\is_singular(GraphQLQueryPostType::POST_TYPE)) {
            $this->doInit();
        }
    }

    abstract protected function doInit(): void;

    protected function getConfigurationCustomPostID(string $metaKey)
    {
        global $post;
        $graphQLQueryPost = $post;
        do {
            $aclPostID = \get_post_meta($graphQLQueryPost->ID, $metaKey, true);
            // If it doesn't have an ACL defined, and it has a parent, check if it has an ACL, then use that one
            if (!$aclPostID && $graphQLQueryPost->post_parent) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
            } else {
                // Make sure to exit the `while` for the root post, even if it doesn't have ACL
                $graphQLQueryPost = null;
            }
        } while (!$aclPostID && !is_null($graphQLQueryPost));

        return $aclPostID;
    }

    protected function getNamespacedTypeNameClasses(): array
    {
        if (is_null($this->namespacedTypeNameClasses)) {
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
        return $this->namespacedTypeNameClasses;
    }
    protected function getDirectiveNameClasses(): array
    {
        if (is_null($this->directiveNameClasses)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            $directiveRegistry = DirectiveRegistryFacade::getInstance();
            $directiveResolverClasses = $directiveRegistry->getDirectiveResolverClasses();
            // For each class, obtain its directive name. Notice that different directives can have the same name (eg: @translate as implemented for Google and Azure),
            // then the mapping goes from name to list of resolvers
            $this->directiveNameClasses = [];
            foreach ($directiveResolverClasses as $directiveResolverClass) {
                $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
                $directiveResolverName = $directiveResolver->getDirectiveName();
                $this->directiveNameClasses[$directiveResolverName][] = $directiveResolverClass;
            }
        }
        return $this->directiveNameClasses;
    }
    protected function getEntryFromField(string $selectedField, $value): array
    {
        $namespacedTypeNameClasses = $this->getNamespacedTypeNameClasses();
        // The field is composed by the type namespaced name, and the field name, separated by "."
        // Extract these values
        $entry = explode(AccessControlBlock::TYPE_FIELD_SEPARATOR, $selectedField);
        $namespacedTypeName = $entry[0];
        $field = $entry[1];
        // From the type, obtain which resolver class processes it
        if ($typeResolverClass = $namespacedTypeNameClasses[$namespacedTypeName]) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [$typeResolverClass, $field, $value];
        }
        return null;
    }
    protected function getEntryFromDirective(string $selectedDirective, $value): array
    {
        $directiveNameClasses = $this->getDirectiveNameClasses();
        // Obtain the directive resolver class from the directive name. If more than one resolver has the same directive name, add all of them
        if ($selectedDirectiveResolverClasses = $directiveNameClasses[$selectedDirective]) {
            foreach ($selectedDirectiveResolverClasses as $directiveResolverClass) {
                return [$directiveResolverClass, $value];
            }
        }
        return null;
    }
}
