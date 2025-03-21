<?php
/**
 * Plugin Name: JetFormBuilder File Download Action
 * Plugin URI:  https://votre-site.com
 * Description: Ajoute une Post Submit Action à JetFormBuilder qui force le téléchargement d’un fichier sélectionné.
 * Version:     1.0
 * Author:      Votre Nom
 * Author URI:  https://votre-site.com
 * Text Domain: jfb-file-download
 * License:     GPL2
 */

// Sécurité : Empêche l'accès direct.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// IMPORTANT : Les instructions "use" doivent être placées au niveau supérieur du fichier.
use Jet_Form_Builder\Actions\Action_Handler;
use Jet_Form_Builder\Actions\Types\Base;

/**
 * Initialisation du plugin après le chargement de tous les plugins.
 */
add_action( 'plugins_loaded', 'jfb_file_download_action_init' );
function jfb_file_download_action_init() {

    // Vérifie que JetFormBuilder est actif.
    if ( ! class_exists( 'Jet_Form_Builder\Plugin' ) ) {
        return;
    }

    // Déclare la classe de l'action personnalisée si elle n'existe pas déjà.
    if ( ! class_exists( 'JFB_File_Download_Action' ) ) {

        class JFB_File_Download_Action extends Base {

            /**
             * Identifiant unique de l'action.
             */
            public function get_id() {
                return 'download_file';
            }

            /**
             * Nom affiché dans l'interface de JetFormBuilder.
             */
            public function get_name() {
                return __( 'Télécharger un fichier', 'jfb-file-download' );
            }

            /**
             * Définit les champs de configuration de l'action.
             * Ici, un champ de type "media" pour sélectionner le fichier.
             */
            public function get_fields() {
                return [
                    'file_id' => [
                        'field_type'  => 'media',
                        'field_label' => __( 'Sélectionnez un fichier', 'jfb-file-download' ),
                        'desc'        => __( 'Choisissez le fichier à télécharger après la soumission du formulaire.', 'jfb-file-download' ),
                    ],
                ];
            }

            /**
             * Méthode exécutée après la soumission du formulaire.
             *
             * @param array          $request Données soumises par le formulaire.
             * @param Action_Handler $handler Instance du gestionnaire d'actions.
             */
            public function do_action( array $request, Action_Handler $handler ) {
                $settings = $this->settings;

                if ( empty( $settings['file_id'] ) ) {
                    return;
                }

                $file_id   = $settings['file_id'];
                $file_path = get_attached_file( $file_id );

                // Vérifie que le fichier existe sur le serveur.
                if ( ! $file_path || ! file_exists( $file_path ) ) {
                    return;
                }

                // Nettoie le buffer si nécessaire.
                if ( ob_get_length() ) {
                    ob_end_clean();
                }

                // Envoie des en-têtes pour forcer le téléchargement.
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
     * Enregistre l'action auprès de JetFormBuilder.
     */
    add_action( 'jet-form-builder/actions/register', 'jfb_register_download_action' );
    function jfb_register_download_action( $manager ) {
        // Notez l'utilisation de register_action_type() pour enregistrer l'action.
        $manager->register_action_type( new JFB_File_Download_Action() );
    }
}
