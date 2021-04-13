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
 * The base landed cost handler class.
 *
 * @since 1.5.0
 */
class WC_AvaTax_Landed_Cost_Handler {


	/**
	 * Gets the HTS code for a product.
	 *
	 * If a country is provided, it will try and get the fully qualified HTS code
	 * for the product and country, based on the plugin configuration.
	 *
	 * @since 1.5.0
	 *
	 * @param \WC_Product $product product object
	 * @param string $destination_country shipping destination country
	 * @return string $code the product's HTS code
	 */
	public function get_hts_code( WC_Product $product, $destination_country = '' ) {

		$code = $product->get_meta( '_wc_avatax_hts_code' );

		// if a variation, check for the parent product's HTS code
		if ( ! $code && $product->is_type( 'variation' ) ) {

			$product = wc_get_product( $product->get_parent_id( 'edit' ) );

			$code = $product->get_meta( '_wc_avatax_hts_code' );
		}

		if ( ! $code ) {

			$categories = get_the_terms( $product->get_id(), 'product_cat' );

			if ( is_array( $categories ) ) {

				foreach ( $categories as $category ) {

					if ( $category_code = get_term_meta( $category->term_id, 'wc_avatax_hts_code', true ) ) {
						$code = $category_code;
						break;
					}
				}
			}
		}

		if ( $code ) {

			if ( ! $destination_country ) {
				$destination_country = WC()->countries->get_base_country();
			}

			if ( $country_code = $this->get_country_class_code( $code, $destination_country ) ) {
				$code .= $country_code;
			}
		}

		/**
		 * Filters a product's HTS code.
		 *
		 * @since 1.5.0
		 *
		 * @param string $code HTS code
		 * @param \WC_Product $product product object
		 * @param string $destination_country shipping destination country
		 */
		return apply_filters( 'wc_avatax_landed_cost_product_hts_code', $code, $product, $destination_country );
	}


	/**
	 * Gets classification code configured for an HTS code & country.
	 *
	 * @since 1.5.0
	 *
	 * @param string $hts_code the product HTS code
	 * @param string $country destination country code
	 * @return string $code country-specific tariff code
	 */
	public function get_country_class_code( $hts_code, $country ) {

		$stored_codes = $this->get_classes( $hts_code );
		$code         = '';

		foreach ( $stored_codes as $class_code => $countries ) {

			if ( in_array( $country, $countries, true ) ) {
				$code = $class_code;
				break;
			}
		}

		return $code;
	}


	/**
	 * Gets all configured classification codes.
	 *
	 * @since 1.5.0
	 *
	 * @param string $hts_code specific HTS code
	 * @return array
	 */
	public function get_classes( $hts_code = '' ) {

		$classes = get_option( 'wc_avatax_landed_cost_classes', array() );

		// if looking for a specific HTS code
		if ( $hts_code ) {

			$classes = isset( $classes[ $hts_code ] ) ? $classes[ $hts_code ] : array();

		// otherwise, get 'em all
		} else {

			$hts_codes = $this->get_hts_codes();

			foreach ( $classes as $code => $data ) {

				if ( ! in_array( $code, $hts_codes ) ) {
					unset( $classes[ $code ] );
				}
			}
		}

		return $classes;
	}


	/**
	 * Gets all product HTS codes.
	 *
	 * @since 1.5.0
	 *
	 * @param bool $use_cache whether to use the cache or get fresh results
	 * @return array
	 */
	public function get_hts_codes( $use_cache = true ) {
		global $wpdb;

		$codes = $this->get_hts_cache();

		if ( ! $use_cache || empty( $codes ) ) {

			$product_codes = $wpdb->get_col( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_wc_avatax_hts_code'" );
			$term_codes    = $wpdb->get_col( "SELECT meta_value FROM $wpdb->termmeta WHERE meta_key = 'wc_avatax_hts_code'" );

			$codes = array_filter( array_unique( array_merge( $product_codes, $term_codes ) ) );

			$this->set_hts_cache( $codes );
		}

		return $codes;
	}


	/**
	 * Gets the HTS code cache.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_hts_cache() {

		return ( $codes = get_transient( 'wc_avatax_hts_codes' ) ) ? $codes : array();
	}


	/**
	 * Sets the HTS code cache.
	 *
	 * @since 1.5.0
	 *
	 * @param array $codes HTS codes
	 */
	public function set_hts_cache( $codes ) {

		set_transient( 'wc_avatax_hts_codes', $codes, 15 * DAY_IN_SECONDS );
	}


	/**
	 * Clears the HTS code cache.
	 *
	 * @since 1.5.0
	 */
	public function clear_hts_cache() {

		delete_transient( 'wc_avatax_hts_codes' );
	}


	/**
	 * Determines if landed cost is available.
	 *
	 * Currently not supported for tax-inclusive prices.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function is_available() {

		return $this->is_enabled() && ! wc_prices_include_tax();
	}


	/**
	 * Determines if landed cost is enabled.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function is_enabled() {

		/**
		 * Filters whether Landed Cost is enabled.
		 *
		 * @since 1.5.0
		 *
		 * @param bool $is_enabled()
		 */
		return apply_filters( 'wc_avatax_is_landed_cost_enabled', 'yes' === get_option( 'wc_avatax_enable_landed_cost', 'no' ) );
	}


