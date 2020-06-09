<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;

class CPTUtils
{
    /**
     * Get the description of the post, defined in the excerpt
     *
     * @param object $post
     * @return string
     */
    public static function getCustomPostDescription($post): string
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(ModuleResolver::EXCERPT_AS_DESCRIPTION)) {
            return '';
        }
        return strip_tags($post->post_excerpt ?? '');
    }
}
