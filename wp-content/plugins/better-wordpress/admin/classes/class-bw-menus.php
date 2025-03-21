<?php

/**
 * Class for managing admin menus
 *
 * @package Better_Wordpress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class BW_Menus
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_menus'));
    }

    /**
     * Register admin menus
     */
    public function register_menus()
    {
        add_menu_page(
            __('Better WordPress', 'better-wordpress'),
            __('Better WP', 'better-wordpress'),
            'manage_options',
            'better-wordpress',
            array($this, 'main_page'),
            'dashicons-admin-generic',
            30
        );

        add_submenu_page(
            'better-wordpress',
            __('Settings Better WordPress', "better-wordpress"),
            __('Settings', "better-wordpress"),
            'manage_options',
            'bw-settings',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Display main admin page
     */
    public function main_page()
    {
        echo '<div class="wrap">';
        echo '<h1>Hello from Better WordPress</h1>';
        echo '<div id="bw-react-root"></div>'; // <-- C’est ici que React se montera
        echo '</div>';
    }

    /**
     * Display settings page
     */
    public function display_settings_page()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('better-wordpress-settings');
                do_settings_sections('better-wordpress-settings');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }
}
