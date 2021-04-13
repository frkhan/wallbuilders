<?php

namespace Samadhan;


use Samadhan\WC_API_Client;

class GetOrder
{

    public static function is_unauthorized()
    {

        $user = wp_get_current_user();
        $allowed_roles = array( 'staff', 'administrator', 'shop_manager' );
        if ( array_intersect( $allowed_roles, $user->roles ) ) {
            return false;
        }
        else {
            return  true;
        }

    }

    public static function unauthorized_message(){

        return "<div style='text-align: center'><h2>Please login as a Staff Member!</h2></div>";
    }






    protected static function smdn_order_API_call()
    {

        $consumer_key = get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret = get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');

        $wc_api = new WC_API_Client($consumer_key, $consumer_secret, $store_url, true);

        return $wc_api;


    }



    //order functions
    public static function woocommerce_get_all_orders($from_date,$to_date){

        global $wpdb;

        $blogID=$wpdb->get_blog_prefix() ;
        $table=$wpdb->get_blog_prefix().'posts';
        //$table='wp_2_posts';
        $blogid=$wpdb->blogid;
        $base_prefix=$wpdb->base_prefix;
        //var_dump($blogID);
        // var_dump($wpdb);
        //var_dump($wpdb->get_blog_prefix());
        //var_dump($wpdb->get_blog_prefix());
        $results=$wpdb->get_results( $wpdb->prepare("SELECT * FROM  $table where post_type = 'shop_order' and post_status not in ('wc-cancelled','wc-on-hold', 'wc-refunded') and post_date between '%s' and '%s' order by post_date ASC",$from_date,$to_date));
        return $results;

    }

    public static function get_order_item($order_id){
        global $wpdb;
        $table=$wpdb->get_blog_prefix().'woocommerce_order_items';
        //$table='wp_2_woocommerce_order_items';
        $results= $wpdb->get_results( $wpdb->prepare("SELECT * FROM $table where order_item_type='line_item' and order_id=%d", $order_id));
        return $results;
    }

    public static function woocommerce_get_all_donation_orders($from_date,$to_date){

        global $wpdb;

        $wpdb->get_blog_prefix() ;
        $table=$wpdb->get_blog_prefix().'posts';
        $woo_table=$wpdb->get_blog_prefix().'woocommerce_order_items';
        //$table='wp_2_posts';
        // var_dump($wpdb);
        //  SELECT p.Id as order_id FROM multisite.wp_2_posts as p  inner join multisite.wp_2_woocommerce_order_items as w on p.ID=w.order_id where post_type = 'shop_order' and post_status not in ('wc-cancelled','wc-on-hold', 'wc-refunded')  and order_item_type='line_item' and order_item_name='Donation'
        $results=$wpdb->get_results( $wpdb->prepare("SELECT * FROM  $table as p inner join  $woo_table as w on p.ID=w.order_id where post_type = 'shop_order' and p.post_status not in ('wc-cancelled','wc-on-hold', 'wc-refunded')  and w.order_item_type='line_item' and w.order_item_name='Donation' and p.post_date between '%s' and '%s' order by p.post_date ASC",$from_date,$to_date));
        return $results;
    }




}

new GetOrder();
