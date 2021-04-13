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
 * The AvaTax API tax request class.
 *
 * @since 1.5.0
 */
class WC_AvaTax_API_Tax_Request extends \WC_AvaTax_API_Request {


	/** @var string Avalara company code */
	protected $company_code;


	/**
	 * Constructs the class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $company_code company code for this request
	 */
	public function __construct( $company_code ) {

		$this->company_code = $company_code;

		$this->path = '/transactions/create';
	}


	/** Cart methods **********************************************************/


	/**
	 * Calculate tax for a cart instance.
	 *
	 * @since 1.5.0
	 * @param \WC_Cart $cart cart object
	 */
	public function process_cart( WC_Cart $cart ) {

		parse_str( Framework\SV_WC_Helper::get_posted_value( 'post_data' ), $post_data );

		if ( empty( $post_data ) ) {
			$post_data = $_POST;
		}

		/**
		 * Filter the origin address at checkout.
		 *
		 * @since 1.1.0
		 * @param array $address origin address
		 * @param \WC_Cart $cart The cart instance
		 */
		$origin_address = apply_filters( 'wc_avatax_checkout_origin_address', wc_avatax()->get_tax_handler()->get_origin_address(), $cart );

		/**
		 * Filter the destination address at checkout.
		 *
		 * @since 1.1.0
		 * @param array $address destination address
		 * @param \WC_Cart $cart cart instance
		 */
		$destination_address = apply_filters( 'wc_avatax_checkout_destination_address', array(
			'address_1' => WC()->customer->get_shipping_address(),
			'address_2' => WC()->customer->get_shipping_address_2(),
			'city'      => WC()->customer->get_shipping_city(),
			'state'     => WC()->customer->get_shipping_state(),
			'country'   => WC()->customer->get_shipping_country(),
			'postcode'  => WC()->customer->get_shipping_postcode(),
		), $cart );

		// set all cart products as line items
		$lines = $this->prepare_cart_contents( $cart->cart_contents, $origin_address, $destination_address );

		// add any fees as line items
		$cart_fee_lines = [];
		$cart_fees      = $cart->get_fees();
		foreach ( $cart_fees as $cart_fee_key => $cart_fee ) {

			// when sending the number field to the API, hash cart fee ids to support ids longer than 30 characters (API limit)
			$cart_fee->number = wp_hash( $cart_fee->id );

			$cart_fee_lines[ $cart_fee_key ] = $cart_fee;
		}

		$lines = $this->prepare_fee_lines( $lines, $cart_fee_lines );

		// if the cart has shipping
		if ( $cart->needs_shipping() ) {

			// add the shipping line items, based on packages
			$lines = $this->prepare_cart_shipping_lines( $lines, WC()->shipping()->get_packages() );

			// if all of the chosen shipping methods are local-pickup, then destination is also origin
			if ( ! $this->shipping_has_destination( WC()->session->get( 'chosen_shipping_methods' ) ) ) {
				$destination_address = $origin_address;
			}
		}

		// Almost ready to send lovingly off to AvaTax!
		$args = array(
			'customerCode' => ( ! empty( $post_data['billing_email'] ) ) ? $post_data['billing_email'] : 'Guest',
			'lines'        => $lines,
			'origin'       => $origin_address,
			'destination'  => $destination_address,
		);

		// set the VAT if it exists
		if ( $vat = WC()->session->get( 'wc_avatax_vat_id', '' ) ) {
			$args['vat'] = $vat;
		}

		// Set the exemption if it exists
		if ( $exemption = get_user_meta( get_current_user_id(), 'wc_avatax_tax_exemption', true ) ) {
			$args['exemption'] = $exemption;
		}

		$this->set_params( $args );
	}


	/**
	 * Prepares cart contents for the AvaTax API.
	 *
	 * @since 1.5.0
	 *
	 * @param array $contents cart contents
	 * @param array $origin_address origin address
	 * @param array $destination_address destination address
	 * @param bool $tax_included whether the line prices include tax
	 * @return array $lines request line items
	 */
	protected function prepare_cart_contents( $contents, $origin_address = array(), $destination_address = array() ) {

		$items = array();

		$tax_included = 'yes' === get_option( 'woocommerce_prices_include_tax' );

		// set all the cart contents as line items
		foreach ( $contents as $key => $item ) {

			$amount = $tax_included ? $item['line_total'] + $item['line_tax'] : $item['line_total'];

			$items[ $key ] = array(
				'product'      => $item['data'],
				'quantity'     => $item['quantity'],
				'amount'       => max( (float) $amount, 0.00 ), // ensure there are no negative amounts sent
				'tax_included' => $tax_included,
				'origin'       => $origin_address,
				'destination'  => $destination_address,
			);
		}

		return $this->prepare_product_lines( $items, WC()->shipping()->get_packages() );
	}


