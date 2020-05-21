<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EditorScripts;

use GraphQLAPI\GraphQLAPI\Scripts\GraphQLByPoPScriptTrait;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;

/**
 * Components required to edit a GraphQL endpoint CPT
 */
class EndpointComponentEditorScript extends AbstractEditorScript
{
    use GraphQLByPoPScriptTrait;

    /**
     * Block name
     *
     * @return string
     */
    protected function getScriptName(): string
    {
        return 'endpoint-editor-components';
    }

    /**
     * Dependencies to load before the script
     *
     * @return array
     */
    protected function getScriptDependencies(): array
    {
        return array_merge(
            parent::getScriptDependencies(),
            [
                'wp-edit-post',
            ]
        );
    }

    /**
     * Add the locale language to the localized data?
     *
     * @return bool
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     *
     * @return array
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }

    /**
     * In what languages is the documentation available
     *
     * @return array
     */
    protected function getDocLanguages(): array
    {
        return array_merge(
            parent::getDocLanguages(),
            [
                'es', // Spanish
            ]
        );
    }

    /**
     * Post types for which to register the script
     *
     * @return array
     */
    protected function getAllowedPostTypes(): array
    {
        return array_merge(
            parent::getAllowedPostTypes(),
            [
                GraphQLEndpointPostType::POST_TYPE,
            ]
        );
    }
}