	/**
	 * Gets the Landed Cost Incoterms.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_incoterms() {

		/**
		 * Filters the Landed Cost Incoterms.
		 *
		 * @since 1.5.0
		 *
		 * @param string $incoterms Landed Cost Incoterms
		 */
		return apply_filters( 'wc_avatax_landed_cost_incoterms', get_option( 'wc_avatax_landed_cost_incoterms', 'seller' ) );
	}


	/**
	 * Gets the Landed Cost shipping mode.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_shipping_mode() {

		/**
		 * Filters the Landed Cost shipping mode.
		 *
		 * @since 1.5.0
		 *
		 * @param string $shipping_mode Landed Cost shipping mode
		 */
		return apply_filters( 'wc_avatax_landed_cost_shipping_mode', get_option( 'wc_avatax_landed_cost_shipping_mode', 'ground' ) );
	}


	/**
	 * Adds action & filter hooks.
	 *
	 * @since 1.5.0
	 */
	public function add_hooks() {

		if ( ! $this->is_available() ) {
			return;
		}

		// add Landed Cost notes after an order is posted to Avalara
		add_action( 'wc_avatax_after_order_tax_calculated', array( $this, 'add_calculated_order_notes' ), 10, 2 );

		// replace VAT/Tax with Import Fees if there are landed costs
		add_filter( 'woocommerce_countries_tax_or_vat', [ $this, 'replace_tax_or_vat' ] );

		// reorder the taxes to make sure any landed costs are displayed first
		add_filter( 'woocommerce_cart_get_taxes', [ $this, 'reorder_taxes' ], 10, 2 );
	}


	/**
	 * Adds Landed Cost notes after an order is posted to Avalara.
	 *
	 * This ensures the merchant is better informed if duties are not calculated
	 * for some reason.
	 *
	 * @internal
	 *
	 * @since 1.5.0
	 *
	 * @param int $order_id order ID
	 * @param \WC_AvaTax_API_Tax_Response $response tax calculation response object
	 */
	public function add_calculated_order_notes( $order_id, $response ) {

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}

		$messages = $response->get_messages();

		foreach ( $messages as $message ) {

			if ( ! empty( $message->summary ) && ! empty( $message->refersTo ) && 'LandedCost' === $message->refersTo ) {
				$order->add_order_note( $message->summary );
			}
		}
	}


	/**
	 * Replaces VAT/Tax with Import Fees if there are landed costs
	 * and taxes are displayed as a single total.
	 *
	 * @since 1.10.0
	 *
	 * @param string $label the original label (Tax or VAT)
	 * @return string
	 */
	public function replace_tax_or_vat( $label ) {

		if ( 'single' === get_option( 'woocommerce_tax_total_display' ) && ! empty( WC()->cart->avatax_has_landed_costs ) ) {

			$label = __( 'Import Fees', 'woocommerce-avatax' );
		}

		return $label;
	}


	/**
	 * Reorders the taxes to make sure any landed costs are displayed first.
	 *
	 * @see WC_Cart::get_taxes()
	 *
	 * @since 1.10.0
	 *
	 * @param array $taxes the original taxes
	 * @param \WC_Cart $cart the cart
	 * @return array
	 */
	public function reorder_taxes( $taxes, $cart ) {

		if ( $cart->avatax_has_landed_costs ) {

			// search for a key containing LandedCost
			$landed_cost_tax = array_filter( $taxes, function ( $key ) {
				return strpos( $key, 'LandedCost' ) !== false;
			}, ARRAY_FILTER_USE_KEY );

			if ( ! empty( $landed_cost_tax ) ) {

				unset( $taxes[ key( $landed_cost_tax ) ] );

				//  add it to the beginning of the array because it needs to be displayed before other taxes
				$taxes = [ key( $landed_cost_tax ) => current( $landed_cost_tax ) ] + $taxes;
			}
		}

		return $taxes;
	}


}
