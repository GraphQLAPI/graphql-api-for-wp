<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers;

use PoP\EngineWP\Templates\TemplateHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\General\RequestParams;
use Leoloso\GraphQLByPoPWPPlugin\General\EndpointHelpers;
use PoP\GraphQLAPIRequest\Execution\QueryExecutionHelpers;
use Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers\EndpointResolverTrait;

class AdminEndpointResolver
{
    use EndpointResolverTrait;

    /**
     * Provide the query to execute and its variables
     *
     * @return array
     */
    protected function getGraphQLQueryAndVariables(): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return QueryExecutionHelpers::getRequestedGraphQLQueryAndVariables();
    }
    
    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     *
     * @return boolean
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return \is_admin()
            && 'POST' == $_SERVER['REQUEST_METHOD']
            && $_GET['page'] == Menu::NAME
            && $_GET[RequestParams::ACTION] == RequestParams::ACTION_EXECUTE_QUERY;
    }

    /**
     * Initialize the resolver
     *
     * @return void
     */
    public function init(): void
    {
        /**
         * Print the global JS variables, required by the blocks
         */
        $this->printGlobalVariables();

        /**
         * If executing the GraphQL query, resolve it
         */
        if ($this->isGraphQLQueryExecution()) {
            $this->executeGraphQLQuery();
            $this->printTemplateInAdminAndExit();
        }
    }

    /**
     * To print the JSON output, we use WordPress templates,
     * which are used only in the front-end.
     * When in the admin, we must manually load the template,
     * and then exit
     *
     * @return void
     */
    protected function printTemplateInAdminAndExit(): void
    {
        \add_action(
            'admin_init',
            function() {
                include TemplateHelpers::getTemplateFile();
                die;
            }
        );
    }

    /**
     * Print JS variables which are used by several blocks,
     * before the blocks are loaded
     *
     * @return void
     */
    protected function printGlobalVariables(): void
    {
        \add_action('admin_print_scripts', function () {
            /**
             * The endpoint against which to execute GraphQL queries while on the WordPress admin
             */
            \printf(
                '<script type="text/javascript">var GRAPHQL_API_ADMIN_ENDPOINT = "%s"</script>',
                EndpointHelpers::getGraphQLEndpoint()
            );
        });
    }
}
