<?php

// Load classes and controllers for the admin part of the plugin
require_once plugin_dir_path(__FILE__) . 'classes/class-bw-admin.php';


// Create an instance of the admin class
$bw_admin = new BW_Admin();



/*if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'classes/class-bw-menus.php';
    $bw_admin = new BW_Menus();
}*/
function bw_enqueue_react_assets($hook_suffix) {

    error_log('hook_suffix: ' . $hook_suffix);
    $build_dir = plugin_dir_path(__FILE__) . '../assets/react-build/assets/';
    $build_url = plugin_dir_url(__FILE__) . '../assets/react-build/assets/';

    // JS
    $js_files = glob($build_dir . 'index-*.js');
    if ($js_files) {
        $js_url = $build_url . basename($js_files[0]);
        wp_enqueue_script('bw-react-app', $js_url, [], null, true);
    }

    // CSS
    $css_files = glob($build_dir . 'index-*.css');
    if ($css_files) {
        $css_url = $build_url . basename($css_files[0]);
        wp_enqueue_style('bw-react-style', $css_url, [], null);
    }
}
add_action('admin_enqueue_scripts', 'bw_enqueue_react_assets');
