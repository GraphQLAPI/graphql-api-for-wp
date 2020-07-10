<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PostTypes;

use PoP\ComponentModel\State\ApplicationState;
use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use GraphQLAPI\GraphQLAPI\General\RequestParams;
use GraphQLAPI\GraphQLAPI\PostTypes\AbstractPostType;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigurationBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\EndpointResolvers\EndpointResolverTrait;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractQueryExecutionOptionsBlock;

abstract class AbstractGraphQLQueryExecutionPostType extends AbstractPostType
{
    use EndpointResolverTrait {
        EndpointResolverTrait::getNature as getUpstreamNature;
        EndpointResolverTrait::addGraphQLVars as upstreamAddGraphQLVars;
    }

    /**
     * Indicates if we executing the GraphQL query (`true`) or visualizing the query source (`false`)
     * It returns always `true`, unless passing ?view=source in the single post URL
     *
     * @return boolean
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return $_REQUEST[RequestParams::VIEW] != RequestParams::VIEW_SOURCE;
    }

    /**
     * Label to show on the "execute" action in the CPT table
     *
     * @return string
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute', 'graphql-api');
    }

    /**
     * Get actions to add for this CPT
     * "View" action must be attached ?view=source, and the view link is called "Execute"
     *
     * @param Object $post
     * @return array
     */
    protected function getPostTypeTableActions($post): array
    {
        $actions = parent::getPostTypeTableActions($post);
        $post_type_object = \get_post_type_object($post->post_type);

        /**
         * Code copied from function `handle_row_actions` in file
         * wp-admin/includes/class-wp-posts-list-table.php
         */
        if (is_post_type_viewable($post_type_object)) {
            $title = \_draft_or_post_title();
            $isEnabled = $this->isEnabled($post);
            $executeLabel = $this->getExecuteActionLabel();
            if (in_array($post->post_status, array('pending', 'draft', 'future'))) {
                $can_edit_post = \current_user_can('edit_post', $post->ID);
                if ($can_edit_post) {
                    $preview_link = \get_preview_post_link($post);
                    $actions['view'] = sprintf(
                        '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                        esc_url(\add_query_arg(
                            RequestParams::VIEW,
                            RequestParams::VIEW_SOURCE,
                            $preview_link
                        )),
                        /* translators: %s: Post title. */
                        esc_attr(sprintf(__('Preview source &#8220;%s&#8221;', 'graphql-api'), $title)),
                        __('Preview source', 'graphql-api')
                    );
                    if ($isEnabled) {
                        $actions['execute'] = sprintf(
                            '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                            esc_url($preview_link),
                            esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'graphql-api'), $executeLabel, $title)),
                            $executeLabel
                        );
                    }
                }
            } elseif ('trash' != $post->post_status) {
                $actions['view'] = sprintf(
                    '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                    \add_query_arg(
                        RequestParams::VIEW,
                        RequestParams::VIEW_SOURCE,
                        get_permalink($post->ID)
                    ),
                    esc_attr(sprintf(__('View source &#8220;%s&#8221;', 'graphql-api'), $title)),
                    __('View source', 'graphql-api')
                );
                if ($isEnabled) {
                    $actions['execute'] = sprintf(
                        '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                        get_permalink($post->ID),
                        esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'graphql-api'), $executeLabel, $title)),
                        $executeLabel
                    );
                }
            }
        }
        return $actions;
    }

    /**
     * Add the hook to initialize the different post types
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        /**
         * Two outputs:
         * 1.`isGraphQLQueryExecution` = true, then resolve the GraphQL query
         * 2.`isGraphQLQueryExecution` = false, then do something else (eg: view the source for the GraphQL query)
         */
        if ($this->isGraphQLQueryExecution()) {
            $this->executeGraphQLQuery();
        } else {
            $this->doSomethingElse();
        }
    }

    /**
     * Do something else, not the execution of the GraphQL query.
     * By default, print the Query source
     *
     * @return void
     */
    protected function doSomethingElse(): void
    {
        /** Add the excerpt, which is the description of the GraphQL query */
        \add_filter(
            'the_content',
            [$this, 'maybeGetGraphQLQuerySourceContent']
        );
    }

    /**
     * Render the GraphQL Query CPT
     *
     * @param [type] $content
     * @return string
     */
    public function maybeGetGraphQLQuerySourceContent(string $content): string
    {
        /**
         * Check if it is this CPT...
         */
        if (\is_singular($this->getPostType())) {
            $vars = ApplicationState::getVars();
            $post = $vars['routing-state']['queried-object'];
            return $this->getGraphQLQuerySourceContent($content, $post);
        }
        return $content;
    }

    /**
     * Render the GraphQL Query CPT
     *
     * @param [type] $content
     * @return string
     */
    protected function getGraphQLQuerySourceContent(string $content, $graphQLQueryPost): string
    {
        /**
         * Prettyprint the code
         */
        $content .= '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
        return $content;
    }

    /**
     * Assign the single endpoint by setting it as the Home nature
     */
    public function getNature($nature, $query)
    {
        if ($query->is_singular($this->getPostType())) {
            return $this->getUpstreamNature($nature, $query);
        }

        return $nature;
    }

    abstract protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock;

    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @return void
     */
    protected function isOptionsBlockValueOn($postOrID, string $attribute, bool $default): bool
    {
        $optionsBlockDataItem = $this->getOptionsBlockDataItem($postOrID);
        // If there was no options block, something went wrong in the post content
        if (is_null($optionsBlockDataItem)) {
            return null;
        }

        // The default value is not saved in the DB in Gutenberg!
        return $optionsBlockDataItem['attrs'][$attribute] ?? $default;
    }

    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @return void
     */
    protected function isEnabled($postOrID): bool
    {
        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            AbstractQueryExecutionOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED,
            true
        );
    }

    protected function getOptionsBlockDataItem($postOrID): ?array
    {
        return BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getQueryExecutionOptionsBlock()
        );
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     *
     * @return boolean
     */
    protected function doURLParamsOverrideGraphQLVariables($postOrID): bool
    {
        return true;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @return void
     */
    public function addGraphQLVars($vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if (\is_singular($this->getPostType()) && $this->isEnabled($vars['routing-state']['queried-object-id'])) {
            $this->upstreamAddGraphQLVars($vars_in_array);
        }
    }

    /**
     * If enabled by module, add the Schema Configuration block to the locked Gutenberg template
     *
     * @return array
     */
    protected function maybeAddSchemaConfigurationBlock(array &$template): void
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::SCHEMA_CONFIGURATION)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            $schemaConfigurationBlock = $instanceManager->getInstance(SchemaConfigurationBlock::class);
            $template[] = [$schemaConfigurationBlock->getBlockFullName()];
        }
    }
}
