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

    public function print(): void {
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
        add_settings_section(
            'graphql-by-pop-options-section',
            // The empty string ensures the render function won't output a h2.
            '',
            [$this, 'printPageDescription'],
            'graphql-by-pop-options'
        );
        add_settings_field(
            'graphql-by-pop-enable-rest',
            __('Enable REST endpoints', 'graphql-by-pop'),
            [$this, 'printField'],
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
            [$this, 'printField'],
            'graphql-by-pop-options',
            'graphql-by-pop-options-section',
            array(
                'label' => __('Supercharge your GraphQL API with additional features', 'graphql-by-pop'),
                'id'    => 'graphql-by-pop-enable-extended-graphql',
            )
        );
        add_settings_field(
            'graphql-by-pop-namespacing',
            __('Namespace types and interfaces', 'graphql-by-pop'),
            [$this, 'printField'],
            'graphql-by-pop-options',
            'graphql-by-pop-options-section',
            array(
                'label' => __('Automatically namespace all types and interfaces in the schema with their corresponding PHP package name, as to avoid conflicts among 3rd parties', 'graphql-by-pop'),
                'id'    => 'graphql-by-pop-namespacing',
            )
        );
        register_setting(
            'graphql-by-pop-options',
            'graphql-by-pop-options'
        );
    }

    /**
     * Display a checkbox field for a Gutenberg experiment.
     *
     * @since 6.3.0
     *
     * @param array $args ( $label, $id ).
     */
    function printField( $args ) {
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
     * Display the experiments section.
     *
     * @since 6.3.0
     */
    function printPageDescription() {
        ?>
        <p>
        <?php echo sprintf(
            __('Please refer to the <a href="%s">documentation page</a> for detailed information on all features.', 'graphql-by-pop'),
            menu_page_url('graphql_by_pop_documentation', false)
        );?>
        </p>
        <?php
    }
}
