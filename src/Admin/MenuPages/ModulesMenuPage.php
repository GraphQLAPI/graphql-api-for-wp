<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractTableMenuPage;
use GraphQLAPI\GraphQLAPI\Admin\Tables\ModuleListTable;

/**
 * Module menu page
 */
class ModulesMenuPage extends AbstractTableMenuPage
{
    public const SCREEN_OPTION_NAME = 'modules_per_page';

    protected function getHeader(): string
    {
        return \__('GraphQL API — Modules', 'graphql-api');
    }

    protected function getScreenOptionLabel(): string
    {
        return \__('Modules', 'graphql-api');
    }

    protected function getScreenOptionName(): string
    {
        return self::SCREEN_OPTION_NAME;
    }

    protected function getTableClass(): string
    {
        return ModuleListTable::class;
    }

    protected function hasScreenOptions(): bool
    {
        return true;
    }
}
