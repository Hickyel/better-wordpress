<?php
/**
 * Plugin Name: Custom Post Type
 * Plugin URI: https://example.com
 * Description: A plugin to create custom post types similar to JetEngine by Crocoblock.
 * Version: 1.0.0
 * Author: Code Source Marketing
 * Author URI: https://example.com
 * License: GPL2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load plugin text domain for localization.
function csm_load_textdomain() {
    load_plugin_textdomain( 'custom-post-type', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'csm_load_textdomain' );

// Flush rewrite rules on activation.
function csm_on_activation() {
    csm_register_all_custom_post_types();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'csm_on_activation' );

// Flush rewrite rules on deactivation.
function csm_on_deactivation() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'csm_on_deactivation' );

// Include necessary files.
require_once plugin_dir_path(__FILE__) . 'includes/register-post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-pages.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-box.php';

// Register custom post types on init.
add_action('init', 'csm_register_all_custom_post_types', 0);

// Add admin menu.
add_action( 'admin_menu', 'csm_add_admin_menu' );

// Flush rewrite rules if needed after post type registration.
function csm_after_post_type_registration() {
    // Only flush rewrite rules if needed
    if (get_option('csm_flush_rewrite_rules')) {
        flush_rewrite_rules();
        delete_option('csm_flush_rewrite_rules');
    }
}
add_action('init', 'csm_after_post_type_registration', 20);