	/**
	 * Prepare a set of shipping packages for the AvaTax API.
	 *
	 * @since 1.5.0
	 *
	 * @param array $existing_lines existing transaction lines
	 * @param array $packages shipping packages
	 * @return array $lines line items
	 */
	protected function prepare_cart_shipping_lines( $existing_lines, $packages ) {

		$shipping_lines = array();

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

		// parse the shipping packages
		foreach ( $packages as $package_key => $package ) {

			if ( isset( $chosen_shipping_methods[ $package_key ], $package['rates'][ $chosen_shipping_methods[ $package_key ] ] ) ) {

				$chosen_method = $package['rates'][ $chosen_shipping_methods[ $package_key ] ];

				$shipping_lines[] = $this->prepare_line( array(
					'number'      => "shipping_{$package_key}",
					'itemCode'    => $chosen_shipping_methods[ $package_key ],
					'taxCode'     => wc_avatax()->get_tax_handler()->get_default_shipping_tax_code(),
					'description' => $chosen_method->label,
					'amount'      => $chosen_method->cost,
				) );
			}
		}

		return array_merge( $existing_lines, $shipping_lines );
	}


	/**
	 * Get the calculated tax for the current cart at checkout.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.0
	 *
	 * @param \WC_Cart $cart cart object
	 */
	public function process_checkout( WC_Cart $cart ) {

		_deprecated_function(  __METHOD__, '1.5.0', 'WC_AvaTax_API_Tax_Request::process_cart()' );

		$this->process_cart( $cart );
	}


	/** Order methods *********************************************************/


	/**
	 * Get the calculated tax for a specific order.
	 *
	 * @since 1.0.0
	 * @param \WC_Order $order order object
	 * @param bool $commit Whether to commit the transaction to Avalara
	 */
	public function process_order( WC_Order $order, $commit ) {

		// Get the origin address
		// If tax has already been calculated for the order and we're sending the result to Avalara,
		// then continue with the origin address that was used at last calculation.
		if ( $commit && $order->get_meta( '_wc_avatax_origin_address' ) ) {
			$origin_address = $order->get_meta( '_wc_avatax_origin_address' );
		} else {
			$origin_address = wc_avatax()->get_tax_handler()->get_origin_address();
		}

		/**
		 * Filter the origin address.
		 *
		 * @since 1.1.0
		 * @param array $address origin address
		 * @param \WC_Cart $cart The cart instance
		 */
		$origin_address = apply_filters( 'wc_avatax_order_origin_address', $origin_address, $order );

		if ( $order->get_meta( '_wc_avatax_destination_address' ) ) {
			$destination_address = $order->get_meta( '_wc_avatax_destination_address' );
		} else {
			$destination_address = $order->get_address( 'shipping' );
		}

		// if no shipping address was set, use the billing address
		if ( empty( $destination_address['country'] ) ) {
			$destination_address = $order->get_address( 'billing' );
		}

		// if all of the chosen shipping methods are local-pickup, then destination is also origin
		if ( ! $this->order_has_destination( $order ) ) {
			$destination_address = $origin_address;
		}

		/**
		 * Filter the destination address.
		 *
		 * @since 1.1.0
		 * @param array $address destination address
		 * @param \WC_Cart $cart The cart instance
		 */
		$destination_address = apply_filters( 'wc_avatax_order_destination_address', $destination_address, $order );

		$args = array(
			'code'         => $order->get_order_key( 'edit' ),
			'order_number' => $order->get_order_number(),
			'customerCode' => $order->get_billing_email( 'edit' ),
			'currencyCode' => $order->get_currency(),
			'lines'        => $this->prepare_order_lines( $order, $origin_address, $destination_address ),
			'date'         => ( $date_created = $order->get_date_created( 'edit' ) ) ? $date_created->date( 'Y-m-d' ) : '',
			'origin'       => $origin_address,
			'destination'  => $destination_address,
			'type'         => $commit ? 'SalesInvoice' : 'SalesOrder',
			'commit'       => $this->commit_calculations(),
		);

		// ensure there are no negative line amounts for regular orders
		foreach ( $args['lines'] as $key => $line ) {

			if ( isset( $line['amount'] ) ) {
				$args['lines'][ $key ]['amount'] = max( (float) $line['amount'], 0.00 );
			}
		}

		// Set the VAT if it exists
		if ( $vat = $order->get_meta( '_billing_wc_avatax_vat_id' ) ) {
			$args['vat'] = $vat;
		}

		// Set the exemption if it exists
		if ( $exemption = $order->get_meta( '_wc_avatax_exemption' ) ) {
			$args['exemption'] = $exemption;
		} else {
			$args['exemption'] = get_user_meta( $order->get_user_id(), 'wc_avatax_tax_exemption', true );
		}

		$this->set_params( $args );
	}


