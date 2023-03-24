<?php
/**
 * REST API Shipping Zone locations controller
 *
 * Handles requests to the /shipping/zones/<id>/locations endpoint.
 *
 * @package WooCommerce\RestApi
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API Shipping Zone locations class.
 *
 * @package WooCommerce\RestApi
 * @extends WC_REST_Shipping_Zone_locations_V2_Controller
 */
class WC_REST_Shipping_Zone_locations_Controller extends WC_REST_Shipping_Zone_locations_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';
}
