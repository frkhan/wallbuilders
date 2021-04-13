<?php
/*
Plugin Name: Samadhan Woo - Nop Connection
Plugin URI: www.samadhan.com.bd
Description: Woo to Nop Connectivity Custom Plugin
Author: Samadhan Solution Pty Ltd
Version: 3.0.0
Author URI: https://www.samadhan.com.bd
*/



define( 'SMDN_DONATION_PRODUCT_ID', 3048); //  Dev->86
define( 'SMDN_DONATION_REGULAR_DONOR_PRODUCT_ID', 3144); //  Dev->86


require_once ('helper/SamadhanConnect_API_Client.php');
require_once ('helper/nop-auth.php');

//require_once ('admin/settings.php');
//require_once ('admin/scheduler.php');
//87

//namespace Samadhan
//add_shortcode('test_function',   'SamadhanOrderProcessing::test_add_customer_to_connect');
add_action('woocommerce_thankyou',   'SamadhanOrderProcessing::add_customer_to_connect');
//add_action('admin_enqueue_scripts', ['Samadhan_AC_Meta_Box','meta_box_scripts']);


class SamadhanOrderProcessing {

    public static  $metakey_prefix = 'smdn_pod_';


    public static function test_add_customer_to_connect(){
        SamadhanOrderProcessing::add_customer_to_connect(87);
    }

    public static function add_customer_to_connect($order_id)
    {
        if (!$order_id) { return; }

        $major_donor_id = 0;
        $is_donation_ordered = false;

        $metakey = SamadhanOrderProcessing::$metakey_prefix . 'order_send_to_connect';
        if( get_post_meta($order_id,$metakey, true)) return;
        add_post_meta($order_id,$metakey, true,true);

        $api_url = get_option( 'smdn_ac_api_url', '' );
        $api_key = get_option( 'smdn_ac_api_key', '' );

        $order = \wc_get_order($order_id);
        $customer_user_id = $order->get_user_id();
        $customer_user_email = $order->get_billing_email();
        $customer_first_name= $order->get_billing_first_name();
        $customer_last_name= $order->get_billing_last_name();


        $order_items = $order->get_items();
        foreach ($order_items as $product) {
            if ($product['product_id'] == SMDN_DONATION_PRODUCT_ID) {
                $is_donation_ordered = true;
                break;
            }
            if ($product['product_id'] == SMDN_DONATION_REGULAR_DONOR_PRODUCT_ID) {
                $is_donation_ordered = true;
                break;
            }
        }

        if($is_donation_ordered) {

            //var_dump($customer_user_id);
            //echo "Customer name : " . $customer_first_name;

            $consumer_key = "test";
            $consumer_secret ="test";
            //$store_url = "http://192.168.0.106/connect/";
            $store_url = "http://api-connect.samadhan-demo.com/";

            $ConnectApi = new Connect_API_Client( $consumer_key, $consumer_secret, $store_url, $is_ssl = false );
           // $ConnectApi->set_return_as_object(true);

            $data = array (
                "FirstName" => $customer_first_name,
                "LastName" => $customer_last_name,
                "Email" => $customer_user_email,
                "Company" =>  $order->get_billing_company(),
                "Address1" => $order->get_billing_address_1(),
                "Address2" => $order->get_billing_address_2(),
                "City" => $order->get_billing_city(),
                "State" => $order->get_billing_state(),
                "Zip" => $order->get_billing_postcode(),
                //"emailOption" => $order->?
                "active" => 1,
                //"totalDonationsCount" => 1,
                //"totalDonationAmount" => $order->get_amount(),
                //"currentLevel" => 1,
                //"totalDonationCtLY" => 0,
                //"totalDonationAmtLY" => 0
                "Phone" => $order->get_billing_phone()
            );

            $savedDonor =   $ConnectApi->create_MajorDonor($data);
            //$savedDonor = json_decode($return);

            //var_dump($savedDonor);
            if($savedDonor->HasError == false){
                $major_donor_id = $savedDonor->Result;
            }

            //

            var_dump($major_donor_id);

            /******************* save donation details ********************/
            $order_items = $order->get_items();
            foreach ($order_items as $item_id => $product) {
                $historyData = array (
                    "OriginalClientId"=>$order->get_customer_id(),
                    "MajorDonorID"=>$major_donor_id,
                    "Source"=>"WooCommerce",
                    "OrderNumber"=>$order->get_order_number(),
                    "OriginalCreateDate" => $order->get_date_created()->format(DateTime::ISO8601),
                    "ProductName"=> $product->get_name(),
                    "Quantity" =>$product->get_quantity(),
                    "UnitPrice" =>  $product->get_subtotal(),
                    "TotalAmount" => $product->get_total(),
                    "FirstName" => $customer_first_name,
                    "LastName" => $customer_last_name,
                    "Email" => $customer_user_email,
                    "Company" =>  $order->get_billing_company(),
                    "Address1" => $order->get_billing_address_1(),
                    "Address2" => $order->get_billing_address_2(),
                    "City" => $order->get_billing_city(),
                    "State" => $order->get_billing_state(),
                    "Zip" => $order->get_billing_postcode()
                    );

               // var_dump($historyData);
                $DonorHistory =   $ConnectApi->create_MajorDonor_History($historyData);
            }
            //var_dump($DonorHistory);


            /*
             * {"Id":17,"MdId":13944,"Name":"Sam & Kitty Harward","Company":null,"Address1":"146 W H St","Address2":null,"CityStateZip":"Casper  WY    82601-1252","CreateDate":"2014-10-23T11:31:21.663","CreatedBy":"","UpdateDate":"1900-01-01T00:00:00","UpdatedBy":"","Note":"7/31/14-Kitty talked to Sandy.  Sam decided to stop taking the pain meds, and his headaches are gone.\r\n7/23/14-Shared thanks with Kitty.  Sam is having very bad headaches, even going in to the emergency room. He is seeing his doctor and a neurosurgeon, and they've discovered blood on the brain, but don't know why.  Pray that they can find the reason and remedy so that he can be healed.  Kitty says they really appreciate what WB does to \"stem the judgement that's coming\" SW\r\n12/26/13-417-681-1177 is a wrong number.Called the right number. Shared thanks with Kitty.Pray for her 3 sons, raised to know the Lord, but fallen away.  Pray that the prodigals would come home. J.K., George, and Todd. Pray for their families as well.SW\r\n12/3/2013: Canceled monthly c/c donations PC.\r\n6/2/13-Thanked Sam for support. SW\r\n11/1/12- 417-631-7890 is a wrong number.SW\r\n11/1/12 LM of T&A. SW \t\r\n9/22/11 Sent note. SW \t\r\n9/9/10 Shared thanks. No prayer needs just now. SW\r\n1/14/10-LM of T&A. SW "}
             */

            /************* ADD CUSTOMER NOTE *****************/
            $customerNote = array (
                    "MdId" => $major_donor_id,
                    "CreatedBy" => "Customer note from Woo Store",
                    "Note" => $order->get_customer_note(),
                    "CreateDate" =>  $order->get_date_created()->format(DateTime::ISO8601)
            );

            $DonorNotes =   $ConnectApi->create_MajorDonor_Notes($customerNote);
        //    var_dump($DonorNotes);

                }

            return "Donor Saved! ID : " . $major_donor_id ;
        }



}