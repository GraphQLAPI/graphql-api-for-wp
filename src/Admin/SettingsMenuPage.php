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
        $options = get_option('graphql-by-pop-settings');
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
        $options = get_option('graphql-by-pop-settings');
        return $options[$name];
    }

    public function print(): void
    {
        ?>
        <script type="application/javascript">
            jQuery( document ).ready( function($){
                $('#graphql-by-pop-settings .tab-content').hide(); // Hide all tabs first
                $('#graphql-by-pop-settings #main').show(); //  Show the default tab
                // $('#graphql-by-pop-settings a[href="#main"].nav-tab').addClass('nav-tab-active');

                $('#graphql-by-pop-settings .nav-tab').on('click', function(e){
                    e.preventDefault();
                    tab = $(this).attr('href');
                    $('#graphql-by-pop-settings .tab-content').hide();
                    $(tab).show();
                    $('#graphql-by-pop-settings .nav-tab').removeClass('nav-tab-active');
                    $('#graphql-by-pop-settings a[href="'+tab+'"].nav-tab').addClass('nav-tab-active');
                });
            });
        </script>
        <div
            id="graphql-by-pop-settings"
            class="wrap"
        >
            <h1><?php echo __('GraphQL by PoP — Settings', 'graphql-by-pop'); ?></h1>
            <?php settings_errors(); ?>
            <?php $this->printMainSectionDescription(); ?>

            <h2 class="nav-tab-wrapper">
                <a href="#main" class="nav-tab nav-tab-active"><?php echo __('Main', 'graphql-by-pop'); ?></a>
                <a href="#rest" class="nav-tab"><?php echo __('REST', 'graphql-by-pop'); ?></a>
                <a href="#extended_graphql" class="nav-tab"><?php echo __('Extended GraphQL', 'graphql-by-pop'); ?></a>
                <a href="#clients" class="nav-tab"><?php echo __('Public clients', 'graphql-by-pop'); ?></a>
            </h2>

            <form method="post" action="options.php">
                <?php settings_fields('graphql-by-pop-settings'); ?>
                <!--?php do_settings_sections('graphql-by-pop-settings'); ?-->

                <?php /* Main Section */ ?>
                <div id="main" class="tab-content">
                    <?php echo '<table class="form-table">'; ?>
                    <?php do_settings_fields('graphql-by-pop-settings', 'graphql-by-pop-settings-main-section'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php /* REST Section */ ?>
                <div id="rest" class="tab-content">
                    <?php $this->printRESTEnabledHeader1(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php do_settings_fields('graphql-by-pop-settings', 'graphql-by-pop-settings-rest-enabled-section-1'); ?>
                    <?php echo '</table>'; ?>
                    <?php $this->printRESTEnabledHeader2(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php do_settings_fields('graphql-by-pop-settings', 'graphql-by-pop-settings-rest-enabled-section-2'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php /* GraphQL Extended Section */ ?>
                <div id="extended_graphql" class="tab-content">
                    <?php $this->printXTGraphQLEnabledHeader(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php do_settings_fields('graphql-by-pop-settings', 'graphql-by-pop-settings-extendedgraphql-enabled-section'); ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php /* Clients */ ?>
                <div id="clients" class="tab-content">
                <?php $this->printClientEnabledHeader(); ?>
                    <?php echo '<table class="form-table">'; ?>
                    <?php /*do_settings_fields('graphql-by-pop-settings', 'graphql-by-pop-settings-publicclients-enabled-section');*/ ?>
                    <?php echo '</table>'; ?>
                </div>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    protected function init(): void
    {
        $clientURLPlaceholder = __('Currently set to <a href="%1$s">%2$s</a>', 'graphql-by-pop');
        $concatSentencePlaceholder = __('%s. %s', 'graphql-by-pop');
        /**
         * Main section
         */
        add_settings_section(
            'graphql-by-pop-settings-main-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printMainSectionDescription'],
            'graphql-by-pop-settings'
        );
        add_settings_field(
            'graphql-by-pop-graphql-endpoint',
            __('GraphQL endpoint', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Make the GraphQL service available under the specified endpoint. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#graphql-endpoint'
                ),
                'id'    => 'graphql-by-pop-graphql-endpoint',
            )
        );
        // $restSupportedResources = sprintf(
        //     __('<em>Supported resources:</em> single post, author, tag, and page, and list of resources with slugs <code>%s</code>, <code>%s</code>, and <code>%s</code>, ', 'graphql-by-pop'),
        //     \POP_POSTS_ROUTE_POSTS,
        //     \POP_USERS_ROUTE_USERS,
        //     \POP_TAXONOMIES_ROUTE_TAGS
        // );
        add_settings_field(
            'graphql-by-pop-rest-endpoint',
            __('REST endpoint', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Endpoint to append at the end of a resource URL (single post, author, tag, page) to access its pre-defined data. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#rest-endpoint'
                ),
                    // sprintf(
                    //     '%s<br/>%s',
                    //     __('Endpoint appended at the end of the page URL', 'graphql-by-pop'),
                    //     $restSupportedResources
                    // ),
                'id'    => 'graphql-by-pop-rest-endpoint',
            )
        );
        add_settings_field(
            'graphql-by-pop-enable-extended-graphql',
            __('Enable extended GraphQL', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Supercharge the GraphQL API with additional features. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#extended-graphql'
                ),
                'id'    => 'graphql-by-pop-enable-extended-graphql',
            )
        );
        add_settings_field(
            'graphql-by-pop-namespacing',
            __('Enable schema namespacing', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Automatically namespace types and interfaces as to avoid potential naming clashes. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#namespacing'
                ),
                'id'    => 'graphql-by-pop-namespacing',
            )
        );
        add_settings_field(
            'graphql-by-pop-blockmetadata',
            __('Enable querying Gutenberg', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Add a field <code>blockMetadata</code> on type <code>%s</code> to retrieve the meta-data from its Guntenberg blocks. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    PostTypeResolver::NAME,
                    'https://graphql.getpop.org/wp/documentation/#gutenberg'
                ),
                'id'    => 'graphql-by-pop-blockmetadata',
            )
        );
        add_settings_field(
            'graphql-by-pop-public-graphiql',
            __('Public GraphiQL client URL path', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Make the GraphiQL client publicly available under the specified URL path. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#graphiql'
                ),
                'id'    => 'graphql-by-pop-public-graphiql',
            )
        );
        add_settings_field(
            'graphql-by-pop-public-voyager',
            __('Public "interactive schema" URL path', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-main-section',
            array(
                'label' => sprintf(
                    __('Make the "interactive schema" publicly available under the specified URL path. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#voyager'
                ),
                'id'    => 'graphql-by-pop-public-voyager',
            )
        );

        /**
         * REST section <= valid when REST enabled
         * Header 1
         */
        add_settings_section(
            'graphql-by-pop-settings-rest-enabled-section-1',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printRESTEnabledHeader1'],
            'graphql-by-pop-settings'
        );
        add_settings_field(
            'graphql-by-pop-rest-enable-querying',
            __('Enable to query custom fields', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-rest-enabled-section-1',
            array(
                'label' => sprintf(
                    __('Query custom fields in the REST endpoint through parameter <code>%s</code>. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    QueryInputs::QUERY,
                    'https://graphql.getpop.org/wp/documentation/#rest-custom-querying'
                ),
                'id'    => 'graphql-by-pop-rest-enable-querying',
            )
        );
        /**
         * REST section Header 2
         */
        add_settings_section(
            'graphql-by-pop-settings-rest-enabled-section-2',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printRESTEnabledHeader2'],
            'graphql-by-pop-settings'
        );
        add_settings_field(
            'graphql-by-pop-rest-enabled-post-fields',
            __('Post fields', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-rest-enabled-section-2',
            array(
                'label' => sprintf(
                    __('Default fields for the single post URL, and the post list page URL (with slug <code>%s</code>)', 'graphql-by-pop'),
                    \POP_POSTS_ROUTE_POSTS
                ),
                'id'    => 'graphql-by-pop-rest-enabled-post-fields',
            )
        );
        add_settings_field(
            'graphql-by-pop-rest-enabled-user-fields',
            __('User fields', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-rest-enabled-section-2',
            array(
                'label' => sprintf(
                    __('Default fields for the author URL, and the user list page URL (with slug <code>%s</code>)', 'graphql-by-pop'),
                    \POP_USERS_ROUTE_USERS
                ),
                'id'    => 'graphql-by-pop-rest-enabled-user-fields',
            )
        );
        add_settings_field(
            'graphql-by-pop-rest-enabled-tag-fields',
            __('Tag fields', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-rest-enabled-section-2',
            array(
                'label' => sprintf(
                    __('Default fields for the single tag URL, and the tag list page URL (with slug <code>%s</code>)', 'graphql-by-pop'),
                    \POP_TAXONOMIES_ROUTE_TAGS
                ),
                'id'    => 'graphql-by-pop-rest-enabled-tag-fields',
            )
        );
        add_settings_field(
            'graphql-by-pop-rest-enabled-page-fields',
            __('Page fields', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-rest-enabled-section-2',
            array(
                'label' => __('Default fields for the page URL'),
                'id'    => 'graphql-by-pop-rest-enabled-page-fields',
            )
        );

        /**
         * Extended GraphQL section <= valid when extended GraphQL enabled
         * Header 1
         */
        add_settings_section(
            'graphql-by-pop-settings-extendedgraphql-enabled-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printXTGraphQLEnabledHeader'],
            'graphql-by-pop-settings'
        );
        add_settings_field(
            'graphql-by-pop-extendedgraphql-cachecontrol',
            __('Cache-control max-age', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-extendedgraphql-enabled-section',
            array(
                'label' => sprintf(
                    __('HTTP Caching: Set the default max-age value in seconds for the Cache-Control header. From this value, the overall max-age from all requested fields will be calculated. Keep empty to disable. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    'https://graphql.getpop.org/wp/documentation/#cache-control'
                ),
                'id'    => 'graphql-by-pop-extendedgraphql-cachecontrol',
            )
        );
        add_settings_field(
            'graphql-by-pop-extendedgraphql-guzzle',
            __('Enable sending requests to external web services', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-settings',
            'graphql-by-pop-settings-extendedgraphql-enabled-section',
            array(
                'label' => sprintf(
                    __('Enable fields (%s) to fetch data from external web services. <a href="%s" target="documentation-site">See documentation</a>.', 'graphql-by-pop'),
                    '<code>'.implode(
                        '</code>'.__(',').'<code>',
                        [
                            'getJSON',
                            'getAsyncJSON',
                        ]
                    ).'</code>',
                    'https://graphql.getpop.org/wp/documentation/#external-services'
                ),
                'id'    => 'graphql-by-pop-extendedgraphql-guzzle',
            )
        );

        /**
         * Finally register all the settings
         */
        register_setting(
            'graphql-by-pop-settings',
            'graphql-by-pop-settings'
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
                <input type="checkbox" name="<?php echo 'graphql-by-pop-settings['.$name.']'; ?>" id="<?php echo $name; ?>" value="1" <?php checked(1, $value); ?> />
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
                <input type="text" name="<?php echo 'graphql-by-pop-settings['.$name.']'; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" />
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
                __('Please refer to the <a href="%s">documentation page</a> for detailed information on all features.', 'graphql-by-pop'),
                menu_page_url('graphql_by_pop_documentation', false)
            );*/echo sprintf(
                __('Please refer to the <a href="%s">documentation</a> for detailed information on all features.', 'graphql-by-pop'),
                'https://graphql.getpop.org/wp/documentation/'
            );?>
        </p>
        <?php
    }

    /**
     * Section header
     */
    function printRESTEnabledHeader1(): void
    {
        ?>
        <h2>
        <?php echo __('Settings if the REST endpoint is enabled', 'graphql-by-pop');?>
        </h2>
        <p>
            <?php echo sprintf(
                __('<strong>Note:</strong> Fields (default ones for the REST endpoint, and custom ones when querying through the URL) are defined using the <a href="%s">extended GraphQL syntax</a>.', 'graphql-by-pop'),
                'https://github.com/getpop/field-query'
            );?>
            <br/>
            <?php echo __('Examples for a post: ', 'graphql-by-pop');?>
        </p>
        <ol>
            <li>
                <?php echo __('<code>"id|title"</code> fetches the post\'s <code>ID</code> and <code>title</code> fields', 'graphql-by-pop')?>
            </li>
            <li>
                <?php echo __('<code>"id|title|author.id|name"</code> fetches the post\'s <code>ID</code> and <code>title</code> fields, and the post author\'s <code>ID</code> and <code>name</code> fields', 'graphql-by-pop')?>
            </li>
            <li>
                <?php echo __('<code>"id|title|comments.id|date|content|author.id|name"</code> fetches the post\'s <code>ID</code> and <code>title</code> fields, the post comments\' <code>ID</code>, <code>date</code> and <code>content</code> fields, and the comment\'s author\'s <code>ID</code> and <code>name</code> fields', 'graphql-by-pop')?>
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
            <?php echo __('Default REST fields ', 'graphql-by-pop');?>
        </h3-->
        <p><em>
            <?php echo __('Default REST fields for the different resources:', 'graphql-by-pop');?>
        </em></p>
        <?php
    }

    /**
     * Section header
     */
    function printXTGraphQLEnabledHeader(): void
    {
        ?>
        <h2>
        <?php echo __('Settings if extended GraphQL is enabled', 'graphql-by-pop');?>
        </h2>
        <?php
    }

    /**
     * Section header
     */
    function printClientEnabledHeader(): void
    {
        ?>
        <h2>
        <?php echo __('Settings if public clients are enabled', 'graphql-by-pop');?>
        </h2>
        <?php
    }
}
