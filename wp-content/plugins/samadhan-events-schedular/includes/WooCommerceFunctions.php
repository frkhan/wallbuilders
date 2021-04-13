<?php
class WooCommerceFunctions{
private static function authorized(){
    $nonce ='';
    if ( isset( $_REQUEST['_wpnonce'] ) ) {
        $nonce = $_REQUEST['_wpnonce'];
    } elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
        $nonce = $_SERVER['HTTP_X_WP_NONCE'];
    }
    return wp_verify_nonce( $nonce, 'wp_rest' );

  }

    public static function getMajorDonorReports(){


        try {


            $wc_api = WooCommerceFunctions::getWCApiClient();
            $data=new Samadhan\SMDNdonorFormView();
            return $data->get_donor_order_reports($wc_api);

        } catch ( Exception $e ) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;

        }


    }





    private static function getWCApiClient(){

        $is_ssl = false;

        try {

            $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
            $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
            $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');
            return  new WC_API_Client(  $consumer_key, $consumer_secret,$store_url, $is_ssl );

        } catch ( Exception $e ) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;
        }
    }


    public static function get_edit_maintain_major_donor_form(){

        if(isset($_POST["getDonorSearch"]) && !empty($_POST["getDonorSearch"])){
            $customer_id=$_POST["WooId"];
        }else{
            $customer_id=$_GET["customer_id"];
        }


      //  if(self::authorized()){



/*
        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


            $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,true);
*/
            $wc_api = WooCommerceFunctions::getWCApiClient();
            $donorViewForm=new Samadhan\SMDNdonorFormView();
            $major_donor= $donorViewForm->get_edit_maintain_major_donor_forms($wc_api,$customer_id);
            $major_donor .= $donorViewForm->get_donation_history_reports($wc_api,$customer_id);
            $major_donor .= $donorViewForm->get_donation_history_database_reports($wc_api,$customer_id);
            $major_donor .=$donorViewForm->get_donation_woo_notes($wc_api,$customer_id);
            $major_donor .=$donorViewForm->get_donation_data_base_notes($wc_api,$customer_id);
            return $major_donor;









               // return   rest_ensure_response(array('status'=>$data));


       // }

    }



    public static function saveMajorDonorSubmitButtonForm(){
        if(self::authorized()){



            $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
            $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
            $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


            $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,true);


            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);

           // $majorDonor=$eventData->majorDonor;







            $FirstName=$eventData->FirstName;
            $LastName=$eventData->LastName;
            $Company=$eventData->Company;
            $Address1=$eventData->Address1;
            $Address2=$eventData->Address2;
            $City=$eventData->City;
            $State=$eventData->State;
            $Zip=$eventData->Zip;
            $Email=$eventData->Email;
            $Phone=$eventData->Phone;


            $id=$eventData->Id;
            $NopID=$eventData->NopID;
            $emailOption=$eventData->emailOption;
            $activeStatus=$eventData->activeStatus;
            $totalDonationsCount=$eventData->totalDonationsCount;
            $totalDonationAmount=$eventData->totalDonationAmount;
            $currentLevel=$eventData->currentLevel;
            $totalDonationCtLY=$eventData->totalDonationCtLY;
            $totalDonationAmtLY=$eventData->totalDonationAmtLY;
            $arrayMajorDonorData=array(
                'Id'=>$id,
                'NopID'=>$NopID,
                'FirstName'=>$FirstName,
                'LastName'=>$LastName,
                'Company'=>$Company,
                'Address1'=>$Address1,
                'Address2'=>$Address2,
                'City'=>$City,
                'State'=>$State,
                'Zip'=>$Zip,
                'Email'=>$Email,
                'emailOption'=>$emailOption,
                'activeStatus'=>$activeStatus,
                'totalDonationsCount'=>$totalDonationsCount,
                'totalDonationAmount' =>$totalDonationAmount,
                'currentLevel'=>$currentLevel,
                'totalDonationCtLY'=>$totalDonationCtLY,
                'totalDonationAmtLY'=>$totalDonationAmtLY,
                'Phone'=>$Phone
            );

            $customer_details = [
                'customer' => [
                    'email' => $Email,
                    'first_name' => $FirstName,
                    'last_name' => $LastName,
                    'username' => $FirstName.'.'.$LastName,
                    'billing_address' => [
                        'first_name' => $FirstName,
                        'last_name' => $LastName,
                        'company' => $Company,
                        'address_1' => $Address1,
                        'address_2' => $Address2,
                        'city' => $City,
                        'state' => $State,
                        'postcode' => $Zip,
                        'country' => 'US',
                        'email' => $Email,
                        'phone' =>$Phone
                    ]
                ]
            ];


            $results=self::save_MajorDonor_data($arrayMajorDonorData);
           $a_customer = $wc_api->create_customer($customer_details);
           $user_id= $a_customer->customere->id;
            if($user_id){
           update_user_meta($user_id,'_order_id',$NopID);
           update_user_meta($user_id,'_order_count',$totalDonationsCount);
           update_user_meta($user_id,'_money_spent',$totalDonationAmount);
           update_user_meta($user_id,'emailOption',$emailOption);
           update_user_meta($user_id,'activeStatus',$activeStatus);
           update_user_meta($user_id,'currentLevel',$currentLevel);
           update_user_meta($user_id,'totalDonationCtLY',$totalDonationCtLY);
           update_user_meta($user_id,'totalDonationAmtLY',$totalDonationAmtLY);
            }









            $data = "<p style='color:#e00d3f'>Unsuccessfuly Save Error</p>";
            if($a_customer){
                if($a_customer->customer->email){
                $data ="<p style='color:#0AB152'>Successfuly Save Data</p>";

                }
                if($a_customer->errors[0]->message){
                    $data ="Already Existing User Enrolled";

                }

                return   rest_ensure_response(array('status'=>$data));
            }

        }

    }

    public static function saveMajorDoner(){

        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


           // $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,false);


            $postdata = file_get_contents("php://input");
            $mejorDonorData = json_decode($postdata);

        $arrayMajorDonorData=array(
            'Id'=>0,
            'WooId'=>$mejorDonorData->WooId,
            'ConnectId'=>$mejorDonorData->ConnectId,
            'NopID'=>$mejorDonorData->NopID,
            'FirstName'=>$mejorDonorData->FirstName,
            'LastName'=>$mejorDonorData->LastName,
            'Company'=>$mejorDonorData->Company,
            'Address1'=>$mejorDonorData->Address1,
            'Address2'=>$mejorDonorData->Address2,
            'City'=>$mejorDonorData->City,
            'State'=>$mejorDonorData->State,
            'Zip'=>$mejorDonorData->Zip,
            'Email'=>$mejorDonorData->Email,
            'emailOption'=>$mejorDonorData->emailOption,
            'activeStatus'=>$mejorDonorData->activeStatus,
            'totalDonationsCount'=>$mejorDonorData->totalDonationsCount,
            'totalDonationAmount' =>$mejorDonorData->totalDonationAmount,
            'currentLevel'=>$mejorDonorData->currentLevel,
            'totalDonationCtLY'=>$mejorDonorData->totalDonationCtLY,
            'totalDonationAmtLY'=>$mejorDonorData->totalDonationAmtLY,
            'Phone'=>$mejorDonorData->Phone
        );

        $results=self::save_MajorDonor_data($arrayMajorDonorData);

        return   rest_ensure_response(array('status'=>$results));

    }
    public static function saveMajorDonationHistory(){


        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


           // $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,false);


            $postdata = file_get_contents("php://input");
            $mejorDonorData = json_decode($postdata);

            $arrayMajorDonorData=array(
                'Id'=>0,
                'WooId' =>$mejorDonorData->WooId,
                'ConnectId'=>$mejorDonorData->ConnectId,
                'MajorDonorID'=>$mejorDonorData->MajorDonorID,
                'Source'=>$mejorDonorData->Source,
                'OrderNumber'=>$mejorDonorData->OrderNumber,
                'OriginalCreateDate'=>$mejorDonorData->OriginalCreateDate,
                'ProductName'=>$mejorDonorData->ProductName,
                'Quantity'=>$mejorDonorData->Quantity,
                'UnitPrice'=>$mejorDonorData->UnitPrice,
                'TotalAmount'=>$mejorDonorData->TotalAmount,
                'FirstName'=>$mejorDonorData->FirstName,
                'LastName'=>$mejorDonorData->LastName,
                'Company'=>$mejorDonorData->Company,
                'Address1'=>$mejorDonorData->Address1,
                'Address2'=>$mejorDonorData->Address2,
                'City' =>$mejorDonorData->City,
                'State'=>$mejorDonorData->State,
                'Zip'=>$mejorDonorData->Zip,
                'Email'=>$mejorDonorData->Email,
                'Phone'=>$mejorDonorData->Phone,
                'OriginalClientId'=>$mejorDonorData->OriginalClientId
            );

            $results=self::save_MajorDonor_history_data($arrayMajorDonorData);
             return   rest_ensure_response(array('status'=>$results));
            //return   rest_ensure_response(array('status'=>$arrayMajorDonorData));

    }

    //************** Set Function**************//

    public static function set_update_MajorDonor_data($customer=array(),$majorDonor=array()){



        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');

//curl https://store.wallbuilders.com/wp-json/wc/v3/customers?per_page=1  -u ck_55c9b85d07a0029110d30d486bab048e0b5f2c63:cs_a596c053f5c4cfa3249f4a55a821b694ce64b459
//curl https://store.wallbuilders.com/wp-json/wc/v3/customers?page=1&per_page=1  -u ck_55c9b85d07a0029110d30d486bab048e0b5f2c63:cs_a596c053f5c4cfa3249f4a55a821b694ce64b459

//        $consumer_key = 'ck_091e12591be04641416d409088fec802cf8ba5fb';// 'ck_55c9b85d07a0029110d30d486bab048e0b5f2c63';
//        $consumer_secret = 'cs_da4202e30f437a61cae008d186fd6a6118d4764d';// 'cs_a596c053f5c4cfa3249f4a55a821b694ce64b459';
//        $store_url = 'http://localhost/cannxi/'; // https://store.wallbuilders.com/';

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
    public static function save_MajorDonor_data($data=array()){

        global $wpdb;
        //$wpdb->show_errors = true;
        $table_name = $wpdb->base_prefix.'MajorDonor';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }

    public static function update_MajorDonor_data($data=array(),$where=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonor';
        $wpdb->update( $table_name, $data,$where );
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
    public static function get_MajorDonor_by_user_id($user_id){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonor';
        $query = "SELECT * FROM $table_name where WooId=$user_id";
        $results = $wpdb->get_results($query);
        return $results;
    }
    public static function save_MajorDonor_history_data($data){


        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonorHistory';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }

    //**********All Customer User Delete**********//

    public static function all_customer_user_delete(){

        global $wpdb;
        $table_name = $wpdb->prefix.'wc_customer_lookup';
        $table_name_users = $wpdb->base_prefix.'users';
        $table_name_usersmeta = $wpdb->base_prefix.'usermeta';
        $results = $wpdb->get_results( "SELECT * FROM $table_name " );

        foreach ($results as $result){

            $user_meta = get_userdata($result->user_id );
            $user_roles = $user_meta->roles;

            if ( !in_array( 'administrator', $user_roles, true ) && in_array( 'customer', $user_roles, true )) {

                $wpdb->query( "delete FROM $table_name_users where id=".$result->user_id );
                $wpdb->query( "delete  FROM $table_name_usersmeta where user_id=".$result->user_id );
            }
           // $wpdb->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_wc_report_customers_%')" );
            $wpdb->query( "delete FROM $table_name where customer_id=".$result->customer_id );

      }
    }






}

