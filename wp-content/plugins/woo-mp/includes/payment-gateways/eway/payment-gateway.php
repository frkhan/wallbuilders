<?php

namespace Woo_MP\Payment_Gateways\Eway;

defined( 'ABSPATH' ) || die;

/**
 * eWAY payment gateway.
 */
class Payment_Gateway extends \Woo_MP\Payment_Gateway\Payment_Gateway {

    const ID = 'eway';

    public function get_title() {
        return 'eWAY';
    }

    public function get_payment_method_title() {
        return get_option( 'woo_mp_eway_title', 'Credit Card (eWAY)' );
    }

    public function get_settings_section() {
        return new Settings_Section();
    }

    public function get_payment_meta_box_helper() {
        return new Payment_Meta_Box_Helper();
    }

    public function get_payment_processor() {
        return new Payment_Processor();
    }

}
