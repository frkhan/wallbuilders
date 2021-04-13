<?php

namespace Samadhan;
use PODServiceLib;
use SmdnAccountsWoocommerceReport;

use DateTime;
use WC_API_Client;
class SalesTax extends GetOrder {

    public function __construct()
    {
        add_shortcode('pod_sales_tax_reports','Samadhan\SalesTax::smdn_woocommerce_sale_tax_report');
    }


    public static function smdn_woocommerce_sale_tax_report(){


        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }

        $postFrom_date = '';
        $postTo_date= '';

        $linesPerPage = 10;
        $currentPageNumber = 1;

        if(isset($_POST['currentPageNumber']) && !empty($_POST['currentPageNumber'])){
            $currentPageNumber = $_POST['currentPageNumber'];
        }

        //$filterData= array( 'per_page'=>$linesPerPage, 'page'=>(int)$currentPageNumber);


        if(isset($_POST['searchButton']) && !empty($_POST['filterName'])){
            $searchName=$_POST['filterName'];

            //$filterData= array('search'=>$searchName, 'per_page'=>$linesPerPage, 'page'=>(int)$currentPageNumber);
        }

        if( isset($_POST["from_date"]) && isset($_POST["to_date"])) {
            $postFrom_date = $_POST["from_date"];
            $postTo_date = $_POST["to_date"];

            $from_date = new DateTime($postFrom_date);
            $from_date->setTime(00, 00, 00);
            $getFromDate = $from_date->format('Y-m-d\TH:i:s');

            $to_date = new DateTime($postTo_date);
            $to_date->setTime(23, 59, 59);
            $getToDate = $to_date->format('Y-m-d\TH:i:s');


        }

        $filterData = array('after' => $getFromDate, 'before' => $getToDate, 'page' =>$currentPageNumber);



        self::smdn_sales_tax_filter_form($postFrom_date,$postTo_date,$state,$currentPageNumber);



        if(!empty($postFrom_date && $postTo_date)) {


            $wc_api = parent::smdn_order_API_call();

            $total_order_count = $wc_api->get_reports_total();


            $date_min = '2000-01-01';
            $date_max = date('Y-m-d');
            $f = array('date_min' => $date_min, 'date_max' => $date_max);
            $total_sales_reports = $wc_api->get_sales_report($f);
            foreach ($total_sales_reports as $total_sales_report)
            {
                $total_sales = $total_sales_report->total_sales;
            }


            $orders_count_by_filter = SmdnAccountsWoocommerceReport::get_order_count_dummy($postFrom_date,$postTo_date);
            $order_count_by_filter = $orders_count_by_filter->total;
            //var_dump($order_count_by_filter);




            $membership_table = "<div><h2> ALL SALES TAX  REPORTS</h2>
                                
                            </div>
                            <br/> ";
            $membership_table .= '<table class="table">';
            $membership_table .= '<thead style="color: white; background-color: rgb(69, 88, 97);">
                                   <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="7">Total Sales = '.wc_price($total_sales).'</th>
                                      <th scope="row" colspan="7">Total Orders = '.$total_order_count.'</th>
                                                                       
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    <tr>
                                        <th rowspan="2">SL#</th>
                                        <th rowspan="2">Order Id</th>
                                        <th rowspan="2">Paid Date</th>
                                        <th rowspan="2">First Name</th>
                                        <th rowspan="2">Last Name</th>
                                        <th rowspan="2">Company</th>
                                        <th rowspan="2">Ship To City</th>
                                        <th rowspan="2">Ship To State</th>
                                        <th rowspan="2">Ship To Zip</th>
                                        <th rowspan="2">Ship To Country</th>
                                        <th rowspan="2">Order Amount</th>
                                        <th rowspan="2">Donation</th>
                                        <th rowspan="2">Tax</th>
                                        <th rowspan="2">Taxable Sales</th>
                                        <th rowspan="2">Shipping</th>
                                        <th rowspan="2">Total Taxable Shipping</th>
                                      </tr>
                                     
                                  </thead>';
            $membership_table .= '<tbody>';


            $sl_no = 0;
            $grand_total = 0;
            $grand_total_tax = 0;



            //var_dump($total_sales);

            $current_page = 0;
            $count_order_per_page = 0;
            $order_per_page = 0;



                $getOrder = $wc_api->get_orders($filterData);



            $line_number = ((int)$currentPageNumber - 1) * $linesPerPage;
            $current_page = (($currentPageNumber - 1) * $linesPerPage) + 1;
            foreach ($getOrder as $order) {

                //var_dump($order);
                $line_number++;
                $count_order_per_page++;
                //$current_page = (($currentPageNumber-1) * $count_order_per_page) + 1;
                $order_per_page = $current_page - 1 + $count_order_per_page;

                $order_id = $order->id;

                $shipping_lines = $order->shipping_lines;
                //var_dump($shipping_lines);
                foreach ($shipping_lines as $shipping_line) {
                    $shipping_line->total_tax;
                    $shipping_tax = $shipping_line->shipping_tax;
                }


                $firstname = $order->shipping->first_name; //billing_first_name;
                $lastname = $order->shipping->last_name; //billing_last_name;
                $user_email = $order->shipping->email;
                $paid_date = date_i18n(get_option('date_format'), strtotime($order->date_paid));
                $ship_to_city = $order->shipping->city;
                $ship_to_state = $order->shipping->state;
                $ship_to_zip = $order->shipping->postcode;
                $ship_to_country = $order->shipping->country;
                $ship_to_company = $order->shipping->company;
                $order_status = $order->status;
                $order_ship_status = $order->payment_method;
                $order_paid_status = $order->payment_method;
                $order_customer_id = $order->customer_id;
                $order_billing_addresss1 = $order->shipping->address_1;
                $order_billing_addresss2 = $order->shipping->address_2;

                $order_date = date_i18n(get_option('date_format'), strtotime($order->date_paid)); //. " *  " .$order->order_date;

                $tax_total = wc_format_decimal($order->total_tax, 2);
                $order_total = wc_format_decimal($order->total, 2);
                $total_wo_gst = wc_format_decimal($order_total - $tax_total, 2);

                $grand_total += $order_total;
                $grand_total_tax += $tax_total;

                $total_taxable_shipping = $order_total + $shipping_tax;
                $grand_taxable_shipping += $total_taxable_shipping;

                //var_dump($grand_taxable_shipping);


                /*
                       $users = Samadhan\course_helpers::get_user_course_data($user_id);
                       foreach ($users as $user) {
                           $total_cpd = $user['total_ceus'];
                       }*/


                $membership_table .= "<tr>
                                      <td>$line_number</td>
                                      <td>$order_id</td>
                                      <td>$paid_date</td>
                                      <td>$firstname</td>
                                      <td>$lastname</td>
                                      <td>$ship_to_company</td>
                                      <td>$ship_to_city</td>
                                      <td>$ship_to_state</td>
                                      <td>$ship_to_zip</td>
                                      <td>$ship_to_country</td>
                                      <td >" . wc_price($order_total) . "</td>
                                      <td >" . wc_price(0) . "</td>   
                                      <td>" . wc_price($tax_total) . "</td>                                                       
                                      <td>" . wc_price($order_total) . "</td>
                                      <td>" . wc_price($shipping_tax) . "</td>
                                      <td>" . wc_price($order_total) . "</td>
                                </tr>";

            }


            $membership_table .= ' </tbody> ';


            $membership_table .= '<tfoot><tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="10">Page Total</th>
                                      <th>' . wc_price($grand_total) . '</th>  
                                      <th>' . wc_price(0) . '</th>  
                                      <th>' . wc_price($grand_total_tax) . '</th>                                  
                                      <th>' . wc_price($grand_total) . '</th>  
                                      <th>' . wc_price(0) . '</td> 
                                      <th>' . wc_price($grand_taxable_shipping) . '</td>                                  
                                    </tr>
                                     <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="13">Showing ' . $current_page . ' to  ' . $order_per_page . ' of total ' . $order_count_by_filter . ' entries</th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    </tfoot>';

            $membership_table .= '</table>';
        }

