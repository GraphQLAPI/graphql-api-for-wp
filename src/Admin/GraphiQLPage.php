<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;

/**
 * GraphiQL page
 */
class GraphiQLPage extends AbstractMenuPage {

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client"><?php echo __('Loading...', 'graphql-by-pop') ?></div>
        <?php
    }

    public function init(): void
    {

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
