<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Settings\Options;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Tokens;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractMenuPage
{
    use GraphQLAPIMenuPageTrait;

    public const SETTINGS_FIELD = 'graphql-api-settings';

    public function getMenuPageSlug(): string
    {
        return 'settings';
    }

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

    /**
     * Return all the modules with settings
     *
     * @return array
     */
    protected function getAllItems(): array
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(true, true);
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $items[] = [
                'module' => $module,
                // Replace "." with "-" since dots are not allowed as HTML IDs
                'id' => str_replace('.', '-', $moduleResolver->getID($module)),
                'name' => $moduleResolver->getName($module),
                'settings' => $moduleResolver->getSettings($module),
            ];
        }
        return $items;
    }

    protected function getSettingsFieldForModule(string $moduleID): string
    {
        return self::SETTINGS_FIELD . '-' . $moduleID;
    }

    public function print(): void
    {
        $items = $this->getAllItems();
        if (!$items) {
            _e('There are no items to be configured', 'graphql-api');
            return;
        }

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
                // $('#graphql-api-settings .tab-content').hide(); // Hide all tabs first
                // $('#graphql-api-settings #main').show(); //  Show the default tab

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
            <?php \settings_errors(); ?>
            <?php /*$this->printMainSectionDescription();*/ ?>

            <!-- Tabs -->
            <h2 class="nav-tab-wrapper">
            <?php
            foreach ($items as $item) {
                printf(
                    '<a href="#%s" class="nav-tab %s">%s</a>',
                    $item['id'],
                    $item['id'] == $items[0]['id'] ? 'nav-tab-active' : '',
                    $item['name']
                );
            }
            ?>
            </h2>

            <form method="post" action="options.php">
                <?php
                // Panels
                \settings_fields(self::SETTINGS_FIELD);
                foreach ($items as $item) {
                    $displayStyle = $item['id'] == $items[0]['id'] ? 'block' : 'none';
                    ?>
                    <div id="<?php echo $item['id'] ?>" class="tab-content" style="display: <?php echo $displayStyle ?>;">
                        <table class="form-table">
                            <?php \do_settings_fields(self::SETTINGS_FIELD, $this->getSettingsFieldForModule($item['id'])) ?>
                        </table>
                    </div>
                    <?php
                }
                \submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function initialize(): void
    {
        parent::initialize();

        \add_action('admin_init', function () {

            $items = $this->getAllItems();
            foreach ($items as $item) {
                $settingsFieldForModule = $this->getSettingsFieldForModule($item['id']);
                \add_settings_section(
                    $settingsFieldForModule,
                    // The empty string ensures the render function won't output a h2.
                    '',
                    null,
                    self::SETTINGS_FIELD
                );
                foreach ($item['settings'] as $itemSetting) {
                    \add_settings_field(
                        $itemSetting[Tokens::NAME],
                        $itemSetting[Tokens::TITLE],
                        [$this, 'printInputField'],
                        self::SETTINGS_FIELD,
                        $settingsFieldForModule,
                        array(
                            'label' => $itemSetting[Tokens::DESCRIPTION],
                            'id' => $itemSetting[Tokens::NAME],
                        )
                    );
                }
            }

            /**
             * Finally register all the settings
             */
            \register_setting(
                self::SETTINGS_FIELD,
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
    /*
    function printMainSectionDescription()
    {
        ?>
        <p>
            <?php echo sprintf(
            \__('Please refer to the <a href="%s">documentation</a> for detailed information on all features.', 'graphql-api'),
            'https://graphql.getpop.org/wp/documentation/'
            );?>
        </p>
        <?php
    }
    */

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
