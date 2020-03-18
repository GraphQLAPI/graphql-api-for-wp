<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractAccessControlBlock
{
    /**
     * When saving access control for a field, the format is "typeNamespacedName.fieldName"
     */
    public const TYPE_FIELD_SEPARATOR = '.';

    protected function getBlockName(): string
    {
        return 'access-control';
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }
}