	/**
	 * Prepares all line items for an order.
	 *
	 * @since 1.5.0
	 *
	 * @param \WC_Abstract_Order $order order or refund object
	 * @param array $origin_address origin address
	 * @param array $destination_address destination address
	 * @return array $lines request line items
	 */
	protected function prepare_order_lines( WC_Abstract_Order $order, $origin_address = array(), $destination_address = array() ) {

		// set all order products as line items
		$lines = $this->prepare_order_products( $order, $origin_address, $destination_address );

		// add any fees as line items
		$lines = $this->prepare_fee_lines( $lines, $order->get_fees() );

		// add the shipping lines
		$lines = $this->prepare_order_shipping_lines( $lines, $order );

		return $lines;
	}


	/**
	 * Prepares order items for the AvaTax API.
	 *
	 * @since 1.5.0
	 *
	 * @param \WC_Abstract_Order $order order or refund object
	 * @param array $origin_address origin address
	 * @param array $destination_address destination address
	 * @return array $lines request line items
	 */
	protected function prepare_order_products( WC_Abstract_Order $order, $origin_address = array(), $destination_address = array() ) {

		$items = array();

		foreach ( $order->get_items() as $item_id => $item ) {

			$product     = $item->get_product();
			$quantity    = $item->get_quantity();
			$total       = $item->get_total();
			$total_tax   = $item->get_total_tax();
			$origin      = $item->get_meta( '_wc_avatax_origin_address' );
			$destination = $item->get_meta( '_wc_avatax_destination_address' );
			$tax_code    = $item->get_meta( '_wc_avatax_tax_code' );

			if ( ! empty( $origin ) ) {
				$origin_address = $origin;
			}

			if ( ! empty( $origin ) ) {
				$destination_address = $destination;
			}

			if ( $order instanceof WC_Order_Refund ) {

				$original_order = wc_get_order( $order->get_parent_id( 'edit' ) );

				$tax_included = $original_order ? $original_order->get_prices_include_tax( 'edit' ) : false;

			} else {

				$tax_included = $order->get_prices_include_tax( 'edit' );
			}

			$items[ $item_id ] = array(
				'product'      => $product,
				'quantity'     => $quantity,
				'amount'       => $tax_included ? $total + $total_tax : $total,
				'tax_included' => $tax_included,
				'tax_code'     => $tax_code,
				'origin'       => $origin_address,
				'destination'  => $destination_address,
			);
		}

		return $this->prepare_product_lines( $items );
	}


	/**
	 * Prepare the shipping item lines for an order or refund.
	 *
	 * @since 1.5.0
	 *
	 * @param array $existing_lines transaction lines
	 * @param \WC_Abstract_Order $order order or refund object
	 * @return array $lines transaction lines with shipping methods added
	 */
	protected function prepare_order_shipping_lines( $existing_lines, WC_Abstract_Order $order ) {

		// add the shipping methods
		foreach ( $order->get_shipping_methods() as $item_id => $method ) {

			$method_id = $method->get_method_id();
			$name      = $method->get_name();
			$total     = $method->get_total();
			$tax_code  = $method->get_meta( '_wc_avatax_tax_code' );

			if ( ! $tax_code ) {
				$tax_code = wc_avatax()->get_tax_handler()->get_default_shipping_tax_code();
			}

			$existing_lines[] = $this->prepare_line( array(
				'number'      => "shipping_{$item_id}",
				'itemCode'    => $method_id,
				'taxCode'     => $tax_code,
				'description' => $name,
				'amount'      => $total,
			) );
		}

		return $existing_lines;
	}


	/**
	 * Determines if an order has a destination address.
	 *
	 * @since 1.5.0
	 *
	 * @param \WC_Abstract_Order $order order or refund object
	 * @return bool
	 */
	protected function order_has_destination( WC_Abstract_Order $order ) {

		$has_destination  = true;
		$shipping_methods = $order->get_shipping_methods();

		if ( ! empty( $shipping_methods ) ) {

			$shipping_method_ids = array();

			// add the shipping methods
			foreach ( $shipping_methods as $item_id => $method ) {
				$shipping_method_ids[] = $method['method_id'];
			}

			// if all of the chosen shipping methods are local-pickup, then destination is also origin
			$has_destination = $this->shipping_has_destination( $shipping_method_ids );
		}

		/**
		 * Filters whether an order has a destination address.
		 *
		 * @since 1.5.0
		 *
		 * @param bool $has_destination
		 * @param \WC_Abstract_Order $order order or refund object
		 */
		return apply_filters( 'wc_avatax_order_has_destination', $has_destination, $order );
	}


