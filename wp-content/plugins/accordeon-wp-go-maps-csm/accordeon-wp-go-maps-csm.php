<?php

/**
 * Plugin Name:         Accordéon par Categorie pour WP GO MAPS
 * Plugin URI:          https://codesource.marketing
 * Description:         Permet d'afficher un accordeon avec les Maps.
 * Version:             0.0.1
 * Author:              Code Source Marketing
 * Author URI:          https://codesource.marketing
 * Text Domain:         accordeon-wp-go-maps-csm
 * License:             GPL-3.0+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:         /languages
 * Requires at least:    6.1
 */
if(!defined('ABSPATH')){
    exit;
}

function accordeon_maps_csm_shortcode($atts)
{
    ob_start();


    $urlMarkers = 'https://galaxie.condos/wp-json/wpgmza/v1/markers';
    $urlCategories = 'https://galaxie.condos/wp-json/wpgmza/v1/categories';

    $urlListing = 'https://galaxie.condos/wp-json/wpgmza/v1/marker-listing/';
    $responseListing = wp_remote_get($urlListing);

    print_r($responseListing);

    $responseMarkers = wp_remote_get($urlMarkers);
    $responseCategories = wp_remote_get($urlCategories);

    $displayMarker = array();

    if(is_wp_error($responseMarkers) || is_wp_error($responseCategories) ){
        echo "Erreur de requête : " . $responseMarkers->get_error_message();
    }else{
        $bodyMarkers = wp_remote_retrieve_body($responseMarkers);
        $dataMarkers = json_decode($bodyMarkers,true);

        $bodyCategories = wp_remote_retrieve_body($responseCategories);
        $dataCategories = json_decode($bodyCategories,true);

        if(!array_key_exists("children",$dataCategories)){return;}

        $allCategories = $dataCategories["children"];


        foreach($dataMarkers as $key => $value){
            if(!array_key_exists("title",$value)){continue;}
            if(!array_key_exists("categories",$value)){continue;}
            
            $categoriesAssocie = $value["categories"];
            
            if(!array_key_exists(0,$categoriesAssocie)){continue;}

            $categoryAssocie = $categoriesAssocie[0];

            foreach($allCategories as $keyCat => $cat){
                if(!array_key_exists("id",$cat) || !array_key_exists("name",$cat)){continue;}

                if($cat["id"] == $categoryAssocie){
                   
                    // Ajouter au display
                    if(!array_key_exists($cat["name"],$displayMarker)){
                        $displayMarker[$cat["name"]] = array();
                    }

                    $displayMarker[$cat["name"]][] = $value["title"];

                }
            }

        }
    }
    print_r($displayMarker);

    ?>
    <style>
        .my-accordion .accordion-item { 
            border: 1px solid #ccc; 
            margin-bottom: 5px; 
        }
        .my-accordion .accordion-header { 
            background: #f2f2f2; 
            padding: 10px; 
            cursor: pointer; 
        }
        .my-accordion .accordion-content { 
            display: none; 
            padding: 10px; 
        }
    </style>

    <div class="my-accordion">
        <div class="accordion-item">
            <div class="accordion-header">Section 1</div>
            <div class="accordion-content">Contenu de la section 1.</div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Section 2</div>
            <div class="accordion-content">Contenu de la section 2.</div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Section 3</div>
            <div class="accordion-content">Contenu de la section 3.</div>
        </div>
        <?php

        ?>
    </div>

    <script>
        jQuery(document).ready(function($){
            $(".my-accordion .accordion-header").click(function(){
                var content = $(this).next(".accordion-content");
                // Si la section est déjà ouverte, on la ferme, sinon on ferme toutes et on ouvre celle-ci
                if(content.is(":visible")){
                    content.slideUp();
                } else {
                    $(".my-accordion .accordion-content").slideUp();
                    content.slideDown();
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('accordeon_maps_csm', 'accordeon_maps_csm_shortcode');


?>