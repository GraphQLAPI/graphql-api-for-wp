<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\General\EndpointHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;
use Leoloso\GraphQLByPoPWPPlugin\Admin\EnqueueReactMenuPageTrait;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractMenuPage {

    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client"><?php echo __('Loading...', 'graphql-api') ?></div>
        <?php
    }

    protected function getScreenID(): ?string
    {
        return Menu::NAME;
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
            \GRAPHQL_BY_POP_PLUGIN_URL.'assets/css/graphiql-client.css',
            array(),
            \GRAPHQL_BY_POP_VERSION
        );
        \wp_enqueue_style(
            'graphql-api-graphiql',
            \GRAPHQL_BY_POP_PLUGIN_URL.'assets/css/vendors/graphiql.min.css',
            array(),
            \GRAPHQL_BY_POP_VERSION
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'graphql-api-graphiql',
            \GRAPHQL_BY_POP_PLUGIN_URL.'assets/js/vendors/graphiql.min.js',
            array('graphql-api-react-dom'),
            \GRAPHQL_BY_POP_VERSION,
            true
        );
        \wp_enqueue_script(
            'graphql-api-graphiql-client',
            \GRAPHQL_BY_POP_PLUGIN_URL.'assets/js/graphiql-client.js',
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
                'endpoint' => EndpointHelpers::getGraphQLEndpointURL(),
                'defaultQuery' => $this->getDefaultQuery(),
            )
        );
    }

    protected function getDefaultQuery(): string
    {
        return <<<EOT
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
#     {
#       field(arg: "value") {
#         subField
#       }
#     }
#
# Keyboard shortcuts:
#
#  Prettify Query:  Shift-Ctrl-P (or press the prettify button above)
#
#     Merge Query:  Shift-Ctrl-M (or press the merge button above)
#
#       Run Query:  Ctrl-Enter (or press the play button above)
#
#   Auto Complete:  Ctrl-Space (or just start typing)
#
query {
  posts(limit:2) {
    id
    title
    author {
      id
      name
      posts(limit:3) {
        id
        url
        title
        date(format:"d/m/Y")
        tags {
          name
        }
        featuredimage {
          id
          src
        }
      }
    }
  }
}

EOT;
    }
}
