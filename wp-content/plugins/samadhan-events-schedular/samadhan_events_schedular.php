<?php
/*
	Plugin Name: Samadhan Events Schedular
	Plugin URI:  http://samadhan.com.bd
	Description: Simple and Easy Events Schedule .
	Version:     25.0.2
	Author:      samadhan
	Author URI:  http://samadhan.com.bd
*/

/** STAGING SERVER https://store.wallbuilders.com/ **/


//update_option('SAMADHAN_STORE_CONSUMER_KEY', 'ck_55c9b85d07a0029110d30d486bab048e0b5f2c63');
//update_option('SAMADHAN_STORE_CONSUMER_SECRET', 'cs_a596c053f5c4cfa3249f4a55a821b694ce64b459');
//update_option('SAMADHAN_STORE_API_ENDPOINT', 'https://store.wallbuilders.com/');


/** Mukul's local machine */
/*
update_option('SAMADHAN_STORE_CONSUMER_KEY', 'ck_edd831e8ca9a0a732d391e0fe2a8783d87b61ab5');
update_option('SAMADHAN_STORE_CONSUMER_SECRET', 'cs_bbcadf1862f1e2a78337d818612b4016fe3a15a1');
update_option('SAMADHAN_STORE_API_ENDPOINT', 'http://localhost/cannxi/');
*/

/** fazlur's local machine **/
/*
update_option('SAMADHAN_STORE_CONSUMER_KEY', 'ck_195b97576ea98de1c66389ff6af61872961507ca');
update_option('SAMADHAN_STORE_CONSUMER_SECRET', 'cs_a592016145f0a68e5128eeb6c7cea0a1641134c4');
update_option('SAMADHAN_STORE_API_ENDPOINT', 'http://kalapahar.com/wall-builders/');
*/
/*********************************************************/

update_option('SAMADHAN_STORE_API_SSL', false);
//method
include_once('includes/donor/model/majorDonorModel.php');
include_once('includes/common/model/userCrud.php');
include_once('includes/schedule/model/schedulerModel.php');


//require_once ('includes/vendors/woocommerce/woocommerce-api.php');
require_once ('includes/vendors/class-wc-api-client.php');
include_once('includes/functions.php');
include_once('includes/WooCommerceFunctions.php');

include_once('admin/SHcCreateTable.php');
include_once('admin/setting.php');
include_once('views/SMDNEventSchedular.php');
include_once('views/scheduler_forms.php');
include_once('views/donor_forms.php');
include_once('includes/SMDN_Rest_API_Connect.php');


//contact reports
include_once('includes/contact/view/pastor-add.php');
include_once('includes/contact/view/pastor-list.php');
include_once('includes/contact/view/intern-add.php');
include_once('includes/contact/view/intern-list.php');
include_once('includes/contact/view/teacher-add.php');
include_once('includes/contact/view/teacher-list.php');
include_once('includes/contact/view/newsletter-add.php');
include_once('includes/contact/view/newsletter-list.php');



//event forms
include_once ('includes/events/view/events-form.php');
include_once ('includes/events/view/event-information-form.php');
include_once ('includes/events/view/contact-information-form.php');
include_once ('includes/events/view/leadership-information-form.php');
include_once ('includes/events/view/manage-request-reports.php');


//scheduling forms
include_once ('includes/schedule/view/add-media-request-form.php');
include_once ('includes/schedule/view/add-scheduling-request-form.php');
include_once ('includes/schedule/view/add-speaker-form.php');
include_once ('includes/schedule/view/create-itinerary-form.php');
include_once ('includes/schedule/view/itinerary-report.php');
include_once ('includes/schedule/view/manage-events-reports.php');
include_once ('includes/schedule/view/manage-media-reports.php');
include_once ('includes/schedule/view/manage-speaker-list.php');
include_once ('includes/schedule/view/schedule-request-details-report.php');
include_once ('includes/schedule/view/schedule-request-information.php');
include_once ('includes/schedule/view/schedule-speakers-report.php');


