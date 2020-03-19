<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\CacheControlBlock;

class PluginState {

    public static $graphiQLBlock;
    public static $accessControlBlock;
    public static $accessControlNestedBlocks = [];
    public static $cacheControlBlock;

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
}
