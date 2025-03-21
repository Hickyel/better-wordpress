<?php
function csm_add_meta_box($post_type) {
    $post_types = get_option('csm_custom_post_types', array());
    if (!isset($post_types[$post_type]['meta_fields'])) {
        return;
    }
    
    $title = isset($post_types[$post_type]['meta_box_title']) 
        ? $post_types[$post_type]['meta_box_title'] 
        : __('Custom Fields', 'custom-post-type');
        
    add_meta_box(
        'csm_meta_box',
        $title,
        'csm_render_meta_box',
        $post_type,
        'normal',
        'high'
    );
}

function csm_render_meta_box($post) {
    $post_type = get_post_type($post);
    $post_types = get_option('csm_custom_post_types', array());
    
    if (!isset($post_types[$post_type]['meta_fields'])) {
        return;
    }
    
    $fields = $post_types[$post_type]['meta_fields'];
    wp_nonce_field('csm_meta_box', 'csm_meta_box_nonce');
    
    foreach ($fields as $field) {
        $value = get_post_meta($post->ID, $field, true);
        ?>
        <div class="csm-meta-field">
            <label for="<?php echo esc_attr($field); ?>">
                <?php echo esc_html(ucwords(str_replace('_', ' ', $field))); ?>
            </label>
            <input 
                type="text" 
                id="<?php echo esc_attr($field); ?>"
                name="<?php echo esc_attr($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                style="width: 100%; margin-bottom: 10px;"
            />
        </div>
        <?php
    }
}

function csm_save_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['csm_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['csm_meta_box_nonce'], 'csm_meta_box')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);
    $post_types = get_option('csm_custom_post_types', array());

    if (!isset($post_types[$post_type]['meta_fields'])) {
        return;
    }

    // Save each meta field
    foreach ($post_types[$post_type]['meta_fields'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta(
                $post_id,
                $field,
                sanitize_text_field($_POST[$field])
            );
        }
    }
}

add_action('save_post', 'csm_save_meta_box');