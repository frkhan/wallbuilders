<?php

namespace Samadhan;
use Exception;
use Samadhan\UserCRUD;

class DonorOrderList extends MajorDonorReports {

    public function __construct()
    {
        add_shortcode('donor_order_list','Samadhan\DonorOrderList::getMajorDonorReports');
    }



    public static function getMajorDonorReports(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        try {


            $wc_api = parent::getWCApiClient();
            //$data=new Samadhan\SMDNdonorFormView();
            return self::get_donor_order_reports($wc_api);

        } catch ( Exception $e ) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;

        }


    }



    public static function  get_donor_order_reports($wc_api){



        $linesPerPage = 10;
        $currentPageNumber = 1;
        $filterData= array("per_page"=>$linesPerPage, "offset"=>$currentPageNumber);
        if(isset($_POST['currentPageNumber']) && !empty($_POST['currentPageNumber'])){
            $currentPageNumber = $_POST['currentPageNumber'];
        }

        if(isset($_POST['searchButton']) && !empty($_POST['filterName'])){
            $searchName=$_POST['filterName'];
            $filterData= array("search"=>$searchName, "per_page"=>$linesPerPage, "offset"=>$currentPageNumber);
        }

        // $filterData= array('customer'=>$customer_id,'per_page'=>200);


        //var_dump($customer_id);
        // var_dump($allOrders);


        //  $allOrders = $wc_api->get_customer_orders($customer_id);


        $membership_table ="<div><h2>DONOR ORDER LIST</h2>
                                <form method='post' name='form' style='background: #0a4b78; float: right;width: 100%' >
                                    <div style='float: right'>
                                    <input type='hidden' id='currentPageNumber' name='currentPageNumber' value='".$currentPageNumber."'>
                                    <input type='text' name='filterName' value='".$searchName."'>
                                    <input type='submit' id='searchButton' name='searchButton' value='Search'>
                                    </div>
                                </form>
                            </div>
                            <br/> ";
        $membership_table .= '<div id="maintainManageDonorReports"><table class="table">';
        $membership_table.='<thead style="color: white; background-color: rgb(69, 88, 97);">
                                  <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="15"></th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Sl#</th>
                                        <th rowspan="2">Order#</th>
                                        <th rowspan="2">Order Status</th>
                                        <th rowspan="2">Paid Status</th>
                                        <th rowspan="2">Ship Status</th>
                                        <th rowspan="2">Customer#</th>
                                        <th rowspan="2">First Name</th>
                                        <th rowspan="2">Last Name</th>
                                        <th rowspan="2">Company</th>
                                        <th rowspan="2">Address 1</th>
                                        <th rowspan="2">Address 2</th>
                                        <th rowspan="2">City</th>
                                        <th rowspan="2">State</th>
                                        <th rowspan="2">Zip</th>
                                        <th rowspan="2">Order Date</th>
                                        <th rowspan="2">Paid Date</th>
                                        <th rowspan="2">PO</th>
                                        <th rowspan="2">Amount</th>
                                      </tr>
                                     
                                  </thead>';
        $membership_table.='<tbody>';


        $allOrders = $wc_api->get_orders($filterData);


        $line_number =  ((int)$currentPageNumber -1 ) * $linesPerPage;


        foreach($allOrders as $order ){


            $product_ids=$order->line_items;
            $cateID=false;
            foreach ($product_ids as $product_id){
                $product_id=$product_id->product_id;
                $products = $wc_api->get_product($product_id);
                //var_dump($products);
                $productsName= $products->categories[0]->name;
                if($productsName=="Donor"){
                    $cateID=true;
                }

            }


            if($cateID){
                $line_number++;
                $order_id=$order->id;

                $order_status=$order->status;
                $order_paid_status=($order->payment_details->paid ? 'Paid': 'Unpaid');
                $order_ship_status=$order->shipping_methods;
                $order_customer_id=$order->id;
                $firstname=$order->billing->first_name;
                $lastname=$order->billing->last_name;
                $ship_to_company=$order->billing->company;
                $order_billing_addresss1=$order->billing->address_1;
                $order_billing_addresss2=$order->billing->address_2;
                $ship_to_city=$order->billing->city;
                $ship_to_state=$order->billing->state;
                $ship_to_zip=$order->billing->postcode;
                $order_date=date_i18n( get_option( 'date_format' ), strtotime($order->created_at) );
                $paid_date=date_i18n( get_option( 'date_format' ), strtotime($order->completed_at) );
                $total_wo_gst=$order->total_tax;
                $order_total=$order->total;




                $membership_table.="<tr>
                                      <td>$line_number</td>
                                      <td>$order_id</td>
                                      <td>$order_status</td>
                                      <td>$order_paid_status</td>
                                      <td>$order_ship_status</td>
                                      <td>$order_customer_id</td>
                                      <td>$firstname</td>
                                      <td>$lastname</td>
                                      <td>$ship_to_company</td>
                                      <td>$order_billing_addresss1</td>
                                      <td>$order_billing_addresss2</td>
                                      <td>$ship_to_city</td>
                                      <td>$ship_to_state</td>
                                      <td>$ship_to_zip</td>
                                      <td >$order_date</td>
                                      <td >$paid_date</td>   
                                      <td>" . wc_price($total_wo_gst) . "</td>                                                       
                                      <td>" . wc_price($order_total) . "</td>
                                     
                                </tr>";

            }
        }


        $membership_table.=' </tbody> ';

        $membership_table.='<tfoot><tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="15"></th>
                                      <th></th>                                  
                                      <th></td>
                                      <th></td>                                  
                                    </tr>
                                      <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="15"></th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    </tfoot>';


        $membership_table.='</table></div>';


        return $membership_table;


    }


}

new DonorOrderList();
