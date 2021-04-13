<?php

namespace Samadhan;

use Exception;
//use Samadhan\MajorDonorReportView;
use WC_API_Client;
use WC_Countries;


class MajorDonorReports {



    protected static function getWCApiClient(){

        $options = array(
            'debug'           => true,
            'return_as_array' => false,
            'validate_url'    => false,
            'timeout'         => 30,
            'ssl_verify'      => false,
        );

        try {

            $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
            $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
            $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');
            return  new WC_API_Client($consumer_key, $consumer_secret,$store_url, $options );

        } catch ( Exception $e ) {

            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;

            if ( $e instanceof Exception ) {
                print_r( $e->get_request());
                print_r( $e->get_response());
            }
        }
    }


    public static function get_MajorDonor_by_user_id($user_id){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonor';
        $query = "SELECT * FROM $table_name where WooId=$user_id";
        $results = $wpdb->get_results($query);
        return $results;
    }

    public static function set_update_MajorDonor_data($customer=array(),$majorDonor=array()){



        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');

        $customer_id=$majorDonor["WooId"];
        $where=array("WooId"=>$customer_id);
        $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,true);
        $updateData=$wc_api->update_customer($customer_id,$customer);
        $results=self::update_MajorDonor_data($majorDonor,$where);
        if($results>0){
            $results=self::save_MajorDonor_data($majorDonor);
        }
        return $updateData;
    }

    public static  function samadhan_get_country_states()
    {

        $WC_Countries = new WC_Countries();
        $states= $WC_Countries->get_states( 'US' );
        // var_dump($states);
        return   rest_ensure_response(array('states'=>$states));

    }

    public static function update_MajorDonor_data($data=array(),$where=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonor';
        $wpdb->update( $table_name, $data,$where );
        return $wpdb->insert_id;

    }

    public static function save_MajorDonor_data($data=array()){

        global $wpdb;
        //$wpdb->show_errors = true;
        $table_name = $wpdb->base_prefix.'MajorDonor';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }

    public static function get_MajorDonor_history_data_by_user_id($user_id){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonorHistory';
        $query = "SELECT * FROM $table_name WHERE WooId=$user_id";
        //var_dump($query);
        $results = $wpdb->get_results($query);
        //var_dump($results);
        return $results;

    }

    public static function get_MajorDonor_note_by_user_id($user_id,$nop_id=''){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonorNotes';
        if(!empty($user_id) && !empty($nop_id)){
            $query = "SELECT * FROM $table_name WHERE WooId=$user_id and NopId=$nop_id";
            $results = $wpdb->get_results($query);
        }else{
            $query = "SELECT * FROM $table_name WHERE WooId=$user_id";
            $results = $wpdb->get_results($query);
        }

        return $results;

    }


    public static function update_MajorDonor_note_data($data=array(),$where=array()){

        global $wpdb;
        $wooId= $where['WooId'];
        $NopId= $where['NopId'];
        $table_name = $wpdb->base_prefix.'MajorDonorNotes';
        $udpateData=$wpdb->update( $table_name, $data,$where );
        if($udpateData){
            $wpdb->query("SELECT * FROM $table_name WHERE WooId = $wooId AND NopId = $NopId");
            return $wpdb->last_result;
        }


    }

    public static function delete_MajorDonor_note_data($where=array()){

        global $wpdb;

        $table_name = $wpdb->base_prefix.'MajorDonorNotes';
        $updateData=$wpdb->delete( $table_name,$where, array( '%d','%d') );

        return $updateData;



    }



}

new MajorDonorReports();
