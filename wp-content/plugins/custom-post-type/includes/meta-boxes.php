<?php

/**
 * Add meta boxes.
 */
function csm_add_meta_boxes() {
    global $post;
    $post_type = $post->post_type;
    $post_types = get_option( 'csm_custom_post_types', array() );

    if ( isset( $post_types[ $post_type ]['meta_fields'] ) ) {
        foreach ( $post_types[ $post_type ]['meta_fields'] as $meta_field ) {
            add_meta_box(
                'csm_meta_box_' . $meta_field,
                ucfirst( str_replace( '_', ' ', $meta_field ) ),
                'csm_meta_box_callback',
                $post_type,
                'normal',
                'default',
                array( 'meta_field' => $meta_field )
            );
        }
    }
}

/**
 * Meta box callback.
 */
function csm_meta_box_callback( $post, $meta ) {
    $meta_field = $meta['args']['meta_field'];
    $value = get_post_meta( $post->ID, $meta_field, true );
    ?>
    <label for="<?php echo esc_attr( $meta_field ); ?>"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $meta_field ) ) ); ?></label>
    <input type="text" name="<?php echo esc_attr( $meta_field ); ?>" id="<?php echo esc_attr( $meta_field ); ?>" value="<?php echo esc_attr( $value ); ?>" />
    <?php
    wp_nonce_field( 'csm_save_meta_box', 'csm_meta_box_nonce' );
}

/**
 * Save meta boxes.
 */
function csm_save_meta_boxes( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['csm_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['csm_meta_box_nonce'], 'csm_save_meta_box' ) ) {
        return;
    }

    $post_type = get_post_type( $post_id );
    $post_types = get_option( 'csm_custom_post_types', array() );

    if ( isset( $post_types[ $post_type ]['meta_fields'] ) ) {
        foreach ( $post_types[ $post_type ]['meta_fields'] as $meta_field ) {
            if ( isset( $_POST[ $meta_field ] ) ) {
                update_post_meta( $post_id, $meta_field, sanitize_text_field( $_POST[ $meta_field ] ) );
            }
        }
    }
}

add_action( 'add_meta_boxes', 'csm_add_meta_boxes' );
add_action( 'save_post', 'csm_save_meta_boxes' );
