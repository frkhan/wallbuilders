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
 * The AvaTax API subscriptions response class.
 *
 * @since 1.5.0
 */
class WC_AvaTax_API_Subscriptions_Response extends \WC_AvaTax_API_Response {


	/** Auto Address subscription name **/
	const TYPE_AUTO_ADDRESS = 'AutoAddress';

	/** AvaTax Standard subscription name **/
	const TYPE_AVATAX_ST = 'AvaTaxST';

	/** AvaTax Pro subscription name **/
	const TYPE_AVATAX_PRO = 'AvaTaxPro';

	/** AvaTax Global subscription name **/
	const TYPE_AVATAX_GLOBAL = 'AvaTaxGlobal';

	/** Landed Cost subscription name **/
	const TYPE_LANDED_COST = 'AvaLandedCost';


	/**
	 * Gets the enabled subscriptions.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_subscriptions() {

		$subscriptions = $this->value;

		if ( ! is_array( $subscriptions ) ) {
			$subscriptions = array();
		}

		return $subscriptions;
	}


	/**
	 * Determines if the account has the Landed Cost subscription.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function has_landed_cost() {

		return $this->has_subscription( self::TYPE_LANDED_COST );
	}


	/**
	 * Determines if the account has the given subscription type.
	 *
	 * @since 1.5.0
	 *
	 * @param string $type subscription type
	 * @return bool
	 */
	public function has_subscription( $type ) {

		$subscriptions = wp_list_pluck( $this->get_subscriptions(), 'subscriptionDescription' );

		return in_array( $type, $subscriptions, true );
	}


}
