<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers;

abstract class AbstractEndpointResolver
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize the resolver
     *
     * @return void
     */
    protected function init(): void
    {
        // Do nothing
    }
}
