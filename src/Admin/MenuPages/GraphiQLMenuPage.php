<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\Menus\Menu;
use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\EnqueueReactMenuPageTrait;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractMenuPage
{
    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client">
            <p>
                <?php echo __('Loading...', 'graphql-api') ?>
                <!--span class="spinner is-active" style="float: none;"></span-->
            </p>
        </div>
        <?php
    }

    protected function getScreenID(): ?string
    {
        return Menu::getName();
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueAssets(): void
    {
        // CSS
        \wp_enqueue_style(
            'graphql-api-graphiql-client',
            \GRAPHQL_API_URL . 'assets/css/graphiql-client.css',
            array(),
            \GRAPHQL_BY_POP_VERSION
        );
        \wp_enqueue_style(
            'graphql-api-graphiql',
            \GRAPHQL_API_URL . 'assets/css/vendors/graphiql.min.css',
            array(),
            \GRAPHQL_BY_POP_VERSION
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'graphql-api-graphiql',
            \GRAPHQL_API_URL . 'assets/js/vendors/graphiql.min.js',
            array('graphql-api-react-dom'),
            \GRAPHQL_BY_POP_VERSION,
            true
        );
        \wp_enqueue_script(
            'graphql-api-graphiql-client',
            \GRAPHQL_API_URL . 'assets/js/graphiql-client.js',
            array('graphql-api-graphiql'),
            \GRAPHQL_BY_POP_VERSION,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'graphql-api-graphiql-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => EndpointHelpers::getAdminGraphQLEndpoint(),
                'defaultQuery' => $this->getDefaultQuery(),
                'response' => $this->getResponse(),
            )
        );
    }

    protected function getResponse(): string
    {
        return '';
        // return \__('Click the "Execute Query" button, or press Ctrl+Enter (Command+Enter in Mac)', 'graphql-api');
    }

    protected function getDefaultQuery(): string
    {
        return \__(<<<EOT
# Welcome to GraphiQL
#
# GraphiQL is an in-browser tool for writing, validating, and
# testing GraphQL queries.
#
# Type queries into this side of the screen, and you will see intelligent
# typeaheads aware of the current GraphQL type schema and live syntax and
# validation errors highlighted within the text.
#
# GraphQL queries typically start with a "{" character. Lines that starts
# with a # are ignored.
#
# An example GraphQL query might look like:
#
#   {
#     field(arg: "value") {
#       subField
#     }
#   }
#
# Run the query (at any moment):
#
#   Ctrl-Enter (or press the play button above)
#

query {
  posts(limit:3) {
    id
    title
    date(format:"d/m/Y")
    url
    author {
      id
      name
      url
    }
    tags {
      name
    }
    featuredImage {
      src
    }
  }
}

EOT, 'graphql-api');
    }
}
