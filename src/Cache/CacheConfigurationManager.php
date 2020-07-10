<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Cache;

use GraphQLAPI\GraphQLAPI\Settings\Options;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CacheConfigurationManager implements CacheConfigurationManagerInterface
{
    /**
     * Save into the DB, and inject to the FilesystemAdapter:
     * A string used as the subdirectory of the root cache directory, where cache
     * items will be stored
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     *
     * @return string
     */
    public function getNamespace(): string
    {
        // The timestamp from when last saving settings/modules to the DB
        return \get_option(Options::TIMESTAMP, '0');
    }
}
