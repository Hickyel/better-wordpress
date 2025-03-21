<?php


namespace JFB_Modules\Form_Record\Rest_Endpoints;

use JFB_Modules\Form_Record\Query_Views\Record_View_Forms;
use Jet_Form_Builder\Classes\Tools;
use Jet_Form_Builder\Exceptions\Query_Builder_Exception;
use Jet_Form_Builder\Rest_Api\Rest_Api_Endpoint_Base;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Fetch_Filters_Endpoint extends Rest_Api_Endpoint_Base {

	public static function get_rest_base() {
		return 'records-table/fetch-filters';
	}

	public static function get_methods() {
		return \WP_REST_Server::READABLE;
	}

	public function check_permission(): bool {
		return current_user_can( 'manage_options' );
	}

	public function run_callback( \WP_REST_Request $request ) {
		try {
			$form_ids = Record_View_Forms::values();
		} catch ( Query_Builder_Exception $exception ) {
			return new \WP_REST_Response( false, 404 );
		}

		$forms = Tools::get_forms_list_for_js(
			false,
			array(
				'include' => $form_ids,
			)
		);

		$forms = array_filter(
			$forms,
			function ( $item ) {
				return -1 !== $item['value'];
			}
		);

		$forms = array_values( $forms );

		return new \WP_REST_Response(
			array(
				'filters' => array(
					'form' => array(
						'options' => $forms,
					),
				),
			)
		);
	}
}
