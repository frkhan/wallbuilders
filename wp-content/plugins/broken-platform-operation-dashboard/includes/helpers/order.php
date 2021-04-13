<?php

add_shortcode('test','smdn_get_order_total');

function smdn_get_order_total(){
    $consumer_key = get_option('SAMADHAN_STORE_CONSUMER_KEY');
    $consumer_secret = get_option('SAMADHAN_STORE_CONSUMER_SECRET');
    $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');

    $wc_api = new WC_API_Client( $consumer_key,$consumer_secret,$store_url ,true);

    $getOrder=$wc_api->get_reports_total();
    var_dump($getOrder);
    //$getOrd=$wc_api->get_products();
    //var_dump($getOrd);

}