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
 * The AvaTax API rate response class.
 *
 * @since 1.5.0
 */
class WC_AvaTax_API_Rate_Response extends \WC_AvaTax_API_Response {


	/**
	 * Gets the total estimated tax rate.
	 *
	 * @since 1.5.0
	 *
	 * @return float
	 */
	public function get_total_rate() {

		return $this->totalRate;
	}


	/**
	 * Gets the individual estimated tax rates.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_rates() {

		$rates = array();

		if ( is_array( $this->rates ) ) {

			$count = 1;

			foreach ( $this->rates as $rate ) {

				$rates[] = new WC_AvaTax_API_Tax_Rate( array(
					'code' => "{$count}-{$rate->name}",
					'name' => $rate->type,
					'rate' => $rate->rate,
				) );

				$count++;
			}
		}

		return $rates;
	}


}
