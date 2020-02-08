<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\AbstractMenuPage;

/**
 * Options menu page
 */
class OptionMenuPage extends AbstractMenuPage {

    /**
     * Indicate if the option is on. Made static so it can be used without instantiation
     *
     * @param string $name
     * @return boolean
     */
    public static function isOptionOn(string $name): bool
    {
        $options = get_option('graphql-by-pop-options');
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
        $options = get_option('graphql-by-pop-options');
        return $options[$name];
    }

    public function print(): void
    {
        ?>
        <div
            id="graphql-by-pop-options"
            class="wrap"
        >
        <h1><?php echo __('Options', 'graphql-by-pop'); ?></h1>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php settings_fields('graphql-by-pop-options'); ?>
            <?php do_settings_sections('graphql-by-pop-options'); ?>
            <?php submit_button(); ?>
        </form>
        </div>
        <?php
    }

    /**
     * Set up the experiments settings.
     *
     * @since 6.3.0
     */
    public function init(): void
    {
        /**
         * Main section
         */
        add_settings_section(
            'graphql-by-pop-options-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printMainSectionDescription'],
            'graphql-by-pop-options'
        );
        add_settings_field(
            'graphql-by-pop-enable-rest',
            __('Enable REST endpoints', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-options',
            'graphql-by-pop-options-section',
            array(
                'label' => __('Enable adding endpoint <code>/api/rest/</code> at the end of any post, page, author or tag pages, to retrieve the data for that resource', 'graphql-by-pop'),
                'id'    => 'graphql-by-pop-enable-rest',
            )
        );
        add_settings_field(
            'graphql-by-pop-enable-extended-graphql',
            __('Enable extended GraphQL', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-options',
            'graphql-by-pop-options-section',
            array(
                'label' => __('Supercharge your GraphQL API with additional features', 'graphql-by-pop'),
                'id'    => 'graphql-by-pop-enable-extended-graphql',
            )
        );
        add_settings_field(
            'graphql-by-pop-namespacing',
            __('Enable schema namespacing', 'graphql-by-pop'),
            [$this, 'printCheckboxField'],
            'graphql-by-pop-options',
            'graphql-by-pop-options-section',
            array(
                'label' => __('Make types and interfaces in the schema be namespaced using their corresponding PHP package name, as to avoid potential clashes among 3rd parties', 'graphql-by-pop'),
                'id'    => 'graphql-by-pop-namespacing',
            )
        );

        /**
         * REST section <= valid when REST enabled
         */
        add_settings_section(
            'graphql-by-pop-options-rest-enabled-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printRESTEnabledSectionDescription'],
            'graphql-by-pop-options'
        );
        add_settings_field(
            'graphql-by-pop-enable-rest',
            __('Post fields', 'graphql-by-pop'),
            [$this, 'printInputField'],
            'graphql-by-pop-options',
            'graphql-by-pop-options-rest-enabled-section',
            array(
                'label' => sprintf(
                    __('Default fields for the single post URL, and the post list page URL (with slug <code>%s</code>)'),
                    \POP_POSTS_ROUTE_POSTS
                ),
                'id'    => 'graphql-by-pop-rest-enabled-post-fields',
            )
        );

        /**
         * Finally register all the settings
         */
        register_setting(
            'graphql-by-pop-options',
            'graphql-by-pop-options'
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
                <input type="checkbox" name="<?php echo 'graphql-by-pop-options['.$name.']'; ?>" id="<?php echo $name; ?>" value="1" <?php checked(1, $value); ?> />
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
                <input type="text" name="<?php echo 'graphql-by-pop-options['.$name.']'; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" />
                <?php echo $label; ?>
            </label>
        <?php
    }

    /**
     * Display the experiments section.
     *
     * @since 6.3.0
     */
    function printMainSectionDescription()
    {
        ?>
        <p>
            <?php echo sprintf(
                __('Please refer to the <a href="%s">documentation page</a> for detailed information on all features.', 'graphql-by-pop'),
                menu_page_url('graphql_by_pop_documentation', false)
            );?>
        </p>
        <?php
    }

    /**
     * Display the experiments section.
     *
     * @since 6.3.0
     */
    function printRESTEnabledSectionDescription()
    {
        ?>
        <hr/>
        <h2>
        <?php echo __('Options if REST endpoints are enabled', 'graphql-by-pop');?>
        </h2>
        <p>
            <?php echo sprintf(
                __('<strong>Fields to retrieve for resources:</strong> define what fields to retrieve for a single resource or list of resources, when appending <code>%s</code> to the URL.', 'graphql-by-pop'),
                '/api/rest/'
            );?>
        <br/>
            <?php echo sprintf(
                __('Fields are defined using <a href="%s">this syntax</a>. Examples for a post: ', 'graphql-by-pop'),
                'https://github.com/getpop/field-query'
            );?>
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
}
