<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;

class PluginState {

    public static $graphiQLBlock;

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
}
