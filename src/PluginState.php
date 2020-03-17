<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;

class PluginState {

    public static $graphiQLBlock;
    public static $accessControlListBlock;

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
    public static function getAccessControlListBlock(): AccessControlDisableAccessBlock
    {
        return self::$accessControlListBlock;
    }

    /**
     * Set the value of accessControlListBlock
     *
     * @return void
     */
    public static function setAccessControlListBlock(AccessControlDisableAccessBlock $accessControlListBlock): void
    {
        self::$accessControlListBlock = $accessControlListBlock;
    }
}
