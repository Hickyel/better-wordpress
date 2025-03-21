<?php

// Load classes and controllers for the admin part of the plugin
require_once plugin_dir_path(__FILE__) . 'classes/class-bw-admin.php';


// Create an instance of the admin class
$bw_admin = new BW_Admin();



/*if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'classes/class-bw-menus.php';
    $bw_admin = new BW_Menus();
}*/
function bw_enqueue_react_admin_app($hook_suffix) {
    if ($hook_suffix !== 'toplevel_page_bw_options') {
        return;
    }

    $build_url = plugin_dir_url(__FILE__) . '../assets/react-build/';

    wp_enqueue_style(
        'bw-react-style',
        $build_url . 'assets/index.css',
        [],
        null
    );

    wp_enqueue_script(
        'bw-react-app',
        $build_url . 'assets/index.js',
        [],
        null,
        true
    );

    // Conteneur HTML pour monter React
    add_action('admin_footer', function () {
        echo '<div id="bw-react-root"></div>';
    });
}
add_action('admin_enqueue_scripts', 'bw_enqueue_react_admin_app');
