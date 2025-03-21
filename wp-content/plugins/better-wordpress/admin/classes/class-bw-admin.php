<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/jocelyn-Works
 * @since      1.0.0
 *
 * @package    Better_Wordpress
 * @subpackage Better_Wordpress/admin
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Require the BW_Menus class
require_once plugin_dir_path(__FILE__) . 'class-bw-menus.php';

/**
 * The admin-specific functionality of the plugin.
 */
class BW_Admin {

    /**
     * Instance of BW_Menus class
     *
     * @var BW_Menus
     */
    private $BW_Menus;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Constructor
        $this->init();
    }

    public function init(){
        // Initialize the admin area

        // Register the stylesheets for the admin area
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        // Register the JavaScript for the admin area
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        // Initialize the admin menus
        $this->BW_Menus = new BW_Menus();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Enqueue admin styles
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Enqueue admin scripts
    }


}