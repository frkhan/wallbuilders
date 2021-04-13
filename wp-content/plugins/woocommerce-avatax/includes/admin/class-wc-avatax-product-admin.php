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
 * Set up the AvaTax admin.
 *
 * @since 1.0.0
 */
class WC_AvaTax_Product_Admin {


	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// display the tax code field
		add_action( 'woocommerce_product_options_tax', array( $this, 'display_tax_code_field' ) );

		// display the HTS code field
		add_action( 'woocommerce_product_options_tax', array( $this, 'display_hts_code_field' ) );

		// save the product field values
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_meta' ), 10, 2 );

		// display the quick edit fields
		add_action( 'manage_product_posts_custom_column', array( $this, 'add_quick_edit_inline_values' ), 10 );
		add_action( 'woocommerce_product_quick_edit_end',  array( $this, 'display_quick_edit_fields' ) );

		// display and save the bulk edit fields
		add_action( 'woocommerce_product_bulk_edit_end', array( $this, 'display_bulk_edit_fields' ) );
		add_action( 'woocommerce_product_bulk_edit_save', array( $this, 'save_bulk_edit_fields' ) );

		// filter the product table query when a specific HTS code is desired
		add_filter( 'parse_query', array( $this, 'filter_by_hts_code' ) );
	}


	/** Tax Code Methods ******************************************************/


	/**
	 * Displays the tax code field.
	 *
	 * @internal
	 *
	 * @since 1.5.0
	 */
	public function display_tax_code_field() {

		woocommerce_wp_text_input(
			array(
				'id'            => '_wc_avatax_code',
				'wrapper_class' => 'hide_if_external',
				'label'         => __( 'Tax Code', 'woocommerce-avatax' ),
				'placeholder'   => wc_avatax()->get_tax_handler()->get_default_product_tax_code(),
			)
		);
	}


	/** HTS Code Methods ******************************************************/


	/**
	 * Displays the HTS code field.
	 *
	 * @internal
	 *
	 * @since 1.5.0
	 */
	public function display_hts_code_field() {

		// skip if Landed Cost feature is not available or configured
		if ( ! wc_avatax()->get_landed_cost_handler()->is_available() || ! wc_avatax()->get_landed_cost_handler()->get_hts_codes() ) {
			return;
		}

		woocommerce_wp_text_input(
			array(
				'id'            => '_wc_avatax_hts_code',
				'wrapper_class' => 'hide_if_external',
				'label'         => __( 'Harmonized Tariff Code', 'woocommerce-avatax' ),
				'description'   => '<a href="https://www.avalara.com/us/en/products/global-commerce-offerings/item-classification.html" target="_blank">' . __( 'Look up codes', 'woocommerce-avatax' ) . '</a>',
			)
		);
	}


	/** General Methods *******************************************************/


	/**
	 * Saves the product field values.
	 *
	 * @internal
	 *
	 * @param int $post_id product ID
	 * @since 1.5.0
	 */
	public function save_meta( $post_id ) {

		update_post_meta( $post_id, '_wc_avatax_code', sanitize_text_field( Framework\SV_WC_Helper::get_posted_value( '_wc_avatax_code' ) ) );

		// if there is a new HTS code value, clear the code cache
		if ( get_post_meta( $post_id, '_wc_avatax_hts_code', true ) !== Framework\SV_WC_Helper::get_posted_value( '_wc_avatax_hts_code' ) ) {
			wc_avatax()->get_landed_cost_handler()->clear_hts_cache();
		}

		update_post_meta( $post_id, '_wc_avatax_hts_code', $this->sanitize_hts_code( Framework\SV_WC_Helper::get_posted_value( '_wc_avatax_hts_code' ) ) );
	}


	/**
	 * Adds markup for the custom meta values so Quick Edit can fill the inputs.
	 *
	 * @since 1.5.0
	 *
	 * @param string $column the current column slug
	 */
	public function add_quick_edit_inline_values( $column ) {
		global $post;

		$product = is_object( $post ) ? wc_get_product( $post->ID ) : null;

		if ( $product && 'name' === $column ) : ?>

			<div id="wc_avatax_inline_<?php echo esc_attr( $product->get_id() ); ?>" class="hidden">
				<div class="tax_code"><?php echo esc_html( $product->get_meta( '_wc_avatax_code' ) ); ?></div>
				<div class="hts_code"><?php echo esc_html( $product->get_meta( '_wc_avatax_hts_code' ) ); ?></div>
			</div>

		<?php endif;
	}


	/**
	 * Displays the quick edit fields.
	 *
	 * @since 1.5.0
	 */
	public function display_quick_edit_fields() {

		include( wc_avatax()->get_plugin_path() . '/includes/admin/views/html-field-product-tax-code-quick-edit.php' );

		// do not display HTS code field if Landed Cost feature is not available or configured
		if ( ! wc_avatax()->get_landed_cost_handler()->is_available() || ! wc_avatax()->get_landed_cost_handler()->get_hts_codes() ) {
			return;
		}

		include( wc_avatax()->get_plugin_path() . '/includes/admin/views/html-field-product-hts-code-quick-edit.php' );
	}


	/**
	 * Displays the bulk edit fields.
	 *
	 * @since 1.5.0
	 */
	public function display_bulk_edit_fields() {

		include( wc_avatax()->get_plugin_path() . '/includes/admin/views/html-field-product-tax-code-bulk-edit.php' );

		// do not display HTS code field if Landed Cost feature is not available or configured
		if ( ! wc_avatax()->get_landed_cost_handler()->is_available() || ! wc_avatax()->get_landed_cost_handler()->get_hts_codes() ) {
			return;
		}

		include( wc_avatax()->get_plugin_path() . '/includes/admin/views/html-field-product-hts-code-bulk-edit.php' );
	}


	/**
	 * Saves the tax code bulk edit field.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Product $product product object
	 */
	public function save_bulk_edit_fields( $product ) {

		if ( ! empty( $_REQUEST['change_wc_avatax_code'] ) ) {

			$new_code     = sanitize_text_field( $_REQUEST['_wc_avatax_code'] );
			$current_code = $product->get_meta( '_wc_avatax_code' );

			// update to new tax code if different than current tax code
			if ( isset( $new_code ) && $new_code !== $current_code ) {
				update_post_meta( $product->get_id(), '_wc_avatax_code', $new_code );
			}
		}

		if ( ! empty( $_REQUEST['change_wc_avatax_hts_code'] ) ) {

			$new_code     = $this->sanitize_hts_code( $_REQUEST['_wc_avatax_hts_code'] );
			$current_code = $product->get_meta( '_wc_avatax_hts_code' );

			// update to new HTS code if different than current HTS code
			if ( isset( $new_code ) && $new_code !== $current_code ) {

				wc_avatax()->get_landed_cost_handler()->clear_hts_cache();

				update_post_meta( $product->get_id(), '_wc_avatax_hts_code', $new_code );
			}
		}
	}


	/**
	 * Filters the product table query when a specific HTS code is desired.
	 *
	 * @since 1.5.0
	 *
	 * @param \WP_Query $query query object
	 */
	public function filter_by_hts_code( $query ) {
		global $typenow;

		if ( 'product' === $typenow && Framework\SV_WC_Helper::get_requested_value( 'wc_avatax_hts_code' ) ) {
			$query->query_vars['meta_value'] = Framework\SV_WC_Helper::get_requested_value( 'wc_avatax_hts_code' );
			$query->query_vars['meta_key']   = '_wc_avatax_hts_code';
		}
	}


	protected function sanitize_hts_code( $code ) {

		return sanitize_text_field( str_replace( '.', '', $code ) );
	}


}
