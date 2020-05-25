<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use PoP\Posts\TypeResolvers\PostTypeResolver;
use GraphQLAPI\GraphQLAPI\Settings\Options;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractMenuPage
{
    /**
     * Get the option value. Made static so it can be used without instantiation
     *
     * @param string $name
     * @return boolean
     */
    public static function getOptionValue(string $name): ?string
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        return $userSettingsManager->getSetting($name);
    }

    /**
     * Indicate if the option is on. Made static so it can be used without instantiation
     *
     * @param string $name
     * @return boolean
     */
    public static function isOptionOn(string $name): bool
    {
        return !empty(self::getOptionValue($name));
    }

    public function print(): void
    {
        /**
         * Override the box-shadow added to a:focus, when clicking on the tab
         */
        ?>
        <style>
            #graphql-api-settings .nav-tab-active:focus {
                box-shadow: none;
            }
        </style>
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
            <h1><?php \_e('GraphQL API — Settings', 'graphql-api'); ?></h1>
            <?php settings_errors(); ?>
            <?php $this->printMainSectionDescription(); ?>

            <h2 class="nav-tab-wrapper">
                <a href="#main" class="nav-tab nav-tab-active"><?php echo \__('Main', 'graphql-api'); ?></a>
                <a href="#graphql" class="nav-tab"><?php echo \__('GraphQL', 'graphql-api'); ?></a>
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

    public function initialize(): void
    {
        parent::initialize();

        \add_action('admin_init', function () {
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
                        '<code>' . implode(
                            '</code>' . __(',') . '<code>',
                            [
                                'getJSON',
                                'getAsyncJSON',
                            ]
                        ) . '</code>',
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
                Options::SETTINGS
            );
        });
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
                <input type="checkbox" name="<?php echo 'graphql-api-settings[' . $name . ']'; ?>" id="<?php echo $name; ?>" value="1" <?php checked(1, $value); ?> />
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
        $label = $args['label'] ? '<br/>' . $args['label'] : '';
        ?>
            <label for="<?php echo $args['id']; ?>">
                <input type="text" name="<?php echo 'graphql-api-settings[' . $name . ']'; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" />
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
