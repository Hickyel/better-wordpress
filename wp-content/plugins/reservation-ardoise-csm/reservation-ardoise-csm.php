<?php
/**
 * Plugin Name:         ReservationArdoiseCSM
 * Plugin URI:          https://codesource.marketing
 * Description:         Advance calculator for reservation
 * Version:             0.0.1
 * Author:              Code Source Marketing
 * Author URI:          https://codesource.marketing
 * Text Domain:         reservation-ardoise-csm
 * License:             GPL-3.0+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:         /languages
 * Requires at least:    6.1
 */


 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}
// Ajoutez ce code dans votre fichier functions.php ou dans un plugin personnalisé.

function my_addition_calculator_shortcode( $atts ) {
    ob_start();
    ?>
    <div class="addition-calculator" style="margin-bottom: 20px;">
        <label for="addend1">Nombre 1 :</label>
        <input type="number" id="addend1" name="addend1" value="0" style="margin-right: 10px;">
        
        <label for="addend2">Nombre 2 :</label>
        <input type="number" id="addend2" name="addend2" value="0" style="margin-right: 10px;">
        
        <label for="display_result">Résultat :</label>
        <!-- Champ visible en lecture seule pour l'affichage -->
        <input type="number" id="display_result" value="0" readonly style="background-color: #f5f5f5;">
        
    </div>
    <script>
        jQuery(document).ready(function(){
            const addend1 = document.getElementById('addend1');
            const addend2 = document.getElementById('addend2');
            const displayResult = document.getElementById('display_result');
            const hiddenResult = jQuery("input[name='addition_calculator']");
            

            console.log(hiddenResult);
            function updateResult() {
            let val1 = parseFloat(addend1.value) || 0;
            let val2 = parseFloat(addend2.value) || 0;
            let total = val1 + val2;
            displayResult.value = total;
            hiddenResult.val(total);
            }
            
            addend1.addEventListener('input', updateResult);
            addend2.addEventListener('input', updateResult);
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'addition_calculator', 'my_addition_calculator_shortcode' );


function my_multiselection_shortcode( $atts ) {
    // Définir ici vos options : clé => titre
    $options = array(
        'option_1' => 'Option 1',
        'option_2' => 'Option 2',
        'option_3' => 'Option 3',
        'option_4' => 'Option 4',
    );
    
    ob_start();
    ?>
    <div class="multiselection-wrapper">
        <p>Sélectionnez un ou plusieurs éléments :</p>
        <?php foreach ( $options as $key => $title ) : ?>
            <div>
                <label>
                    <input type="checkbox" class="multi-checkbox" value="<?php echo esc_attr( $title ); ?>">
                    <?php echo esc_html( $title ); ?>
                </label>
            </div>
        <?php endforeach; ?>
        

    </div>
    <script>
        jQuery(document).ready(function(){
            var checkboxes = document.querySelectorAll('.multi-checkbox');
            var hiddenField = jQuery('input[name="selected_items"]');
            var hiddenFieldJson = jQuery('input[name="selected_items_json"]')
            function updateHiddenField() {
                var selectedTitles = [];
                checkboxes.forEach(function(checkbox){
                    if (checkbox.checked) {
                        selectedTitles.push(checkbox.value);
                    }
                });
                // On joint les titres avec une virgule
                hiddenField.val(selectedTitles.join(', '));
                hiddenFieldJson.val(JSON.stringify(selectedTitles))
            }
            
            // Mise à jour du champ caché à chaque changement de sélection
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateHiddenField);
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'multiselection', 'my_multiselection_shortcode' );

/*add_filter( 'jet_form_builder/prepare_data', 'add_calculator_data', 10, 2 );
function add_calculator_data( $data, $form_id ) {
    if ( isset( $_POST['addition_result'] ) ) {
        // Assurez-vous de nettoyer la donnée reçue
        $data['addition_result'] = sanitize_text_field( $_POST['addition_result'] );
    }
    return $data;
}*/


?>