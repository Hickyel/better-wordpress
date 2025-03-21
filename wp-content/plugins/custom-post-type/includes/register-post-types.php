<?php

/**
 * Register a custom post type.
 */
function csm_register_custom_post_type($post_type, $args) {
    // Set show_in_menu to true to ensure it appears in admin menu
    $args['show_in_menu'] = true;
    
    // Set default args if not provided
    $default_args = array(
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => $post_type),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-post'
    );

    // Merge default args with provided args
    $args = wp_parse_args($args, $default_args);
    
    // Register the post type
    register_post_type($post_type, $args);
}

/**
 * Register all custom post types on init.
 */
function csm_register_all_custom_post_types() {
    $post_types = get_option('csm_custom_post_types', array());
    
    if (!empty($post_types)) {
        foreach ($post_types as $post_type => $args) {
            if (!post_type_exists($post_type)) {
                csm_register_custom_post_type($post_type, $args);
            }
        }
    }
}

// Remove the init hook from here as it's already added in the main plugin file
require_once plugin_dir_path(__FILE__) . 'meta-box.php';

add_action('add_meta_boxes', function($post_type) {
    csm_add_meta_box($post_type);
});

add_action('save_post', 'csm_save_meta_box');