	/** Refund methods ********************************************************/


	/**
	 * Get the calculated tax for a refunded order.
	 *
	 * @since 1.0.0
	 * @param \WC_Order_Refund $refund order refund object
	 * @throws Framework\SV_WC_API_Exception
	 * @return false|void
	 */
	public function process_refund( WC_Order_Refund $refund ) {

		// Get the original order
		$order = wc_get_order( $refund->get_parent_id( 'edit' ) );

		if ( ! $order ) {
			return false;
		}

		if ( $order->get_meta( '_wc_avatax_origin_address' ) ) {
			$origin_address = $order->get_meta( '_wc_avatax_origin_address' );
		} else {
			$origin_address = $order->get_address( 'billing' );
		}

		if ( $order->get_meta( '_wc_avatax_destination_address' ) ) {
			$destination_address = $order->get_meta( '_wc_avatax_destination_address' );
		} else {
			$destination_address = $order->get_address( 'shipping' );
		}

		// if no shipping address was set, use the billing address
		if ( empty( $destination_address['country'] ) ) {
			$destination_address = $order->get_address( 'billing' );
		}

		// if all of the chosen shipping methods are local-pickup, then destination is also origin
		if ( ! $this->order_has_destination( $order ) ) {
			$destination_address = $origin_address;
		}

		$reason = $refund->get_reason();

		// Almost ready to send lovingly off to AvaTax!
		$args = array(
			'code'         => $order->get_order_key( 'edit' ) . '-' . $refund->get_id(),
			'order_number' => $order->get_order_number(),
			'customerCode' => $order->get_billing_email( 'edit' ),
			'currencyCode' => $order->get_currency(),
			'lines'        => $this->prepare_refund_lines( $refund, $origin_address, $destination_address ),
			'origin'       => $origin_address,
			'destination'  => $destination_address,
			'type'         => 'ReturnInvoice',
			'tax_date'     => $order->get_meta( '_wc_avatax_tax_date' ),
			'reason'       => $reason ? $reason : 'Refund',
			'commit'       => $this->commit_calculations(),
		);

		// if all that logic above results in no lines, bail
		if ( empty( $args['lines'] ) ) {
			throw new Framework\SV_WC_API_Exception( 'Refund amounts must be set per line item.' );
		}

		// Set the VAT if it exists
		if ( $vat = $order->get_meta( '_billing_wc_avatax_vat_id' ) ) {
			$args['vat'] = $vat;
		}

		if ( $exemption = $order->get_meta( '_wc_avatax_exemption' ) ) {
			$args['exemption'] = $exemption;
		}

		$this->set_params( $args );
	}


	/**
	 * Prepares all line items for a refund.
	 *
	 * @since 1.5.0
	 *
	 * @see \WC_AvaTax_API_Tax_Request::prepare_order_lines()
	 *
	 * @param \WC_Order_Refund $refund refund object
	 * @param array $origin_address origin address
	 * @param array $destination_address destination address
	 * @return array $lines request line items
	 */
	protected function prepare_refund_lines( WC_Order_Refund $refund, $origin_address = array(), $destination_address = array() ) {

		// set all refund products, fees, and shipping as line items
		$lines = $this->prepare_order_lines( $refund, $origin_address, $destination_address );

		foreach ( $lines as $key => $line ) {

			// only send lines that have an amount
			if ( $line['amount'] == 0 ) {
				unset( $lines[ $key ] );
				continue;
			}

			// ensure all quantities are positive
			$lines[ $key ]['quantity'] = abs( $line['quantity'] );

			// ensure all amounts are negative
			$lines[ $key ]['amount'] = abs( $line['amount'] ) * -1;
		}

		return $lines;
	}


