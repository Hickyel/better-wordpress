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
    public function register_menus() {
        add_menu_page(
            __('Better WordPress', 'better-wordpress'),
            __('Better WP', 'better-wordpress'),
            'manage_options',
            'bw_dashboard',
            [$this, 'display_dashboard_page'],
            'dashicons-admin-generic',
            20
        );
    
        add_submenu_page(
            'bw_dashboard',
            __('Réglages', 'better-wordpress'),
            __('Réglages', 'better-wordpress'),
            'manage_options',
            'bw_settings',
            [$this, 'display_settings_page']
        );
    }
    
    public function display_dashboard_page() {
        echo '<div class="wrap">';
        echo '<h1>Better WP Dashboard</h1>';
        echo '<div id="bw-dashboard-root"></div>'; // 🔹 React App 1
        echo '</div>';
    }
    
    public function display_settings_page() {
        echo '<div class="wrap">';
        echo '<h1>Better WP Réglages</h1>';
        echo '<div id="bw-settings-root"></div>'; // 🔹 React App 2
        echo '</div>';
    }
    
}
