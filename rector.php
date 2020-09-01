<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\DowngradeTypedPropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Set\ValueObject\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::AUTOLOAD_PATHS, [
        // full directory
        __DIR__ . '/vendor/wordpress/wordpress',
    ]);

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/vendor/getpop',
        __DIR__ . '/vendor/pop-schema',
        __DIR__ . '/vendor/graphql-by-pop',
    ]);

    // is there a file you need to skip?
    $parameters->set(Option::EXCLUDE_PATHS, [
        __DIR__ . '/vendor/getpop/migrate-*/*',
        __DIR__ . '/vendor/pop-schema/migrate-*/*',
        __DIR__ . '/vendor/graphql-by-pop/graphql-parser/*',
    ]);

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [
        SetList::DOWNGRADE
    ]);

    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, '7.2');

    // get services
    $services = $containerConfigurator->services();

    // register single rule
    $services->set(DowngradeTypedPropertyRector::class);
};