	/**
	 * Set the calculation request params.
	 *
	 * @since 1.0.0
	 * @param array $args {
	 *     The AvaTax API parameters.
	 *
	 *     @type int    $id           The unique transaction ID.
	 *     @type string $order_number The order number for reference
	 *     @type string $customer     The unique customer identifier.
	 *     @type array  $addresses    The origin and destination addresses. @see `WC_AvaTax_API::prepare_address()` for formatting.
	 *     @type array  $lines        The line items used for calculation. @see `WC_AvaTax_API::prepare_line()` for formatting.
	 *     @type string $date         The document creation date. Format: YYYY-MM-DD. Default: the current date.
	 *     @type string $tax_date     The effective tax date. Format: YYYY-MM-DD.
	 *     @type string $type         The type of transaction requested of AvaTax. Accepts `checkout`, `payment`, or `refund`. Default: `checkout`.
	 *     @type string $currency     The calculation currency code. Default: the shop currency code.
	 *     @type string $vat          The customer's VAT ID.
	 *     @type bool   $exemption    Whether the transaction has tax exemption.
	 *     @type bool   $commit       Whether to commit this calculation as a finalized transaction. Default: `false`.
	 * }
	 */
	public function set_params( $args ) {

		$args = wp_parse_args( $args, array(
			'code'         => '',
			'order_number' => 0,
			'customerCode' => '',
			'currencyCode' => get_woocommerce_currency(),
			'date'         => date( 'Y-m-d', current_time( 'timestamp' ) ),
			'lines'        => array(),
			'origin'       => array(),
			'destination'  => array(),
			'tax_date'     => '',
			'type'         => 'SalesOrder',
			'vat'          => false,
			'exemption'    => false,
			'commit'       => false,
		) );

		// Set the base request params
		$data = array(
			'code'         => Framework\SV_WC_Helper::str_truncate( $args['code'], 50, '' ),
			'companyCode'  => $this->get_company_code(),
			'customerCode' => Framework\SV_WC_Helper::str_truncate( $args['customerCode'], 50, '' ),
			'currencyCode' => Framework\SV_WC_Helper::str_truncate( $args['currencyCode'], 3, '' ),
			'date'         => $args['date'],
			'lines'        => $args['lines'],
			'addresses'    => $this->prepare_address_data( $args['origin'], $args['destination'] ),
			'type'         => $args['type'],
		);

		// remove an line-level addresses if they match the main order addresses
		$data = $this->remove_duplicate_addresses( $data );

		if ( $args['order_number'] ) {
			$data['purchaseOrderNo'] = Framework\SV_WC_Helper::str_truncate( $args['order_number'], 50, '' );
		}

		// Set a tax date override if required
		if ( $args['tax_date'] && $args['tax_date'] !== $args['date'] ) {

			$data['taxOverride'] = array(
				'type'    => 'taxDate',
				'taxDate' => $args['tax_date'],
				'reason'  => ! empty( $args['reason'] ) ? $args['reason'] : '',
			);
		}

		// Set the VAT if it exists
		if ( $vat = $args['vat'] ) {
			$data['businessIdentificationNo'] = Framework\SV_WC_Helper::str_truncate( $vat, 25, '' );
		}

		// Set the exemption if it exists
		if ( $args['exemption'] ) {
			$data['customerUsageType'] = $args['exemption'];
		}

		// add landed cost data
		if ( wc_avatax()->get_landed_cost_handler()->is_available() ) {
			$data['isSellerImporterOfRecord'] = 'seller' === wc_avatax()->get_landed_cost_handler()->get_incoterms();
		}

		// Should this be committed?
		$data['commit'] = $args['commit'];

		$this->method = 'POST';

		/**
		 * Filters the new tax transaction data.
		 *
		 * @since 1.5.0
		 *
		 * @param array $data
		 */
		$this->data = apply_filters( 'wc_avatax_api_tax_transaction_request_data', $data );
	}


	/** Product Helpers *******************************************************/


