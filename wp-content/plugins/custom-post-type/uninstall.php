<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove stored custom post types
delete_option( 'csm_custom_post_types' );

// Remove all posts of custom post types
$post_types = get_option( 'csm_custom_post_types', array() );
foreach ( $post_types as $post_type => $args ) {
    $posts = get_posts( array(
        'post_type' => $post_type,
        'numberposts' => -1,
        'post_status' => 'any'
    ) );

    foreach ( $posts as $post ) {
        wp_delete_post( $post->ID, true );
    }
}