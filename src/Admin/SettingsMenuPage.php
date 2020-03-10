<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;
use PoP\API\Schema\QueryInputs;
use PoP\Posts\TypeResolvers\PostTypeResolver;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractMenuPage {

    /**
     * Indicate if the option is on. Made static so it can be used without instantiation
     *
     * @param string $name
     * @return boolean
     */
    public static function isOptionOn(string $name): bool
    {
        $options = \get_option('graphql-api-settings');
        return !empty($options[$name]);
    }

    /**
     * Get the option value. Made static so it can be used without instantiation
     *
     * @param string $name
     * @return boolean
     */
    public static function getOptionValue(string $name): ?string
    {
        $options = \get_option('graphql-api-settings');
        return $options[$name];
    }

    public function print(): void
    {
        ?>
        <script type="application/javascript">
            jQuery( document ).ready( function($){
                $('#graphql-api-settings .tab-content').hide(); // Hide all tabs first
                $('#graphql-api-settings #main').show(); //  Show the default tab
                // $('#graphql-api-settings a[href="#main"].nav-tab').addClass('nav-tab-active');

                $('#graphql-api-settings .nav-tab').on('click', function(e){
                    e.preventDefault();
                    tab = $(this).attr('href');
                    $('#graphql-api-settings .tab-content').hide();
                    $(tab).show();
                    $('#graphql-api-settings .nav-tab').removeClass('nav-tab-active');
                    $('#graphql-api-settings a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
                });
            });
        </script>
        <div
            id="graphql-api-settings"
            class="wrap"
        >
            <h1><?php echo \__('GraphQL API — Settings', 'graphql-api'); ?></h1>
            <?php settings_errors(); ?>
            <?php $this->printMainSectionDescription(); ?>

            <h2 class="nav-tab-wrapper">
                <a href="#main" class="nav-tab nav-tab-active"><?php echo \__('Main', 'graphql-api'); ?></a>
                <a href="#graphql" class="nav-tab"><?php echo \__('GraphQL', 'graphql-api'); ?></a>
                <a href="#rest" class="nav-tab"><?php echo \__('REST', 'graphql-api'); ?></a>
                <a href="#extended_graphql" class="nav-tab"><?php echo \__('Extended GraphQL', 'graphql-api'); ?></a>
            </h2>

            <form method="post" action="options.php">
                <?php settings_fields('graphql-api-settings'); ?>
                <!--?php do_settings_sections('graphql-api-settings'); ?-->

                <?php /* Main Section */ ?>
                <div id="main" class="tab-content">
                    <?php $this->printMainHeader(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php \do_settings_fields('graphql-api-settings', 'graphql-api-settings-main-section'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php /* GraphQL */ ?>
                <div id="graphql" class="tab-content">
                    <?php $this->printGraphQLEnabledHeader1(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php \do_settings_fields('graphql-api-settings', 'graphql-api-settings-graphql-enabled-section-1'); ?>
                    <?php echo '</table>'; ?>
                    <?php $this->printGraphQLEnabledHeader2(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php \do_settings_fields('graphql-api-settings', 'graphql-api-settings-graphql-enabled-section-2'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php /* REST Section */ ?>
                <div id="rest" class="tab-content">
                    <?php $this->printRESTEnabledHeader1(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php \do_settings_fields('graphql-api-settings', 'graphql-api-settings-rest-enabled-section-1'); ?>
                    <?php echo '</table>'; ?>
                    <?php $this->printRESTEnabledHeader2(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php \do_settings_fields('graphql-api-settings', 'graphql-api-settings-rest-enabled-section-2'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php /* GraphQL Extended Section */ ?>
                <div id="extended_graphql" class="tab-content">
                    <?php $this->printXTGraphQLEnabledHeader(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php \do_settings_fields('graphql-api-settings', 'graphql-api-settings-extendedgraphql-enabled-section'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php \submit_button(); ?>
            </form>
        </div>
        <?php
    }

    protected function init(): void
    {
        /**
         * Main section
         */
        \add_settings_section(
            'graphql-api-settings-main-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printMainSectionDescription'],
            'graphql-api-settings'
        );
        \add_settings_field(
            'graphql-api-graphql-endpoint',
            \__('GraphQL endpoint', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-main-section',
            array(
                'label' => sprintf(
                    \__('Make the GraphQL service available under the specified endpoint. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#graphql-endpoint'
                ),
                'id'    => 'graphql-api-graphql-endpoint',
            )
        );
        \add_settings_field(
            'graphql-api-rest-endpoint',
            \__('REST endpoint', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-main-section',
            array(
                'label' => sprintf(
                    \__('Endpoint to append at the end of a resource URL (single post, author, tag, page) to access its pre-defined data. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#rest-endpoint'
                ),
                'id'    => 'graphql-api-rest-endpoint',
            )
        );
        \add_settings_field(
            'graphql-api-enable-extended-graphql',
            \__('Enable Extended GraphQL', 'graphql-api'),
            [$this, 'printCheckboxField'],
            'graphql-api-settings',
            'graphql-api-settings-main-section',
            array(
                'label' => sprintf(
                    \__('Supercharge the GraphQL API with additional features. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#extended-graphql'
                ),
                'id'    => 'graphql-api-enable-extended-graphql',
            )
        );
        \add_settings_field(
            'graphql-api-blockmetadata',
            \__('Enable querying Gutenberg', 'graphql-api'),
            [$this, 'printCheckboxField'],
            'graphql-api-settings',
            'graphql-api-settings-main-section',
            array(
                'label' => sprintf(
                    \__('Add a field <code>blockMetadata</code> on type <code>%s</code> to retrieve the meta-data from its Guntenberg blocks. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    PostTypeResolver::NAME,
                    'https://graphql.getpop.org/wp/documentation/#gutenberg'
                ),
                'id'    => 'graphql-api-blockmetadata',
            )
        );

        /**
         * GraphQL section <= valid when GraphQL enabled
         */
        \add_settings_section(
            'graphql-api-settings-graphql-enabled-section-1',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printGraphQLEnabledHeader1'],
            'graphql-api-settings'
        );
        \add_settings_field(
            'graphql-api-namespacing',
            \__('Enable schema namespacing', 'graphql-api'),
            [$this, 'printCheckboxField'],
            'graphql-api-settings',
            'graphql-api-settings-graphql-enabled-section-1',
            array(
                'label' => sprintf(
                    \__('Automatically namespace types and interfaces as to avoid potential naming clashes. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#namespacing'
                ),
                'id'    => 'graphql-api-namespacing',
            )
        );
        \add_settings_section(
            'graphql-api-settings-graphql-enabled-section-2',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printGraphQLEnabledHeader2'],
            'graphql-api-settings'
        );
        \add_settings_field(
            'graphql-api-public-graphiql',
            \__('GraphiQL client URL path', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-graphql-enabled-section-2',
            array(
                'label' => sprintf(
                    \__('Make the GraphiQL client publicly available under the specified URL path. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#graphiql'
                ),
                'id'    => 'graphql-api-public-graphiql',
            )
        );
        \add_settings_field(
            'graphql-api-public-voyager',
            \__('Interactive schema URL path', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-graphql-enabled-section-2',
            array(
                'label' => sprintf(
                    \__('Make the interactive schema publicly available under the specified URL path. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#voyager'
                ),
                'id'    => 'graphql-api-public-voyager',
            )
        );
        \add_settings_field(
            'graphql-api-clients-restrictaccess',
            \__('Restrict access by user capability', 'graphql-api'),
            [$this, 'printCheckboxField'],
            'graphql-api-settings',
            'graphql-api-settings-graphql-enabled-section-2',
            array(
                'label' => sprintf(
                    \__('Allow only logged-in users with capability <code>%s</code> to access the GraphiQL and interactive schema clients. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'access_graphql_clients',
                    'https://graphql.getpop.org/wp/documentation/#restrict-access-to-clients'
                ),
                'id'    => 'graphql-api-clients-restrictaccess',
            )
        );

        /**
         * REST section <= valid when REST enabled
         * Header 1
         */
        \add_settings_section(
            'graphql-api-settings-rest-enabled-section-1',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printRESTEnabledHeader1'],
            'graphql-api-settings'
        );
        \add_settings_field(
            'graphql-api-rest-enable-querying',
            \__('Enable to query custom fields', 'graphql-api'),
            [$this, 'printCheckboxField'],
            'graphql-api-settings',
            'graphql-api-settings-rest-enabled-section-1',
            array(
                'label' => sprintf(
                    \__('Query custom fields in the REST endpoint through parameter <code>%s</code>. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    QueryInputs::QUERY,
                    'https://graphql.getpop.org/wp/documentation/#rest-custom-querying'
                ),
                'id'    => 'graphql-api-rest-enable-querying',
            )
        );
        /**
         * REST section Header 2
         */
        \add_settings_section(
            'graphql-api-settings-rest-enabled-section-2',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printRESTEnabledHeader2'],
            'graphql-api-settings'
        );
        \add_settings_field(
            'graphql-api-rest-enabled-post-fields',
            \__('Post fields', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-rest-enabled-section-2',
            array(
                'label' => sprintf(
                    \__('Default fields for the single post URL, and the post list page URL (with slug <code>%s</code>)', 'graphql-api'),
                    \POP_POSTS_ROUTE_POSTS
                ),
                'id'    => 'graphql-api-rest-enabled-post-fields',
            )
        );
        \add_settings_field(
            'graphql-api-rest-enabled-user-fields',
            \__('User fields', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-rest-enabled-section-2',
            array(
                'label' => sprintf(
                    \__('Default fields for the author URL, and the user list page URL (with slug <code>%s</code>)', 'graphql-api'),
                    \POP_USERS_ROUTE_USERS
                ),
                'id'    => 'graphql-api-rest-enabled-user-fields',
            )
        );
        \add_settings_field(
            'graphql-api-rest-enabled-tag-fields',
            \__('Tag fields', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-rest-enabled-section-2',
            array(
                'label' => sprintf(
                    \__('Default fields for the single tag URL, and the tag list page URL (with slug <code>%s</code>)', 'graphql-api'),
                    \POP_TAXONOMIES_ROUTE_TAGS
                ),
                'id'    => 'graphql-api-rest-enabled-tag-fields',
            )
        );
        \add_settings_field(
            'graphql-api-rest-enabled-page-fields',
            \__('Page fields', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-rest-enabled-section-2',
            array(
                'label' => \__('Default fields for the page URL'),
                'id'    => 'graphql-api-rest-enabled-page-fields',
            )
        );

        /**
         * Extended GraphQL section <= valid when Extended GraphQL enabled
         * Header 1
         */
        \add_settings_section(
            'graphql-api-settings-extendedgraphql-enabled-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printXTGraphQLEnabledHeader'],
            'graphql-api-settings'
        );
        \add_settings_field(
            'graphql-api-extendedgraphql-cachecontrol',
            \__('Cache-control max-age', 'graphql-api'),
            [$this, 'printInputField'],
            'graphql-api-settings',
            'graphql-api-settings-extendedgraphql-enabled-section',
            array(
                'label' => sprintf(
                    \__('HTTP Caching: Set the default max-age value in seconds for the Cache-Control header. From this value, the overall max-age from all requested fields will be calculated. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    'https://graphql.getpop.org/wp/documentation/#cache-control'
                ),
                'id'    => 'graphql-api-extendedgraphql-cachecontrol',
            )
        );
        \add_settings_field(
            'graphql-api-extendedgraphql-guzzle',
            \__('Enable sending requests to external web services', 'graphql-api'),
            [$this, 'printCheckboxField'],
            'graphql-api-settings',
            'graphql-api-settings-extendedgraphql-enabled-section',
            array(
                'label' => sprintf(
                    \__('Enable fields (%s) to fetch data from external web services. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-api'),
                    '<code>'.implode(
                        '</code>'.__(',').'<code>',
                        [
                            'getJSON',
                            'getAsyncJSON',
                        ]
                    ).'</code>',
                    'https://graphql.getpop.org/wp/documentation/#external-services'
                ),
                'id'    => 'graphql-api-extendedgraphql-guzzle',
            )
        );

        /**
         * Finally register all the settings
         */
        \register_setting(
            'graphql-api-settings',
            'graphql-api-settings'
        );
    }

    /**
     * Display a checkbox field.
     *
     * @param array $args
     * @return void
     */
    function printCheckboxField(array $args): void
    {
        $name = $args['id'];
        $value = self::isOptionOn($name);
        ?>
            <label for="<?php echo $args['id']; ?>">
                <input type="checkbox" name="<?php echo 'graphql-api-settings['.$name.']'; ?>" id="<?php echo $name; ?>" value="1" <?php checked(1, $value); ?> />
                <?php echo $args['label']; ?>
            </label>
        <?php
    }

    /**
     * Display a checkbox field.
     *
     * @param array $args
     * @return void
     */
    function printInputField(array $args): void
    {
        $name = $args['id'];
        $value = self::getOptionValue($name) ?? '';
        $label = $args['label'] ? '<br/>'.$args['label'] : '';
        ?>
            <label for="<?php echo $args['id']; ?>">
                <input type="text" name="<?php echo 'graphql-api-settings['.$name.']'; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" />
                <?php echo $label; ?>
            </label>
        <?php
    }

    /**
     * Section header
     */
    function printMainSectionDescription()
    {
        ?>
        <p>
            <?php /*echo sprintf(
                \__('Please refer to the <a href="%s">documentation page</a> for detailed information on all features.', 'graphql-api'),
                menu_page_url('graphql_api_documentation', false)
            );*/echo sprintf(
                \__('Please refer to the <a href="%s">documentation</a> for detailed information on all features.', 'graphql-api'),
                'https://graphql.getpop.org/wp/documentation/'
            );?>
        </p>
        <?php
    }

    /**
     * Section header
     */
    function printMainHeader(): void
    {
        ?>
        <h2>
        <?php echo \__('Main settings', 'graphql-api');?>
        </h2>
        <?php
    }

    /**
     * Section header
     */
    function printRESTEnabledHeader1(): void
    {
        ?>
        <h2>
        <?php echo \__('Settings if REST is enabled', 'graphql-api');?>
        </h2>
        <p>
            <?php echo sprintf(
                \__('<strong>Note:</strong> Fields (default ones for the REST endpoint, and custom ones when querying through the URL) are defined using the <a href="%s">Extended GraphQL syntax</a>.', 'graphql-api'),
                'https://github.com/getpop/field-query'
            );?>
            <br/>
            <?php echo \__('Examples for a post: ', 'graphql-api');?>
        </p>
        <ol>
            <li>
                <?php echo \__('<code>"id|title"</code> fetches the post\'s <code>ID</code> and <code>title</code> fields', 'graphql-api')?>
            </li>
            <li>
                <?php echo \__('<code>"id|title|author.id|name"</code> fetches the post\'s <code>ID</code> and <code>title</code> fields, and the post author\'s <code>ID</code> and <code>name</code> fields', 'graphql-api')?>
            </li>
            <li>
                <?php echo \__('<code>"id|title|comments.id|date|content|author.id|name"</code> fetches the post\'s <code>ID</code> and <code>title</code> fields, the post comments\' <code>ID</code>, <code>date</code> and <code>content</code> fields, and the comment\'s author\'s <code>ID</code> and <code>name</code> fields', 'graphql-api')?>
            </li>
        </ol>
        <?php
    }

    /**
     * Section header
     */
    function printRESTEnabledHeader2(): void
    {
        ?>
        <!--h3>
            <?php echo \__('Default REST fields ', 'graphql-api');?>
        </h3-->
        <h4><em>
            <?php echo \__('Default REST fields for the different resources:', 'graphql-api');?>
        </em></h4>
        <?php
    }

    /**
     * Section header
     */
    function printXTGraphQLEnabledHeader(): void
    {
        ?>
        <h2>
        <?php echo \__('Settings if Extended GraphQL is enabled', 'graphql-api');?>
        </h2>
        <?php
    }

    /**
     * Section header
     */
    function printGraphQLEnabledHeader1(): void
    {
        ?>
        <h2>
        <?php echo \__('Settings if GraphQL is enabled', 'graphql-api');?>
        </h2>
        <?php
    }/**
     * Section header
     */
    function printGraphQLEnabledHeader2(): void
    {
        ?>
        <h4><em>
            <?php echo \__('Public GraphQL clients:', 'graphql-api');?>
        </em></h4>
        <?php
    }
}