	/**
	 * Prepares a list of products for the AvaTax API.
	 *
	 * @since 1.5.0
	 *
	 * @param array $items {
	 *     Product items to prepare as lines.
	 *
	 *     @type \WC_Product $product     product object
	 *     @type string      $tax_code    product tax code
	 *     @type int         $quantity    line quantity
	 *     @type float       $amount      extended line total
	 *     @type array       $origin      origin address
	 *     @type array       $destination destination address
	 * }
	 * @param array $packages list of WooCommerce packages, as generated by \WC_Shipping
	 * @return array $lines API-formatted lines
	 */
	protected function prepare_product_lines( $items, $packages = array() ) {

		$lines = $addresses = array();

		foreach ( $items as $key => $item ) {

			$product = $item['product'];

			if ( ! $product instanceof WC_Product || ! $product->is_taxable() ) {
				continue;
			}

			// Set the sku if it exists. Otherwise, use the variation or product ID
			if ( $product->get_sku() ) {
				$sku = $product->get_sku();
			} else {
				$sku = $product->get_id();
			}

			// if packages are provided, check for per-package destinations and local-pickup
			if ( ! empty( $packages ) ) {

				$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

				// parse the shipping packages
				foreach ( $packages as $package_key => $package ) {

					if ( ! isset( $chosen_shipping_methods[ $package_key ], $package['rates'][ $chosen_shipping_methods[ $package_key ] ] ) ) {
						continue;
					}

					$product_ids   = wp_list_pluck( $package['contents'], 'product_id' );
					$variation_ids = wp_list_pluck( $package['contents'], 'variation_id' );

					// if the product is not in this package, on to the next one
					if ( ! in_array( $product->get_id(), $product_ids, true ) && ! in_array( $product->get_id(), $variation_ids, true ) ) {
						continue;
					}

					// if this is a local pickup package, use the origin address
					$item['destination'] = Framework\SV_WC_Helper::str_starts_with( $chosen_shipping_methods[ $package_key ], 'local_pickup' ) ? $item['origin'] : $package['destination'];

					/**
					 * Filters a product destination.
					 *
					 * @since 1.5.0
					 *
					 * @param array $destination destination address
					 * @param \WC_Product $product product object
					 * @param array $package shipping package
					 */
					$item['destination'] = apply_filters( 'wc_avatax_product_destination', $item['destination'], $product, $package );
				}
			}

			$tax_code = ( ! empty( $item['tax_code'] ) ) ? $item['tax_code'] : $this->prepare_product_tax_code( $product );
			$hts_code = ( ! empty( $item['hts_code'] ) ) ? $item['hts_code'] : wc_avatax()->get_landed_cost_handler()->get_hts_code( $product, $item['destination']['country'] );

			$line = $this->prepare_line( array(
				'number'      => $key,
				'itemCode'    => $sku,
				'taxCode'     => $tax_code,
				'HTSCode'     => $hts_code,
				'weight'      => $product->get_weight(),
				'description' => $product->get_title(),
				'quantity'    => $item['quantity'],
				'amount'      => $item['amount'],
				'taxIncluded' => $item['tax_included'],
				'origin'      => $item['origin'],
				'destination' => $item['destination'],
			) );

			/**
			 * Filter a product's line data.
			 *
			 * @since 1.5.0
			 *
			 * @param array $line line data
			 * @param \WC_Product $product product object
			 */
			$lines[] = apply_filters( 'wc_avatax_api_product_line_data', $line, $product );

			$addresses[ $key ] = array(
				'origin'      => $item['origin'],
				'destination' => $item['destination'],
			);
		}

		// store the item addresses for later use
		if ( WC()->session ) {
			WC()->session->set( 'wc_avatax_line_addresses', $addresses );
		}

		return $lines;
	}


	/**
	 * Get a product's tax code with fallbacks.
	 *
	 * @since 1.0.0
	 * @param WC_Product $product The product object.
	 * @return string $tax_code The tax code.
	 */
	protected function prepare_product_tax_code( WC_Product $product ) {

		$tax_code = '';

		// Check for a product-specific tax code
		if ( $product->get_meta( '_wc_avatax_code' ) ) {

			$tax_code = $product->get_meta( '_wc_avatax_code' );

		// If a variation, check for the parent product's tax code
		} elseif ( $product->is_type( 'variation' ) ) {

			$product = wc_get_product( $product->get_parent_id( 'edit' ) );

			if ( $product->get_meta( '_wc_avatax_code' ) ) {
				$tax_code = $product->get_meta( '_wc_avatax_code' );
			}

		}

		// If none was found yet, check the product's category
		if ( ! $tax_code ) {

			$categories = get_the_terms( $product->get_id(), 'product_cat' );

			if ( is_array( $categories ) ) {

				foreach ( $categories as $category ) {

					if ( $category_tax_code = get_term_meta( $category->term_id, 'wc_avatax_tax_code', true ) ) {
						$tax_code = $category_tax_code;
						break;
					}
				}
			}
		}

		// Use the default tax code as a fallback
		if ( ! $tax_code ) {
			$tax_code = wc_avatax()->get_tax_handler()->get_default_product_tax_code();
		}

		/**
		 * Filter the product tax code.
		 *
		 * Uses the product category's tax code (if any) or the default setting as a fallback.
		 *
		 * @since 1.0.0
		 * @param string $tax_code The tax code.
		 * @param WC_Product $product The product object.
		 */
		return apply_filters( 'wc_avatax_get_product_tax_code', $tax_code, $product );
	}


