<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EditorScripts;

/**
 * Components required to edit a GraphQL endpoint CPT
 */
class EndpointEditorComponentScript extends AbstractEditorScript
{
    use GraphQLByPoPEditorScriptTrait;

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
}