        else {
            echo "<div style='text-align: center'><h1>Enter Date</h1></div>";
        }

        return $membership_table;

    }


    public  static  function smdn_sales_tax_filter_form($from_date, $to_date,$state,$currentPageNumber){
        ?>
        <form id="pod-point-entry" method="post" role="form">
            <?php  wp_nonce_field( 'leader_pod_report' ); ?>
            <input type='hidden' id='currentPageNumber' name='currentPageNumber' value="<?php echo $currentPageNumber; ?>">
            <input type="hidden" name="samadhan_woocommerce_report" value="SEARCH">
            <input type="hidden" name="samadhan_report_type" value="ACCOUNTS_WOOCOMMERCE_SALES">


            <table id="searchResults" class="display" cellspacing="0" width="100%" xmlns="http://www.w3.org/1999/html">
                <caption>ENTER SALES TAX DATES</caption>
                <thead>
                <tr>
                    <th  style="width: 1%; text-align: center">Date From</th>
                    <th  style="width: 1%; text-align: center ">Date To</th>
                    <th></th>
                    <th  style="width: 1%; text-align: center">Search</th>
                    <th  style="width: 1%; text-align: center">Download</th>
                </tr>
                </thead>
                </tbody>

                <tr style="background-color: #222222;">
                    <td style="vertical-align: top;; text-align: center">
                        <input id="pod_date_from"
                               type="date"
                               name='from_date'
                               value="<?php echo $from_date; ?>"
                               class="form-control"
                               placeholder="dd/mm/yyyy *"
                               required="required"
                               required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"
                               data-error="date is required."
                               style="height:32px; text-align: center;
    }"
                        >
                    </td>

                    <td style="vertical-align: top;; text-align: center">
                        <input id="pod_date_to"
                               type="date"
                               name='to_date'
                               value="<?php echo $to_date; ?>"
                               class="form-control"
                               placeholder="dd/mm/yyyy *"
                               required="required"
                               required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"
                               data-error="date is required."
                               style="height:32px; text-align: center;
    }"
                        >
                    </td>


                    <td>
                        <input type='hidden' placeholder='Enter Name' name='filterName' value="<?php echo $currentPageNumber; ?>">
                    </td>

                    <td style="vertical-align: top; text-align: center">
                        <input type="submit" id="searchButton" class="btn btn-success btn-send" name="samadhan-pod-group-leader-search" value="Search">
                    </td>
                    <td style="vertical-align: top; text-align: center">

                        <input type="submit" class="btn btn-success btn-send" name="samadhan-woocommerce-report-download" value="Download CSV">
                    </td>
                </tr>

                </tbody>
            </table>
        </form>

        <?php
    }


}

new SalesTax();