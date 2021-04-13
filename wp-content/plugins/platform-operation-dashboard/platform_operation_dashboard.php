<?php
/*
	Plugin Name: Platform Operation Dashboard
	Plugin URI:  http://samadhan.com.bd
	Description: Simple Platform Operation Dashboard Woocommerce Reports.
	Version:     3.0.3
	Author:      samadhan
	Author URI:  http://samadhan.com.bd
*/


use Samadhan\PaginationBuilder;

require_once ('includes/helpers/queryBuilder.php');
require_once ('includes/helpers/paginationBuilder.php');
include_once ('includes/helpers/order.php');

//finance report
include_once ('includes/donation-finance-report.php');
include_once ('includes/sales-tax-report.php');
include_once ('includes/all-orders-report.php');
include_once ('includes/all-paid-orders-report.php');
include_once ('includes/all-pending-orders-report.php');
include_once ('includes/coupon-code-report.php');
include_once ('includes/rush-order-report.php');
include_once ('includes/response-code-report.php');
include_once ('includes/order-by-product-report.php');
include_once ('includes/order-by-shipping-method-report.php');

//donor report
include_once ('includes/donor/authorize-exception-report.php');
include_once ('includes/donor/authorize-transaction-without-donor.php');
include_once ('includes/donor/major-donor-report.php');


/*
$columnsToSearchArray = array("FirstName", "LastName");
$OrderBy = array(
        "TotalAmount" => "DESC",
        "FirstName" => "ASC"
);
$test = new Samadhan\QueryBuilder("MajorDonorHistory", 20,1,$OrderBy);
$searchData = "Vernon";
$test->BuildSearchQuery($searchData, $columnsToSearchArray);
$totalRecords = $test->GetTotal();
echo("Total Records = " . $totalRecords . "<br>");
$result = $test->ExecuteQuery();
foreach ($result as $item) {
    echo($item->FirstName . "<br>");
}
 */


class SmdnAccountsWoocommerceReport{

    public $consumer_key = '';
    public $consumer_secret = '';
    public $store_url = '';


    public  function __construct(){

         $this->get_js_and_css_files();

    }


    protected function get_js_and_css_files(){
        $ver='1.1.0' . rand(1, 100000);
        $stylePath = plugins_url('assets/style.css', __FILE__);
        $scriptPath = plugins_url('assets/scripts.js', __FILE__);
        wp_register_style('smdn_pod_style', $stylePath ,[],$ver);
        wp_enqueue_style('smdn_pod_style');

        wp_register_script('smdn_pod_script', $scriptPath ,['jquery'],$ver);
        wp_enqueue_script('smdn_pod_script');
    }




    public static function get_order_count_dummy( $from_date,$to_date) {


        $api_url = "https://store-staging.wallbuilders.com/wp-json/samadhan_pod/v1/get_order_count";
        $paramString ="?from_date=" .$from_date. "&to_date=" . $to_date ;

        $ch = curl_init();

        $api_url_with_param = $api_url .  $paramString ;
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






}


new SmdnAccountsWoocommerceReport();
