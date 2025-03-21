<?php
/**
 * Plugin Name: Download Elementor Form Action
 * Description: Download a file after submitting an Elementor form Addons.
 * Plugin URI:  https://codesource.marketing
 * Version:     1.0.0
 * Author:      Jocelyn Blais-Rochon
 * Author URI:  https://codesource.marketing
 * Text Domain: download-elementor-form-action
 * License:     GPL2
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.24.0
 * Elementor Pro tested up to: 3.24.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/** 
 * Add new action to Elementor Form
 * 
 * @since 1.0.0
 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
 */

 function add_new_download_action( $form_actions_registrar ) {
    include_once(__DIR__ . '/actions/download.php');  

    $form_actions_registrar->register(new \Download_Action_After_Submit());
 }

add_action( 'elementor_pro/forms/actions/register', 'add_new_download_action' );


