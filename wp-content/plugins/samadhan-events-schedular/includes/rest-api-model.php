<?php


namespace samadhan;


class RestApiModel
{
    protected static function authorized(){
        $nonce ='';
        if ( isset( $_REQUEST['_wpnonce'] ) ) {
            $nonce = $_REQUEST['_wpnonce'];
        } elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
            $nonce = $_SERVER['HTTP_X_WP_NONCE'];
        }
        return wp_verify_nonce( $nonce, 'wp_rest' );

    }
}