//donor forms
include_once ('includes/donor/view/major-donor-reports.php');
include_once ('includes/donor/view/maintain-major-donor-form.php');
include_once ('includes/donor/view/edit-maintain-major-donor-form.php');
include_once ('includes/donor/view/donor-order-list.php');
include_once ('includes/donor/view/customer-list.php');
include_once ('includes/donor/view/update-donor-note.php');



add_action('init','smdn_initial_loaded_files');

register_activation_hook( __FILE__, 'ShcCreateTable::on_create_table');
//register_deactivation_hook(__FILE__, 'ShcCreateTable::on_remove_table');
register_uninstall_hook(__FILE__, 'ShcCreateTable::on_remove_table');



add_shortcode('customer_delete','customer_all_delete_button');

function customer_all_delete_button(){
    if(isset($_POST['DeleteUser'])){
        WooCommerceFunctions::all_customer_user_delete();
    }

    return '<form action="" method="post">
                <button type="submit" name="DeleteUser"  value="Delete">Delete All Customers</button><br/>
            </form>';
}

function smdn_initial_loaded_files(){
    smdn_get_js_and_css_files();
}
function smdn_get_js_and_css_files(){
    $ver='1.1.0';

    //wp_enqueue_style('googleapis','https://fonts.googleapis.com/css');
    //wp_enqueue_style('vueMaterialCss','https://unpkg.com/vue-material/dist/vue-material.min.css','googleapis');
   // wp_enqueue_style('vuecss1','https://unpkg.com/vue-material/dist/theme/default.css','vueMaterialCss');
    wp_enqueue_style('vuecss1','https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css');
    $pathCss = plugins_url('apps/css/smdn_events_schedule.css', __FILE__);
    wp_register_style('smdn_envets_schedule_css', $pathCss ,'vuecss1',$ver);
    wp_enqueue_style('smdn_envets_schedule_css');

    //wp_enqueue_script('vuejs','https://unpkg.com/vue',array('jquery'));
   // wp_enqueue_script('vuejs','https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.0/vue.js',array('jquery'),'',true);
   // wp_enqueue_script('vuejs','https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.4/vue.min.js',array('jquery'),'4.4.0',true);
    $vujs_path = plugins_url('vendors/vuejs.min.js', __FILE__);
    $my_path = plugins_url('apps/js/smdn_events_schedule.js', __FILE__);
    wp_register_script('vuejs', $vujs_path,array('jquery'),$ver,true);
    wp_register_script('smdn_all_js', $my_path,array('vuejs'),$ver,true);


    wp_localize_script('smdn_all_js',"settingObject",array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'homeUrl' => home_url('/'),
    ));
    wp_enqueue_script( 'smdn_all_js' );
}



//************************** samadhan Rest API Call **************************///

add_shortcode('samadhan_pod_api_get_post_meta_test', 'samadhan_pod_api_get_post_meta_test');
function samadhan_pod_api_get_post_meta_test()
{
    $test = samadhan_pod_api_get_post_meta(3458, "response_code", true);
}



function samadhan_pod_api_get_post_meta( $post_id,$key, $single=false ) {


    $api_url = "https://store-staging.wallbuilders.com/wp-json/samadhan_pod/v1/get_post_meta";
    $paramString ="?post_id=" .$post_id. "&key=" . $key ;
    if ($single == true )$paramString .= "&single=true";

    $ch = curl_init();

    $api_url_with_param = $api_url . $paramString ;
// Set up the enpoint URL
    curl_setopt( $ch, CURLOPT_URL, $api_url_with_param );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 300 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 300 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


    $return = curl_exec( $ch );
    $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

    if($code == 200) {
        $return = json_decode( $return );
    }
    else{
        $return = '{"errors":[{"code":"' . $code . '","message":"cURL HTTP error ' . $code . '"}]}';
        $return = json_decode( $return );
    }


    curl_close($ch);
    return $return;
}