	/**
	 * Prepare a set of fees for the AvaTax API.
	 *
	 * @since 1.5.0
	 *
	 * @param array $existing_lines existing transaction lines
	 * @param array $fees fee objects
	 * @return array $lines line items
	 */
	protected function prepare_fee_lines( $existing_lines, $fees ) {

		$fee_lines = array();

		foreach ( $fees as $item_id => $fee ) {

			$fee_object = new stdClass();

			// account for the various fee object states across WC versions
			if ( $fee instanceof WC_Order_Item_Fee ) {

				$fee_object->id      = $fee->get_id();
				$fee_object->name    = $fee->get_name();
				$fee_object->taxable = 'taxable' === $fee->get_tax_status();
				$fee_object->amount  = $fee->get_total();
				$fee_object->source  = $fee->get_meta( '_wc_avatax_source' );

				$fee = $fee_object;

			} elseif ( is_array( $fee ) ) {

				$fee_object->id      = $item_id;
				$fee_object->name    = $fee['name'];
				$fee_object->taxable = '0' !== $fee['tax_class'];
				$fee_object->amount  = $fee['line_total'];
				$fee_object->source  = isset( $fee['wc_avatax_source'] ) ? $fee['wc_avatax_source'] : '';

				$fee = $fee_object;
			}

			// don't resend fees set by Avalara
			if ( ! empty( $fee->source ) && 'avatax' === $fee->source ) {
				continue;
			}

			if ( isset( $fee->taxable ) && ! $fee->taxable ) {
				continue;
			}

			$fee_line = $this->prepare_line( [
				// if the number is set, send it instead of the id
				'number'      => isset( $fee->number ) ? 'fee_' . $fee->number : 'fee_' . $fee->id,
				'itemCode'    => $fee->id,
				'taxCode'     => wc_avatax()->get_tax_handler()->get_default_product_tax_code(),
				'description' => $fee->name,
				'quantity'    => 1,
				'amount'      => $fee->amount,
			] );

			/**
			 * Filter a fee's line data.
			 *
			 * @since 1.5.0
			 *
			 * @param array $line line data
			 * @param object $fee fee object
			 */
			$fee_lines[] = apply_filters( 'wc_avatax_api_fee_line_data', $fee_line, $fee );
		}

		return array_merge( $existing_lines, $fee_lines );
	}


	/**
	 * Prepare an order line item for the AvaTax API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item {
	 *     The line item details.
	 *
	 *     @type string $number      unique line identifier
	 *     @type string $itemCode    unique item identifier like the product SKU or ID
	 *     @type string $taxCode     line tax code
	 *     @type string $HTSCode     landed cost Harmonized Tarrif Code
	 *     @type string $description item description or product title
	 *     @type int    $quantity    line quantity
	 *     @type float  $amount      extended total price
	 * }
	 * @return array $line The formatted line.
	 */
	protected function prepare_line( $item ) {

		$defaults = array(
			'number'      => '',
			'itemCode'    => '',
			'taxCode'     => '',
			'HTSCode'     => '',
			'weight'      => '',
			'weight_unit' => get_option( 'woocommerce_weight_unit' ),
			'description' => '',
			'quantity'    => 1,
			'amount'      => 0,
			'taxIncluded' => false,
			'origin'      => array(),
			'destination' => array(),
		);

		$item = wp_parse_args( $item, $defaults );

		// cast and truncate the values
		$line = array(
			'number'      => $item['number'],
			'itemCode'    => Framework\SV_WC_Helper::str_truncate( Framework\SV_WC_Helper::str_to_sane_utf8( $item['itemCode'] ), 50, '' ),
			'taxCode'     => Framework\SV_WC_Helper::str_truncate( Framework\SV_WC_Helper::str_to_sane_utf8( $item['taxCode'] ), 25, '' ),
			'description' => Framework\SV_WC_Helper::str_truncate( Framework\SV_WC_Helper::str_to_sane_utf8( $item['description'] ), 255, '' ),
			'quantity'    => (float) $item['quantity'],
			'amount'      => (float) $item['amount'],
			'taxIncluded' => (bool) $item['taxIncluded'],
		);

		// prepare the addresses
		if ( ! empty( $item['origin'] ) || ! empty( $item['destination'] ) ) {
			$line['addresses'] = $this->prepare_address_data( $item['origin'], $item['destination'] );
		}

		// add landed cost
		if ( $item['HTSCode'] && wc_avatax()->get_landed_cost_handler()->is_available() ) {

			$line['hsCode'] = $item['HTSCode'];

			if ( $item['weight'] ) {
				$line['parameters']['AvaTax.LandedCost.UnitName']   = $item['weight_unit']; // TODO: format to the standard defined by Avalara once available {CW 2017-08-21}
				$line['parameters']['AvaTax.LandedCost.UnitAmount'] = $item['weight'];
			}
		}

		// remove any empty values
		foreach ( $line as $key => $value ) {

			if ( empty( $value ) ) {
				unset( $line[ $key ] );
			}
		}

		return $line;
	}


