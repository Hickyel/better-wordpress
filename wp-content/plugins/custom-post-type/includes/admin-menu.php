<?php

/**
 * Add admin menu.
 */
function csm_add_admin_menu() {
    add_menu_page(
        'Custom Post Types',
        'Custom Post Types',
        'manage_options',
        'custom-post-types',
        'csm_display_post_types_page',
        'dashicons-admin-post',
        20
    );

    add_submenu_page(
        'custom-post-types',
        'All Post Types',
        'All Post Types',
        'manage_options',
        'custom-post-types',
        'csm_display_post_types_page'
    );

    add_submenu_page(
        'custom-post-types',
        'Add New Post Type',
        'Add New',
        'manage_options',
        'add-new-post-type',
        'csm_display_add_new_page'
    );

    add_submenu_page(
        'custom-post-types', // parent slug
        'Edit Post Type',    // page title
        'Edit Post Type',    // menu title
        'manage_options',    // capability
        'edit-post-type',   // menu slug
        'csm_display_edit_page' // callback function
    );
}
add_action( 'admin_menu', 'csm_add_admin_menu' );
