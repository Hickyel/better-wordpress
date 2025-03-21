<?php
if(!defined('ABSPATH')){
    exit;
}

use \ElementorPro\Modules\Forms\Classes\Action_Base;

/**
 * Elementor Form Download Action
 * 
 * @since 1.0.0
 */
class Download_Action_After_Submit extends Action_Base{
    /**
     * Get action name
     * 
     * @since 1.0.0
     * @access public
     * @return string
     */
    public function get_name(){
        return 'download';
    }

    /**
     * Get action label
     * 
     * @since 1.0.0
     * @access public
     * @return string
     */
    public function get_label(){
        return esc_html__('Download', 'download-elementor-form-action');
    }


    /**
     * Register settings section
     * 
     * @since 1.0.0
     * @access public
     * @param \Elementor\Widget_Base $widget
     */
    public function register_settings_section($widget){
        $widget->start_controls_section(
            'section_download',
            [
                'label' => esc_html__('Download CSM', 'download-elementor-form-action'),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ]
            ]
        );

        // Ajouter un controle qui demande à l'usager l'url du fichier à téléchargé
        $widget->add_control(
            'file_url',
            [
                'label' => esc_html__('File Url', 'download-elementor-form-action'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__("Enter the URL of the file",'download-elementor-form-action'),
                'dynamic' => [
					'active' => true,
				],
            ]
        );

        // Ajouter un controle qui demande à l'usager si il veut rediriger le download sur une autre page
        $widget->add_control(
            'redirection',
            [
                'label' => esc_html__('Redirection','download-elementor-form-action'),
                'description' => esc_html__("Permet d'afficher le document sur une autre page"),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes','download-elementor-form-action'),
                'label_off' => esc_html__('No','download-elementor-form-action'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );




        $widget->end_controls_section();
    }

    
    /**
     * Run action.
     * 
     * @since 
     * @access public
     * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
     * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
     */
    public function run($record, $ajax_handler){
        // Obtenir les paramètres du formulaire
        $file_url = $record->get_form_settings('file_url');
        $redirection = $record->get_form_settings('redirection');

        if(empty($redirection)){
            $redirection = 'no';
        }

        if(empty($file_url)){
            return;
        }

        $url = $file_url['url'];
        $url = esc_url_raw($url);

        $urlBaseName = basename($url);

        $download_url = add_query_arg(
            ['download_file' => urlencode($url )],
            site_url()
        );
        $ajax_handler->add_response_data('redirect_download_endpoint', $download_url);

        $ajax_handler->add_response_data('redirect_download', $url);
        $ajax_handler->add_response_data('file_name', $urlBaseName);
        $ajax_handler->add_response_data('redirection', $redirection);


    }

    
    /**
     * On export
     * 
     * @since 1.0.0
     * @access public
     * @param array $element
     */
    public function on_export($element){}

}


add_action('elementor-pro/forms/pre_render','download_elementor_form_action_pre_render', 10, 2);
function download_elementor_form_action_pre_render($instance, $form){
    ?>
        <script>
            jQuery(document).ajaxSuccess(function(event, xhr, settings) {


                var responseData;
                try {
                    responseData = xhr.responseJSON;
                } catch (e) {
                    responseData = null;
                }
                // Si la réponse contient une donnée personnalisée (par exemple, redirect_url), utilisez-la
                if (responseData && responseData.data && responseData.data.data && responseData.data.data.redirect_download && responseData.data.data.file_name && responseData.data.data.redirection && responseData.data.data.redirect_download_endpoint) {

                    var url = responseData.data.data.redirect_download;
                    var fileName = responseData.data.data.file_name;
                    var redirection = responseData.data.data.redirection;
                    var urlEndpoint = responseData.data.data.redirect_download_endpoint;

                    console.log(redirection);
                    console.log(url);
                    console.log(fileName);

                    window.open(urlEndpoint, '_blank');

                    setTimeout(function(){
                        if(redirection == 'yes'){

                            // Ouvrir l'endpoint dans un nouvel onglet pour déclencher le téléchargement
                            window.open(url, '_blank');
                        }
                    }, 1000);


                }
            });
        </script>
    <?php
}

add_action('template_redirect', function() {
    if ( isset($_GET['download_file']) && !empty($_GET['download_file']) ) {
        $file_url = esc_url_raw( $_GET['download_file'] );
        $parsed_url = parse_url( $file_url );
        // Supposons que le fichier est local
        $local_file = ABSPATH . ltrim( $parsed_url['path'], '/' );
        if ( file_exists($local_file) ) {
            $file_size = filesize($local_file);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($local_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $file_size);
            flush();
            readfile($local_file);
            exit;
        } else {
            wp_die('Fichier introuvable.');
        }
    }
});

