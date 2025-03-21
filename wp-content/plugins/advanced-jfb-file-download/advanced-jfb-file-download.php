<?php
/**
 * Plugin Name: JetFormBuilder File Download Action
 * Plugin URI:  https://votre-site.com
 * Description: Ajoute une action post-soumission à JetFormBuilder permettant de télécharger un fichier sélectionné, en mode AJAX ou classique.
 * Version:     1.0
 * Author:      Votre Nom
 * Author URI:  https://votre-site.com
 * Text Domain: jfb-file-download-action
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Interdit l'accès direct.
}

// Assurez-vous que JetFormBuilder est actif
if ( ! class_exists( 'Jet_Form_Builder\Plugin' ) ) {
    return;
}

use Jet_Form_Builder\Actions\Types\Base;
use Jet_Form_Builder\Actions\Action_Handler;

if ( ! class_exists( 'JetFormBuilder_File_Download_Action' ) ) {
    /**
     * Classe de l'action de téléchargement.
     */
    class JetFormBuilder_File_Download_Action extends Base {

        /**
         * Retourne l'identifiant unique de l'action.
         *
         * @return string
         */
        public function get_id() {
            return 'file_download';
        }

        /**
         * Retourne le nom de l'action tel qu'il apparaît dans l'administration.
         *
         * @return string
         */
        public function get_name() {
            return __( 'Télécharger un fichier', 'jfb-file-download-action' );
        }

        /**
         * Fournit les informations pour l'affichage dans le catalogue des Post Submit Actions.
         *
         * @return array
         */
        public function get_action_data() {
            return [
                'title'       => $this->get_name(),
                'description' => __( 'Télécharge le fichier sélectionné après la soumission du formulaire.', 'jfb-file-download-action' ),
                'icon'        => plugin_dir_url( __FILE__ ) . 'assets/img/download-icon.svg',
                'docs_link'   => 'https://votre-site.com/docs/file-download-action',
                'category'    => 'utilities',
            ];
        }

        /**
         * Indique si l'action doit être masquée dans le catalogue.
         *
         * @return bool
         */
        public function is_hidden(): bool {
            return false;
        }

        /**
         * Définit les champs de configuration dans l'interface d'administration.
         *
         * @return array
         */
        public function get_fields() {
            return [
                'file_id' => [
                    'field_type'  => 'media',
                    'field_label' => __( 'Sélectionnez un fichier', 'jfb-file-download-action' ),
                    'desc'        => __( 'Choisissez le fichier à télécharger après la soumission.', 'jfb-file-download-action' ),
                ],
                'ajax_mode' => [
                    'field_type'  => 'checkbox',
                    'field_label' => __( 'Activer le téléchargement via AJAX', 'jfb-file-download-action' ),
                    'desc'        => __( 'Si activé, renvoie une URL pour déclencher le téléchargement via AJAX.', 'jfb-file-download-action' ),
                    'default'     => false,
                ],
            ];
        }

        /**
         * Exécute la logique après la soumission du formulaire.
         * Gère les modes AJAX et classique.
         *
         * @param array          $request Données soumises.
         * @param Action_Handler $handler Instance du gestionnaire d'actions de JetFormBuilder.
         */
        public function do_action( array $request, Action_Handler $handler ) {
            $settings = $this->settings;
            if ( empty( $settings['file_id'] ) ) {
                return;
            }

            $file_id   = $settings['file_id'];
            $file_path = get_attached_file( $file_id );
            if ( ! $file_path || ! file_exists( $file_path ) ) {
                return;
            }

            $ajax_mode = ! empty( $settings['ajax_mode'] );
            if ( $ajax_mode && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                $nonce = wp_create_nonce( 'jfb_file_download_' . $file_id );
                $download_url = add_query_arg( [
                    'file_id' => $file_id,
                    'nonce'   => $nonce,
                ], rest_url( 'jfb-file-download/v1/download' ) );

                if ( method_exists( $handler, 'set_response_data' ) ) {
                    $handler->set_response_data( [ 'download_url' => $download_url ] );
                } else {
                    header( 'X-JFB-Download-URL: ' . esc_url( $download_url ) );
                }
                return;
            }

            if ( ob_get_length() ) {
                ob_end_clean();
            }
            header( 'Content-Description: File Transfer' );
            header( 'Content-Type: application/octet-stream' );
            header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
            header( 'Expires: 0' );
            header( 'Cache-Control: must-revalidate' );
            header( 'Pragma: public' );
            header( 'Content-Length: ' . filesize( $file_path ) );
            flush();
            readfile( $file_path );
            exit;
        }
    }
}

/**
 * Enregistrement de la route REST pour le mode AJAX.
 */
add_action( 'rest_api_init', function() {
    register_rest_route( 'jfb-file-download/v1', '/download', [
        'methods'             => 'GET',
        'callback'            => 'jfb_file_download_rest_callback',
        'permission_callback' => '__return_true',
    ] );
} );

/**
 * Callback de la route REST pour le téléchargement via AJAX.
 *
 * @param WP_REST_Request $request
 * @return mixed
 */
function jfb_file_download_rest_callback( $request ) {
    $file_id = absint( $request->get_param( 'file_id' ) );
    $nonce   = $request->get_param( 'nonce' );

    if ( ! wp_verify_nonce( $nonce, 'jfb_file_download_' . $file_id ) ) {
        return new WP_Error( 'invalid_nonce', __( 'Nonce invalide', 'jfb-file-download-action' ), [ 'status' => 403 ] );
    }

    $file_path = get_attached_file( $file_id );
    if ( ! $file_path || ! file_exists( $file_path ) ) {
        return new WP_Error( 'file_not_found', __( 'Fichier non trouvé', 'jfb-file-download-action' ), [ 'status' => 404 ] );
    }

    if ( ob_get_length() ) {
        ob_end_clean();
    }
    header( 'Content-Description: File Transfer' );
    header( 'Content-Type: application/octet-stream' );
    header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . filesize( $file_path ) );
    flush();
    readfile( $file_path );
    exit;
}

/**
 * Enregistrement de l'action auprès de JetFormBuilder.
 */
add_action( 'jet-form-builder/actions/register', function( $manager ) {
    $manager->register_action_type( new JetFormBuilder_File_Download_Action() );
} );
