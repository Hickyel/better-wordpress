<?php
/**
 * Plugin Name: Better WordPress
 * Plugin URI: 
 * Description: A plugin to enhance WordPress functionality
 * Version: 1.0.0
 * Author: Jocelyn Blais-Rochon
 * Author URI: 
 * Text Domain: better-wordpress
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package BetterWordPress
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if(is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin/admin-init.php';
}