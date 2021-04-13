<?php
/**
 * WooCommerce AvaTax
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce AvaTax to newer
 * versions in the future. If you wish to customize WooCommerce AvaTax for your
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-avatax/
 *
 * @author    SkyVerge
 * @copyright Copyright (c) 2016-2020, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

use SkyVerge\WooCommerce\PluginFramework\v5_5_0 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * Set up the Landed Cost admin.
 *
 * @since 1.5.0
 */
class WC_AvaTax_Landed_Cost_Admin extends \WC_Settings_Page {


	/**
	 * Constructs the class.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		$this->id    = 'avatax-landed-cost';
		$this->label = __( 'Landed Cost', 'woocommerce-avatax' );

		parent::__construct();
	}


	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {

		$sections = array(
			''        => __( 'Landed Cost Options', 'woocommerce-avatax' ),
			'classes' => __( 'Country Classifications', 'woocommerce-avatax' ),
		);

		return $sections;
	}


	/**
	 * Adds the Landed Cost section to the Shipping tab.
	 *
	 * @internal
	 *
	 * @since 1.5.0
	 *
	 * @param array $sections existing Shipping tab sections
	 * @return array $sections new Shipping tab sections
	 */
	public function add_settings_section( $sections ) {

		$sections[ $this->id ] = __( 'Landed Cost', 'woocommerce-avatax' );

		return $sections;
	}


	/** Output Methods ********************************************************/


	/**
	 * Displays the Landed Cost screens.
	 *
	 * @since 1.5.0
	 */
	public function output() {
		global $current_section;

		switch ( $current_section ) {

			case '':
				WC_Admin_Settings::output_fields( $this->get_settings() );
			break;

			case 'classes':
				$this->output_classes_screen();
			break;
		}
	}


	/**
	 * Displays the classifications screens.
	 *
	 * @since 1.5.0
	 */
	protected function output_classes_screen() {

		if ( $hts_code = Framework\SV_WC_Helper::get_requested_value( 'hts_code' ) ) {
			$this->output_code_edit_screen( $hts_code );
		} else {
			$this->output_list_screen();
		}
	}


	/**
	 * Displays the HTS code classes list screen.
	 *
	 * @since 1.5.0
	 */
	protected function output_list_screen() {
		global $hide_save_button;

		$columns = array(
			'hts-code'  => __( 'Harmonized Tariff Code', 'woocommerce-avatax' ),
			'codes'     => __( 'Classification Codes', 'woocommerce-avatax' ),
			'countries' => __( 'Destination Countries', 'woocommerce-avatax' ),
		);

		$classes   = wc_avatax()->get_landed_cost_handler()->get_classes();
		$hts_codes = wc_avatax()->get_landed_cost_handler()->get_hts_codes();

		include( wc_avatax()->get_plugin_path() . '/includes/admin/views/html-landed-cost-class-table.php' );

		$hide_save_button = true;
	}


	/**
	 * Displays the HTS code classes edit screen.
	 *
	 * @since 1.5.0
	 */
	protected function output_code_edit_screen( $hts_code ) {

		$columns = array(
			'code'      => __( 'Classification Code', 'woocommerce-avatax' ),
			'countries' => __( 'Destination Countries', 'woocommerce-avatax' ),
			'remove'    => '',
		);

		$classes = wc_avatax()->get_landed_cost_handler()->get_classes( $hts_code );

		$products = new WP_Query( array(
			'post_type'   => 'product',
			'post_status' => 'any',
			'meta_key'    => '_wc_avatax_hts_code',
			'meta_value'  => $hts_code,
		) );

		$products = array_filter( array_map( 'wc_get_product', $products->posts ) );

		include( wc_avatax()->get_plugin_path() . '/includes/admin/views/html-landed-cost-edit.php' );
	}


	/** Storage Methods *******************************************************/


	public function save() {
		global $current_section, $hide_save_button;

		switch ( $current_section ) {

			case '':
				WC_Admin_Settings::save_fields( $this->get_settings() );
			break;

			case 'classes':
				$this->save_classes();
			break;
		}

		delete_transient( 'wc_avatax_subscribed' );
	}


	protected function save_classes() {

		$hts_code       = Framework\SV_WC_Helper::get_requested_value( 'hts_code' );
		$classes        = isset( $_POST['wc_avatax_landed_cost_classes'] ) ? $_POST['wc_avatax_landed_cost_classes'] : array();
		$stored_classes = get_option( 'wc_avatax_landed_cost_classes', array() );

		$stored_classes[ $hts_code ] = array();

		foreach ( $classes as $data ) {

			if ( ! empty( $data['code'] ) ) {
				$stored_classes[ $hts_code ][ $data['code'] ] = array_filter( array_map( 'wc_clean', (array) $data['countries'] ) );
			}
		}

		update_option( 'wc_avatax_landed_cost_classes', $stored_classes );
	}


	public function get_settings() {

		$settings_description = '<div class="updated notice inline"><p>';

		$settings_description .= sprintf(
			/* translators: Placeholder: %1$s - <strong>, %2$s - </strong> */
			__( 'The Landed Cost feature is %1$sfor preview only%2$s. Please contact your Avalara representative if you’d like to test this feature.', 'woocommerce-avatax' ),
			'<strong>', '</strong>'
		);

		$settings_description .= '</p></div>' . __( 'Calculate Landed Cost duties, fees, and taxes.', 'woocommerce-avatax' );

		return array(

			array(
				'type' => 'title',
				'name' => __( 'Landed Cost Options', 'woocommerce-avatax' ),
				'desc' => $settings_description,
			),

			array(
				'id'      => 'wc_avatax_enable_landed_cost',
				'name'    => __( 'Enable/Disable', 'woocommerce-avatax' ),
				'desc'    => __( 'Enable Landed Cost calculation', 'woocommerce-avatax' ),
				'default' => 'no',
				'type'    => 'checkbox',
			),

			array(
				'id'       => 'wc_avatax_landed_cost_incoterms',
				'name'     => __( 'Importer of Record', 'woocommerce-avatax' ),
				'desc_tip' => __( 'Determines the party responsible for declaring the shipment to the customs authority in the destination country and paying all customs duty & import tax. If "Seller" is chosen, the customer will pay this as part of their order at checkout.', 'woocommerce-avatax' ),
				'options'  => array(
					'seller'   => __( 'Seller', 'woocommerce-avatax' ),
					'customer' => __( 'Customer', 'woocommerce-avatax' ),
				),
				'default' => 'seller',
				'type'    => 'select',
			),

			array(
				'type' => 'sectionend',
			),
		);
	}


}
