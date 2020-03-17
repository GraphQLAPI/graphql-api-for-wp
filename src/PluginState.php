<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;

class PluginState {

    public static $graphiQLBlock;
    public static $accessControlListBlocks = [];

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
     * Get the value of graphiQLBlock
     */
    public static function getAccessControlListBlocks(): array
    {
        return self::$accessControlListBlocks;
    }

    /**
     * Set the value of accessControlListBlock
     *
     * @return void
     */
    public static function addAccessControlListBlock(AccessControlDisableAccessBlock $accessControlListBlock): void
    {
        self::$accessControlListBlocks[] = $accessControlListBlock;
    }
}
