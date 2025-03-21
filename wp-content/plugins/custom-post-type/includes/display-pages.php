<?php

function csm_display_post_types_page() {
    $post_types = get_option('csm_custom_post_types', array());
    $args = array();
    $meta_fields = $args['meta_fields'] ?? array();
    ?>
    <div class="wrap">
        <h1>All Custom Post Types <a href="<?php echo admin_url('admin.php?page=add-new-post-type'); ?>" class="page-title-action">Add New</a></h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Post Type</th>
                    <th>Singular Name</th>
                    <th>Plural Name</th>
                    <th>Menu Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($post_types)) {
                    foreach ($post_types as $post_type => $args) : 
                    ?>
                        <tr>
                            <td><?php echo esc_html($post_type); ?></td>
                            <td><?php echo esc_html($args['labels']['singular_name']); ?></td>
                            <td><?php echo esc_html($args['labels']['name']); ?></td>
                            <td><?php echo esc_html($args['labels']['menu_name']); ?></td>
                            <td>
                                <a href="<?php echo esc_url(add_query_arg(array(
                                    'page' => 'edit-post-type',
                                    'post_type' => $post_type,
                                ), admin_url('admin.php'))); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php 
                    endforeach;
                } else {
                    echo '<tr><td colspan="5">No custom post types found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Display add new post type page.
 */
function csm_display_add_new_page() {
    ?>
    <div class="wrap">
        <h1>Create Custom Post Type</h1>
        <form method="post" action="">
            <?php wp_nonce_field( 'csm_create_custom_post_type', 'csm_nonce' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="post_type">Post Type</label></th>
                    <td>
                        <input type="text" id="post_type" name="post_type" required placeholder="e.g. book" />
                        <p class="description">Unique slug for your custom post type.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="singular_name">Singular Name</label></th>
                    <td><input type="text" id="singular_name" name="singular_name" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="plural_name">Plural Name</label></th>
                    <td><input type="text" id="plural_name" name="plural_name" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="menu_name">Menu Name</label></th>
                    <td><input type="text" id="menu_name" name="menu_name" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="meta_fields">Meta Fields (comma separated)</label></th>
                    <td>
                        <input type="text" id="meta_fields" name="meta_fields" placeholder="field1, field2" />
                        <p class="description">Add as many fields as needed, separated by commas.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="meta_box_title">Meta Box Title</label></th>
                    <td><input type="text" id="meta_box_title" name="meta_box_title" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="meta_fields"><?php _e('Meta Fields', 'custom-post-type'); ?></label></th>
                    <td>
                        <div id="csm-meta-fields"></div>
                        <button type="button" class="button" id="csm-add-meta-field"><?php _e('Add Meta Field', 'custom-post-type'); ?></button>
                        <script></script></script>
                            document.getElementById('csm-add-meta-field').addEventListener('click', function() {
                                var container = document.getElementById('csm-meta-fields');
                                var field = document.createElement('div');
                                field.className = 'csm-meta-field';
                                field.innerHTML = '<input type="text" name="csm_meta_fields[]" /><button type="button" class="button csm-remove-meta-field"><?php _e('Remove', 'custom-post-type'); ?></button><br/><br/>';
                                container.appendChild(field);
                            });

                            document.addEventListener('click', function(e) {
                                if (e.target && e.target.className.includes('csm-remove-meta-field')) {
                                    e.target.parentElement.remove();
                                }
                            });
                        </script>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Create Custom Post Type' ); ?>
        </form>
    </div>
    <?php
}

function csm_display_edit_page() {
    if (!isset($_GET['post_type'])) {
        wp_die('Post type not specified');
        return;
    }

    $post_type = sanitize_text_field($_GET['post_type']);
    $post_types = get_option('csm_custom_post_types', array());

    if (!isset($post_types[$post_type])) {
        wp_die('Post type not found');
        return;
    }

    $args = $post_types[$post_type];
    $meta_fields = isset($args['meta_fields']) ? $args['meta_fields'] : array();
    $meta_box_title = isset($args['meta_box_title']) ? $args['meta_box_title'] : '';
    $capabilities = isset($args['capabilities']) ? $args['capabilities'] : array();
    
    // Convert capabilities to comma-separated string if it exists
    $capabilities_string = is_array($capabilities) ? implode(',', array_keys($capabilities)) : '';

    ?>
    <div class="wrap">
        <h1>Edit Custom Post Type</h1>
        <form method="post" action="">
            <?php wp_nonce_field('csm_edit_custom_post_type', 'csm_nonce'); ?>
            <input type="hidden" name="original_post_type" value="<?php echo esc_attr($post_type); ?>" />
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="post_type">Post Type</label></th>
                    <td>
                        <input type="text" id="post_type" name="post_type" value="<?php echo esc_attr($post_type); ?>" required />
                        <p class="description">Unique identifier for your custom post type.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="singular_name">Singular Name</label></th>
                    <td>
                        <input type="text" id="singular_name" name="singular_name" value="<?php echo esc_attr($args['labels']['singular_name']); ?>" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="plural_name">Plural Name</label></th>
                    <td>
                        <input type="text" id="plural_name" name="plural_name" value="<?php echo esc_attr($args['labels']['name']); ?>" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="menu_name">Menu Name</label></th>
                    <td>
                        <input type="text" id="menu_name" name="menu_name" value="<?php echo esc_attr($args['labels']['menu_name']); ?>" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="meta_box_title">Meta Box Title</label></th>
                    <td><input type="text" id="meta_box_title" name="meta_box_title" value="<?php echo esc_attr($args['meta_box_title'] ?? ''); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="capabilities">Capabilities</label></th>
                    <td>
                        <input type="text" id="capabilities" name="capabilities" value="<?php echo esc_attr($capabilities_string); ?>" placeholder="edit_post, read_post, delete_post" />
                        <p class="description">Comma-separated capabilities for the custom post type.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="meta_fields"><?php _e('Meta Fields', 'custom-post-type'); ?></label></th>
                    <td>
                        <div id="csm-meta-fields">
                            <?php foreach ($meta_fields as $field) : ?>
                                <div class="csm-meta-field">
                                    <input type="text" name="csm_meta_fields[]" value="<?php echo esc_attr($field); ?>" />
                                    <button type="button" class="button csm-remove-meta-field"><?php _e('Remove', 'custom-post-type'); ?></button>
                                    <br/><br/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="button" id="csm-add-meta-field"><?php _e('Add Meta Field', 'custom-post-type'); ?></button>
                        <script>
                            document.getElementById('csm-add-meta-field').addEventListener('click', function() {
                                var container = document.getElementById('csm-meta-fields');
                                var field = document.createElement('div');
                                field.className = 'csm-meta-field';
                                field.innerHTML = '<input type="text" name="csm_meta_fields[]" /><button type="button" class="button csm-remove-meta-field"><?php _e('Remove', 'custom-post-type'); ?></button><br/><br/>';
                                container.appendChild(field);
                            });

                            document.addEventListener('click', function(e) {
                                if (e.target && e.target.className.includes('csm-remove-meta-field')) {
                                    e.target.parentElement.remove();
                                }
                            });
                        </script>
                    </td>
                </tr>
            </table>
            <?php submit_button('Update Custom Post Type'); ?>
        </form>
    </div>
    <?php
}
