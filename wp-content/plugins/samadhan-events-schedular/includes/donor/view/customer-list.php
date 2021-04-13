<?php

namespace Samadhan;
use Exception;
use Samadhan\UserCRUD;

class CustomerList extends MajorDonorReports {

    public function __construct()
    {
        add_shortcode('customer_list','Samadhan\CustomerList::getCustomerList');
    }



    public static function getCustomerList(){

        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        //  if(self::authorized()){


        try {
            $wc_api = parent::getWCApiClient();
            //$donorViews=new Samadhan\SMDNdonorFormView();
            return self::get_customer_list_view($wc_api);

        } catch ( Exception $e ) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;

        }

        // return   rest_ensure_response(array('status'=>$data));


        // }

    }



    public static function  get_customer_list_view($wc_api){


        $linesPerPage = 10;
        $currentPageNumber = 1;
        $filterData= array( 'role'=>'all','per_page'=>$linesPerPage, 'page'=>(int)$currentPageNumber);
        if(isset($_POST['currentPageNumber']) && !empty($_POST['currentPageNumber'])){
            $currentPageNumber = $_POST['currentPageNumber'];
        }

        if(isset($_POST['searchButton']) && !empty($_POST['filterName'])){
            $searchName=$_POST['filterName'];
            //$filterData= array('search'=>$searchName,'role'=>'all', 'per_page'=>$linesPerPage, 'page'=>(int)$currentPageNumber);
        }

        $filterData= array('search'=>$searchName,'role'=>'all', 'per_page'=>$linesPerPage, 'page'=>(int)$currentPageNumber);



        $Customers = $wc_api->get_customers($filterData);

        $membership_table="<div><h2>All Donors</h2>
                                <form method='post' name='form' style='background: #0a4b78; float: right;width: 100%' >
                                    <div style='float: right'>
                                    <input type='hidden' id='currentPageNumber' name='currentPageNumber' value='".$currentPageNumber."'>
                                    <input type='text' placeholder='Enter email' name='filterName' value='".$searchName."'>
                                    <input type='submit' id='searchButton' name='searchButton' value='Search'>
                                    </div>
                                </form>
                            </div>
                            <br/> ";

        $membership_table .= '<table class="table">';
        $membership_table.='<thead style="color: white; background-color: rgb(69, 88, 97);">
                                    <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="12"></th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Id</th>
                                        <th rowspan="2">First Name</th>
                                        <th rowspan="2">Last Name</th>
                                        <th rowspan="2">Company</th>
                                        <th rowspan="2">Address 1</th>
                                        <th rowspan="2">Address 2</th>
                                        <th rowspan="2">City</th>
                                        <th rowspan="2">State</th>
                                        <th rowspan="2">Zip</th>
                                        <th rowspan="2">Phone</th>
                                        <th rowspan="2">Mail</th>
                                        <th rowspan="2">Email</th>
                                        <th rowspan="2">Dnt Count</th>
                                        <th rowspan="2">Dnt Total</th>
                                      </tr>
                                     
                                  </thead>';
        $membership_table.='<tbody>';



        $line_number =  ((int)$currentPageNumber -1 ) * $linesPerPage;
        foreach($Customers as $Customer ) {

            $line_number++;

            $customerOders=$wc_api->get_customer_orders(array('customer'=>$Customer->id));

            $orderTotal=0;
            $count=0;
            if($customerOders->errors[0]->code!='200'){
                foreach ($customerOders as $customerOder){
                    $count++;
                    $orderTotal +=$customerOder->total;

                }
            }



            $order_status = $Customer->status;
            $order_paid_status = ($Customer->payment_details->paid ? 'Paid' : 'Unpaid');
            $order_ship_status = $Customer->shipping_methods;
            $order_customer_id = $Customer->id;
            $firstname = $Customer->first_name;
            $lastname = $Customer->last_name;
            $ship_to_company = $Customer->billing->company;
            $order_billing_addresss1 = $Customer->billing->address_1;
            $order_billing_addresss2 = $Customer->billing->address_2;
            $ship_to_city = $Customer->billing->city;
            $ship_to_state = $Customer->billing->state;
            $email = $Customer->email;
            $ship_to_zip = $Customer->billing->postcode;
            $phone = $Customer->billing->phone;
            $orders_count = $Customer->orders_count;
            $total_spent = $Customer->total_spent;
            $order_date = date_i18n(get_option('date_format'), strtotime($Customer->created_at));
            $paid_date = date_i18n(get_option('date_format'), strtotime($Customer->completed_at));
            $total_wo_gst = $Customer->total_tax;
            $order_total = $Customer->total;


            $membership_table .= "<tr>
                                      <td>$line_number</td>
                                      <td><a href='" . home_url("maintain-donor/?customer_id=$order_customer_id") . "'>$order_customer_id</a></td>
                                      <td>$firstname</td>
                                      <td>$lastname</td>
                                      <td>$ship_to_company</td>
                                      <td>$order_billing_addresss1</td>
                                      <td>$order_billing_addresss2</td>
                                      <td>$ship_to_city</td>
                                      <td>$ship_to_state</td>
                                      <td>$ship_to_zip</td>
                                      <td >$phone</td>
                                      <td > </td>   
                                      <td >$email</td>   
                                      <td >$count</td>   
                                      <td >$orderTotal</td>   
                                  
                                     
                                </tr>";

            //  }
        }


        $membership_table .= ' </tbody> ';

        $membership_table .= '<tfoot>
                                    <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="12"></th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                  </tfoot>';

        $membership_table .= '</table>';


        return $membership_table;


    }


}

new CustomerList();
