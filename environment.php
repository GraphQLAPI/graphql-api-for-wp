<?php

/**
 * Define environment variables required by the GraphQL API plugin,
 * overriding the values defined in GraphQL by PoP.
 * Because Composer hasn't been loaded yet, environment variables
 * cannot be referenced yet, so the corresponding string must be used
 */

declare(strict_types=1);

/**
 * GraphQL API determines if to add caching or not
 * Environment variable: PoP\Engine\Environment::ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE
 */
$_ENV['ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE'] = "false";
