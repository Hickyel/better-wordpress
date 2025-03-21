<?php

/**
 * Handle form submission.
 */
function csm_handle_form_submission() {
    if (!isset($_POST['csm_nonce'])) {
        return;
    }

    // Process form for creating new post type
    if (wp_verify_nonce($_POST['csm_nonce'], 'csm_create_custom_post_type')) {
        $post_type = sanitize_text_field($_POST['post_type']);
        $singular_name = sanitize_text_field($_POST['singular_name']);
        $plural_name = sanitize_text_field($_POST['plural_name']);
        $menu_name = sanitize_text_field($_POST['menu_name']);

        $labels = array(
            'name'                  => $plural_name,
            'singular_name'         => $singular_name,
            'menu_name'             => $menu_name,
            'name_admin_bar'        => $singular_name,
            'add_new'               => __('Add New', 'custom-post-type'),
            'add_new_item'          => __('Add New ' . $singular_name, 'custom-post-type'),
            'new_item'              => __('New ' . $singular_name, 'custom-post-type'),
            'edit_item'             => __('Edit ' . $singular_name, 'custom-post-type'),
            'view_item'             => __('View ' . $singular_name, 'custom-post-type'),
            'all_items'             => __('All ' . $plural_name, 'custom-post-type'),
            'search_items'          => __('Search ' . $plural_name, 'custom-post-type'),
            'parent_item_colon'     => __('Parent ' . $plural_name . ':', 'custom-post-type'),
            'not_found'             => __('No ' . strtolower($plural_name) . ' found.', 'custom-post-type'),
            'not_found_in_trash'    => __('No ' . strtolower($plural_name) . ' found in Trash.', 'custom-post-type'),
            'featured_image'        => _x('Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'archives'              => _x($singular_name . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'custom-post-type'),
            'insert_into_item'      => _x('Insert into ' . strtolower($singular_name), 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'custom-post-type'),
            'uploaded_to_this_item' => _x('Uploaded to this ' . strtolower($singular_name), 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'custom-post-type'),
            'filter_items_list'     => _x('Filter ' . strtolower($plural_name) . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'custom-post-type'),
            'items_list_navigation' => _x($plural_name . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'custom-post-type'),
            'items_list'            => _x($plural_name . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'custom-post-type'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => $post_type),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        );

        // Handle meta fields
        $meta_fields = array();
        if (isset($_POST['csm_meta_fields']) && is_array($_POST['csm_meta_fields'])) {
            foreach ($_POST['csm_meta_fields'] as $field) {
                $field = sanitize_key(str_replace(' ', '_', trim($field)));
                if (!empty($field)) {
                    $meta_fields[] = $field;
                }
            }
        }

        $args['meta_fields'] = array_unique($meta_fields);
        $args['meta_box_title'] = sanitize_text_field($_POST['meta_box_title'] ?? '');

        // Save the custom post type to the options table.
        $post_types = get_option('csm_custom_post_types', array());
        $post_types[$post_type] = $args;
        update_option('csm_custom_post_types', $post_types);

        csm_register_custom_post_type($post_type, $args);
    }
    // Process form for editing post type
    elseif (wp_verify_nonce($_POST['csm_nonce'], 'csm_edit_custom_post_type')) {
        $original_post_type = sanitize_text_field($_POST['original_post_type']);
        $post_type = sanitize_text_field($_POST['post_type']);
        $singular_name = sanitize_text_field($_POST['singular_name']);
        $plural_name = sanitize_text_field($_POST['plural_name']);
        $menu_name = sanitize_text_field($_POST['menu_name']);

        $labels = array(
            'name'                  => $plural_name,
            'singular_name'         => $singular_name,
            'menu_name'             => $menu_name,
            'name_admin_bar'        => $singular_name,
            'add_new'               => __('Add New', 'custom-post-type'),
            'add_new_item'          => __('Add New ' . $singular_name, 'custom-post-type'),
            'new_item'              => __('New ' . $singular_name, 'custom-post-type'),
            'edit_item'             => __('Edit ' . $singular_name, 'custom-post-type'),
            'view_item'             => __('View ' . $singular_name, 'custom-post-type'),
            'all_items'             => __('All ' . $plural_name, 'custom-post-type'),
            'search_items'          => __('Search ' . $plural_name, 'custom-post-type'),
            'parent_item_colon'     => __('Parent ' . $plural_name . ':', 'custom-post-type'),
            'not_found'             => __('No ' . strtolower($plural_name) . ' found.', 'custom-post-type'),
            'not_found_in_trash'    => __('No ' . strtolower($plural_name) . ' found in Trash.', 'custom-post-type'),
            'featured_image'        => _x('Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'custom-post-type'),
            'archives'              => _x($singular_name . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'custom-post-type'),
            'insert_into_item'      => _x('Insert into ' . strtolower($singular_name), 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'custom-post-type'),
            'uploaded_to_this_item' => _x('Uploaded to this ' . strtolower($singular_name), 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'custom-post-type'),
            'filter_items_list'     => _x('Filter ' . strtolower($plural_name) . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'custom-post-type'),
            'items_list_navigation' => _x($plural_name . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'custom-post-type'),
            'items_list'            => _x($plural_name . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'custom-post-type'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => $post_type),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        );

        // Handle meta fields
        $meta_fields = array();
        if (isset($_POST['csm_meta_fields']) && is_array($_POST['csm_meta_fields'])) {
            foreach ($_POST['csm_meta_fields'] as $field) {
                $field = sanitize_key(str_replace(' ', '_', trim($field)));
                if (!empty($field)) {
                    $meta_fields[] = $field;
                }
            }
        }

        $args['meta_fields'] = array_unique($meta_fields);
        $args['meta_box_title'] = sanitize_text_field($_POST['meta_box_title'] ?? '');

        // Save the custom post type to the options table.
        $post_types = get_option('csm_custom_post_types', array());
        $post_types[$post_type] = $args;
        update_option('csm_custom_post_types', $post_types);

        csm_register_custom_post_type($post_type, $args);
    }
}
