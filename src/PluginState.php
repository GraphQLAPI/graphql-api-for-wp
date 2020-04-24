<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\CacheControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\FieldDeprecationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigAccessControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigCacheControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigFieldDeprecationListBlock;

class PluginState
{
    public static $schemaConfigurationBlock;
    public static $graphiQLBlock;
    public static $accessControlBlock;
    public static $accessControlNestedBlocks = [];
    public static $cacheControlBlock;
    public static $fieldDeprecationBlock;
    public static $schemaConfigAccessControlListBlock;
    public static $schemaConfigCacheControlListBlock;
    public static $schemaConfigFieldDeprecationListBlock;

    /**
     * Get the value of schemaConfigurationBlock
     */
    public static function getSchemaConfigurationBlock(): SchemaConfigurationBlock
    {
        return self::$schemaConfigurationBlock;
    }

    /**
     * Set the value of schemaConfigurationBlock
     *
     * @return void
     */
    public static function setSchemaConfigurationBlock(SchemaConfigurationBlock $schemaConfigurationBlock): void
    {
        self::$schemaConfigurationBlock = $schemaConfigurationBlock;
    }

    /**
     * Get the value of graphiQLBlock
     */
    public static function getGraphiQLBlock(): GraphiQLBlock
    {
        return self::$graphiQLBlock;
    }

    /**
     * Set the value of graphiQLBlock
     *
     * @return void
     */
    public static function setGraphiQLBlock(GraphiQLBlock $graphiQLBlock): void
    {
        self::$graphiQLBlock = $graphiQLBlock;
    }

    /**
     * Get the value of accessControlBlock
     */
    public static function getAccessControlBlock(): AccessControlBlock
    {
        return self::$accessControlBlock;
    }

    /**
     * Set the value of accessControlBlock
     *
     * @return void
     */
    public static function setAccessControlBlock(AccessControlBlock $accessControlBlock): void
    {
        self::$accessControlBlock = $accessControlBlock;
    }

    /**
     * Get the value of graphiQLBlock
     */
    public static function getAccessControlNestedBlocks(): array
    {
        return self::$accessControlNestedBlocks;
    }

    /**
     * Set the value of accessControlNestedBlock
     *
     * @return void
     */
    public static function addAccessControlNestedBlock(AbstractBlock $accessControlNestedBlock): void
    {
        self::$accessControlNestedBlocks[] = $accessControlNestedBlock;
    }

    /**
     * Get the value of cacheControlBlock
     */
    public static function getCacheControlBlock(): CacheControlBlock
    {
        return self::$cacheControlBlock;
    }

    /**
     * Set the value of cacheControlBlock
     *
     * @return void
     */
    public static function setCacheControlBlock(CacheControlBlock $cacheControlBlock): void
    {
        self::$cacheControlBlock = $cacheControlBlock;
    }

    /**
     * Get the value of fieldDeprecationBlock
     */
    public static function getFieldDeprecationBlock(): FieldDeprecationBlock
    {
        return self::$fieldDeprecationBlock;
    }

    /**
     * Set the value of fieldDeprecationBlock
     *
     * @return void
     */
    public static function setFieldDeprecationBlock(FieldDeprecationBlock $fieldDeprecationBlock): void
    {
        self::$fieldDeprecationBlock = $fieldDeprecationBlock;
    }

    /**
     * Get the value of schemaConfigAccessControlListBlock
     */
    public static function getSchemaConfigAccessControlListBlock(): SchemaConfigAccessControlListBlock
    {
        return self::$schemaConfigAccessControlListBlock;
    }

    /**
     * Set the value of schemaConfigAccessControlListBlock
     *
     * @return void
     */
    public static function setSchemaConfigAccessControlListBlock(SchemaConfigAccessControlListBlock $schemaConfigAccessControlListBlock): void
    {
        self::$schemaConfigAccessControlListBlock = $schemaConfigAccessControlListBlock;
    }

    /**
     * Get the value of schemaConfigCacheControlListBlock
     */
    public static function getSchemaConfigCacheControlListBlock(): SchemaConfigCacheControlListBlock
    {
        return self::$schemaConfigCacheControlListBlock;
    }

    /**
     * Set the value of schemaConfigCacheControlListBlock
     *
     * @return void
     */
    public static function setSchemaConfigCacheControlListBlock(SchemaConfigCacheControlListBlock $schemaConfigCacheControlListBlock): void
    {
        self::$schemaConfigCacheControlListBlock = $schemaConfigCacheControlListBlock;
    }

    /**
     * Get the value of schemaConfigFieldDeprecationListBlock
     */
    public static function getSchemaConfigFieldDeprecationListBlock(): SchemaConfigFieldDeprecationListBlock
    {
        return self::$schemaConfigFieldDeprecationListBlock;
    }

    /**
     * Set the value of schemaConfigFieldDeprecationListBlock
     *
     * @return void
     */
    public static function setSchemaConfigFieldDeprecationListBlock(SchemaConfigFieldDeprecationListBlock $schemaConfigFieldDeprecationListBlock): void
    {
        self::$schemaConfigFieldDeprecationListBlock = $schemaConfigFieldDeprecationListBlock;
    }
}
