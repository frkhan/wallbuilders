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
 * The AvaTax API rate class.
 *
 * @since 1.5.0
 */
class WC_AvaTax_API_Tax_Rate {


	/** @var array $data rate data */
	protected $data = array(
		'code'  => '',
		'name'  => '',
		'rate'  => 0,
		'total' => 0,
	);


	/**
	 * Constructs the class.
	 *
	 * @since 1.5.0
	 *
	 * @param array $data {
	 *     The rate data
	 *
	 *     @type string $code rate code, i.e. 'MI STATE TAX'
	 *     @type string $name rate name, i.e. 'State' or 'City'
	 *     @type float  $rate decimal rate percentage
	 * }
	 */
	public function __construct( $data ) {

		$this->data = wp_parse_args( $data, $this->data );
	}


	/**
	 * Gets the rate code.
	 *
	 * This method removes spaces & prepends 'AVATAX-' to the code so it can be
	 * easily identified later.
	 *
	 * @since 1.5.0
	 *
	 * @return string the rate code, formatted like 'AVATAX-MI-STATE-TAX'
	 */
	public function get_code() {

		return WC_AvaTax_Tax_Handler::RATE_PREFIX . '-' . str_replace( ' ', '-', $this->data['code'] );
	}


	/**
	 * Gets the name.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_name() {

		return $this->data['name'];
	}


	/**
	 * Gets the label.
	 *
	 * This method appends a standard tax label, i.e. "Tax" or "VAT" which
	 * results in a full customer-friendly name, i.e. "State Tax"
	 *
	 * When landed costs are included, this method prepends "Tax - " for all
	 * rates other than the landed costs.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_label() {

		$has_landed_costs = ! empty( WC()->cart->avatax_has_landed_costs );

		if ( $has_landed_costs ) {

			if ( 'LandedCost' === $this->get_name() ) {

				$label = __( 'Duty', 'woocommerce-avatax' );

			} else {

				// prepend "Tax - "
				$label = trim( sprintf(
					/* translators: Placeholder: %s - tax name */
					__( 'Tax - %s', 'woocommerce-avatax' ),
					$this->get_name()
				) );
			}

		} else {

			$label = WC()->countries->tax_or_vat();

			if ( $this->get_name() ) {

				// append "Tax" or "VAT"
				$label = trim( sprintf(
					/* translators: Placeholders: %1$s - tax name, %2$s - tax type (VAT or Tax) */
					__( '%1$s %2$s', 'woocommerce-avatax' ),
					$this->get_name(),
					$label
				) );
			}
		}

		return apply_filters( 'wc_avatax_tax_label', $label );
	}


	/**
	 * Gets the rate percentage, as a decimal.
	 *
	 * @since 1.5.0
	 *
	 * @return float
	 */
	public function get_rate() {

		return (float) $this->data['rate'];
	}


	/**
	 * Gets the total tax amount.
	 *
	 * @since 1.5.0
	 *
	 * @return float
	 */
	public function get_total() {

		return (float) $this->data['total'];
	}


}