	/** Shipping Helper Methods ***********************************************/


	/**
	 * Determines whether the session has a destination, or is local-pickup.
	 *
	 * @since 1.5.0
	 *
	 * @param array $shipping_methods a list of shipping method IDs
	 * @return bool
	 */
	protected function shipping_has_destination( $shipping_methods ) {

		$has_destination = false;

		foreach ( $shipping_methods as $method ) {

			// if a non-local pickup method is found
			if ( ! Framework\SV_WC_Helper::str_starts_with( $method, 'local_pickup' ) ) {
				$has_destination = true;
				break;
			}
		}

		return $has_destination;
	}


	/** Address Helper Methods ************************************************/


	/**
	 * Prepares a origin and destination address for the AvaTax API.
	 *
	 * This method converts a WooCommerce address to the correct key => value
	 * format for the API, as well as changes the data to the 'singleLocation'
	 * key if the addresses are the same, i.e. for local pickup.
	 *
	 * @since 1.5.0
	 *
	 * @param array $origin origin address
	 * @param array $destination destination address
	 * @return array API-formatted addresses
	 */
	protected function prepare_address_data( $origin, $destination ) {

		$addresses = array();

		/**
		 * Filter the origin address for calculation.
		 *
		 * @since 1.1.0
		 * @param array $address the address
		 */
		$origin = (array) apply_filters( 'wc_avatax_tax_origin_address', $origin );

		/**
		 * Filter the origin address for calculation.
		 *
		 * @since 1.1.0
		 * @param array $address destination address
		 */
		$destination = (array) apply_filters( 'wc_avatax_tax_destination_address', $destination );

		// convert the addresses to the AvaTax API format
		$origin      = $this->prepare_address( $origin );
		$destination = $this->prepare_address( $destination );

		if ( $origin == $destination || empty( $origin ) || empty( $destination ) ) {

			$addresses['singleLocation'] = $origin;

		} else {

			$addresses['shipFrom'] = $origin;
			$addresses['shipTo']   = $destination;
		}

		return $addresses;
	}


	/**
	 * Removes line-level addresses when they match the addresses set for the
	 * entire transaction.
	 *
	 * @since 1.5.0
	 *
	 * @param array $data transaction data
	 * @return array $data transaction data with duplicate addresses removed
	 */
	protected function remove_duplicate_addresses( $data ) {

		// no addresses? no problem
		if ( empty( $data['addresses'] ) ) {
			return $data;
		}

		$transaction_addresses = $data['addresses'];

		// check for single location or destination & origin
		if ( isset( $transaction_addresses['singleLocation'] ) ) {
			$types = array( 'singleLocation' );
		} else {
			$types = array( 'shipFrom', 'shipTo' );
		}

		// parse each line and remove its addresses if duplicates are found
		foreach ( $data['lines'] as $line_key => $line ) {

			// no addresses? no problem
			if ( empty( $line['addresses'] ) ) {
				continue;
			}

			foreach ( $types as $type ) {

				if ( ! empty( $transaction_addresses[ $type ] ) && ! empty( $line['addresses'][ $type ] ) && $line['addresses'][ $type ] == $transaction_addresses[ $type ] ) {
					unset( $data['lines'][ $line_key ]['addresses'][ $type ] );
				}
			}

			// if all of the line's addresses were removed, remove the key
			if ( empty( $data['lines'][ $line_key ]['addresses'] ) ) {
				unset( $data['lines'][ $line_key ]['addresses'] );
			}
		}

		return $data;
	}


	/**
	 * Gets the company code for this request.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	protected function get_company_code() {

		return $this->company_code;
	}


	/**
	 * Determine if new tax documents should be committed on calculation.
	 *
	 * @since 1.0.0
	 * @return bool $commit Whether new tax documents should be committed on calculation.
	 */
	protected function commit_calculations() {

		/**
		 * Filter whether new tax documents should be committed on calculation.
		 *
		 * @since 1.0.0
		 * @param bool $commit Whether new tax documents should be committed on calculation.
		 */
		return (bool) apply_filters( 'wc_avatax_commit_calculations', 'yes' === get_option( 'wc_avatax_commit' ) );
	}


}
