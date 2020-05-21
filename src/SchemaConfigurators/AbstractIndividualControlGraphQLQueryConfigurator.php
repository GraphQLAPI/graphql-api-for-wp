<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

abstract class AbstractIndividualControlGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    /**
     * Create a service configuration entry comprising a field and its value,
     * adding an individual schema mode for access control.
     * It returns a single array (or null)
     *
     * @param string $selectedField
     * @param [type] $value
     * @param string|null $schemaMode
     * @return array|null
     */
    protected function getIndividualControlEntryFromField(string $selectedField, $value, ?string $schemaMode): ?array
    {
        $entryFromField = $this->getEntryFromField($selectedField, $value);
        if (!is_null($entryFromField) && !is_null($schemaMode)) {
            $entryFromField[] = $schemaMode;
        }
        return $entryFromField;
    }
    /**
     * Create the service configuration entries comprising a directive and its value,
     * adding an individual schema mode for access control.
     * It returns an array of arrays (or null)
     *
     * @param string $selectedField
     * @param mixed $value
     * @return array|null
     */
    protected function getIndividualControlEntriesFromDirective(string $selectedDirective, $value, ?string $schemaMode): ?array
    {
        $entriesForDirective = $this->getEntriesFromDirective($selectedDirective, $value);
        if (!is_null($entriesForDirective) && !is_null($schemaMode)) {
            foreach ($entriesForDirective as &$entry) {
                $entry[] = $schemaMode;
            }
        }
        return $entriesForDirective;
    }
}
