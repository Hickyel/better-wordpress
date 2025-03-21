<?php

/**
 * Summary of bw_add_admin_menu
 * 
 * Add the admin menu and submenus
 * @return void
 */
function bw_add_admin_menu(){
    add_menu_page(
        __('Better WordPress',"better-wordpress"), //Titre de la page
        __('Better WP',"better-wordpress"), //Titre du menu
        'manage_options', //Capacité requise pour voir le menu
        'bw-main', //Slug de la page
        'bw_display_plugin_main_page', //Fonction qui affiche la page
        'dashicons-admin-generic', //Icône du menu
        3 //Position du menu
    );

    add_submenu_page(
        'bw-main',
        __('Settings Better WordPress',"better-wordpress"),
        __('Settings',"better-wordpress"),
        'manage_options',
        'bw-settings',
        'bw_display_settings_page'
    ); 
}

add_action('admin_menu','bw_add_admin_menu');

/**
 * Summary of bw_display_settings_page
 * Show the settings page
 * @return void
 */
function bw_display_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Paramètres Better WordPress', 'better-wordpress'); ?></h1>
        
    </div>
    <?php
}

/**
 * Summary of bw_display_plugin_main_page
 * Show the main page of the plugin
 * @return void
 */
function bw_display_plugin_main_page(){
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Better WordPress', 'better-wordpress'); ?></h1>
        <p><?php esc_html_e('Bienvenue sur la page principale du plugin Better WordPress.', 'better-wordpress'); ?></p>
    </div>
    <?php
}