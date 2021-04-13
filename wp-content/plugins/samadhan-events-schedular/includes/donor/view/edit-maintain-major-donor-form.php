<?php

namespace Samadhan;
use Samadhan\UserCRUD;

class EditMaintainMajorDonor extends MajorDonorReports {


    public function __construct()
    {
        add_shortcode('pod_maintain_edit_major_donor_form','Samadhan\EditMaintainMajorDonor::get_edit_maintain_major_donor_form');
    }



    public static function get_edit_maintain_major_donor_form(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }


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
        $wc_api = parent::getWCApiClient();

        $major_donor= self::get_edit_maintain_major_donor_forms($wc_api,$customer_id);
        $major_donor .= self::get_donation_history_reports($wc_api,$customer_id);
        $major_donor .= self::get_donation_history_database_reports($wc_api,$customer_id);
        $major_donor .= self::get_donation_woo_notes($wc_api,$customer_id);
        $major_donor .= self::get_donation_data_base_notes($wc_api,$customer_id);
        
        
        return $major_donor;
        
        // return   rest_ensure_response(array('status'=>$data));


        // }

    }


    public static function get_edit_maintain_major_donor_forms($wc_api,$customer_id){
        //var_dump($customer_id);
        if(!empty($customer_id)){
            $customerData = $wc_api->get_customer($customer_id) ;


            $customer_id=$customer_id;
            $first_name=$customerData->first_name;
            $last_name=$customerData->last_name;
            $company=$customerData->billing->company;
            $address_1=$customerData->billing->address_1;
            $address_2=$customerData->billing->address_2;
            $city=$customerData->billing->city;
            $state=$customerData->billing->state;
            $postcode=$customerData->billing->postcode;
            $country=$customerData->billing->country;
            $email=$customerData->email;
            $phone=$customerData->billing->phone;
            $last_order_id=$customerData->last_order_id;



            $major_donor = parent::get_MajorDonor_by_user_id($customer_id);
            foreach ($major_donor as $donor)
            {
                $major_donor_id=$donor->ConnectId;
                $Nop_id = $donor->NopID;
                $currentLevel = $donor->currentLevel;

                $mail = $donor->emailOption;
                $activeStatus = $donor->activeStatus;

            }

            $getEmailOptions=array('Opt In','Opt Out','Donor Gifts Only','No MD TY Gifts','Receipts Only','No TY Letters','Year End Tax Only');
            $emailOptions=' <option value="Opt In">Select</option>';
            foreach ($getEmailOptions as $emailOption){
                $emailChecked=($emailOption==$mail) ? "selected" : "";
                $emailOptions .=' <option  '.$emailChecked.' value="'.$emailOption.'">'.$emailOption.'</option>';
            }



            $checkedActiveStatus=($activeStatus==1) ? "checked" : "";


            //var_dump($activeStatus);
            //var_dump($checkedActiveStatus);



            if(isset($_POST["Update"])){
                $user_id=$customer_id;
                $NopID=$_POST["NopID"];
                $FirstName=$_POST["FirstName"];
                $LastName=$_POST["LastName"];
                $Company=$_POST["Company"];
                $Email=$_POST["Email"];
                $Address1=$_POST["Address1"];
                $Address2=$_POST["Address2"];
                $City=$_POST["City"];
                $State=$_POST["State"];
                $Zip=$_POST["Zip"];
                $Phone=$_POST["Phone"];
                $activeStatus=$_POST["activeStatus"];
                $currentLevel=$_POST["currentLevel"];
                $emailOption=$_POST["emailOption"];
                $totalDonationsCount=$_POST["totalDonationsCount"];
                $totalDonationAmount=$_POST["totalDonationAmount"];
                $totalDonationCtLY=$_POST["totalDonationCtLY"];
                $totalDonationAmtLY=$_POST["totalDonationAmtLY"];
                $arrayMajorDonorData=array(

                    'WooId'=>$customer_id,
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

                    'id' => $customer_id,
                    'email' => $Email,
                    'first_name' => $FirstName,
                    'last_name' => $LastName,

                    'billing' => [
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

                ];

                $result= parent::set_update_MajorDonor_data($customer_details,$arrayMajorDonorData);

                if($result->id){
                    $order_id=$get_order_id;
                    $currency=$get_currency;
                    $customer_id=$result->id;
                    $first_name=$result->billing->first_name;
                    $last_name=$result->billing->last_name;
                    $company=$result->billing->company;
                    $address_1=$result->billing->address_1;
                    $address_2=$result->billing->address_2;
                    $city=$result->billing->city;
                    $state=$result->billing->state;
                    $postcode=$result->billing->postcode;
                    $country=$result->billing->country;
                    $email=$result->billing->email;
                    $phone=$result->billing->phone;
                    $total=$get_total;
                    $total_quantity=$get_total_quantity;


                    update_user_meta($user_id,'_order_id',$NopID);
                    update_user_meta($user_id,'_order_count',$totalDonationsCount);
                    update_user_meta($user_id,'_money_spent',$totalDonationAmount);
                    update_user_meta($user_id,'emailOption',$emailOption);
                    update_user_meta($user_id,'activeStatus',$activeStatus);
                    update_user_meta($user_id,'currentLevel',$currentLevel);
                    update_user_meta($user_id,'totalDonationCtLY',$totalDonationCtLY);
                    update_user_meta($user_id,'totalDonationAmtLY',$totalDonationAmtLY);
                    $message="<h2>Update Successfuly !!!</h2>";
                }else{
                    $message="<h2>Update Unsuccessfuly !!!</h2>";
                }

            }


            $getStates=parent::samadhan_get_country_states();

            $options="<option value='-1'>Select</option>";
            foreach ($getStates->data['states'] as $key=>$stateName){

                if($key==$state){

                    $selected="selected";
                }else{
                    $selected='';
                }
                $options .="<option $selected value='$key'>$stateName</option>";
            }

        }

        $formView ='<form id="" method="post" action="'.home_url('/maintain-donor/?customer_id='.$customer_id).'" >
         <div class="smdn-form-group">
           
         </div>
          <div class="smdn-form-group">
               <h2>Maintain Major Donor</h2>
         </div>
         <div  >'.$message.'</div><hr/>
   <div class="smdn-form-group" >
        <label class="smdn-form-label"> Woo Id</label>
        <input type="text"  name="WooId" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$customer_id.'">
        <button type="submit" name="getDonorSearch" value="donorName">Get Donor</button>
      
   </div>
   <div class="smdn-form-group" >
    <label class="smdn-form-label">Major Donor Id</label>
    <input type="text" name="majorDonorId" class="smdn-start-date" id="smdn-start-date" placeholder=" " value="'.$major_donor_id.'">
   </div>
   
    <div class="smdn-form-group">
        <label class="smdn-form-label">First Name</label>
        <input type="text"  name="FirstName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$first_name.'">
   </div>
     <div class="smdn-form-group">
        <label class="smdn-form-label">Last Name</label>
        <input type="text"  name="LastName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$last_name.'">
   </div>
     <div class="smdn-form-group">
        <label class="smdn-form-label">Company</label>
        <input type="text"    name="Company" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$company.'">
   </div>
   
   <div class="smdn-form-group">
        <label class="smdn-form-label">Email</label>
        <input  type="text"  name="Email" class="smdn-setting" id="smdn-setting"  placeholder=" " value="'.$email.'">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Address1</label>
        <input  type="text"  name="Address1" class="smdn-address" id="smdn-address"  placeholder=" " value="'.$address_1.'">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Address2</label>
        <input  type="text"  name="Address2" class="smdn-address" id="smdn-address"  placeholder=" " value="'.$address_2.'">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">City, State</label>
        <input  type="text" name="City" class="smdn-airport" id="smdn-airport"  placeholder=" " value="'.$city.'">
              <select type="text"  name="State" class="smdn-State" id="smdn-State"  >
                       '.$options.'
              </select> 
    </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Zip Code</label>
        <input type="text"  class="smdn-attendence" name="Zip" id="smdn-attendence"  placeholder=" " value="'.$postcode.'">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Phone</label>
        <input  type="text" class="smdn-audience" name="Phone" id="smdn-audience"  placeholder=" " value="'.$phone.'">
     
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label" > Inactive</label>
        <input   type="checkbox" name="activeStatus" class="smdn-involvement"  '.$checkedActiveStatus.' id="smdn-involvement"   placeholder=" " value="'.$activeStatus.'">
    
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label" > Mail</label>
        <select  name="emailOption" name="Mail" id="">
              '.$emailOptions.'
            
            </select>
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label" > Current Level</label>
        <input  type="text" name="currentLevel" class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="'.$currentLevel.'">
    
   </div>
     <div class="smdn-form-group">
        <label class="smdn-form-label" >Nop Id</label>
        <input  type="text"   name="NopID" class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="'.$Nop_id.'">
    
   </div>
    <div class="smdn-form-group">
         
        <label class="smdn-form-label" >Donations In Last Year</label>
    </div><hr/>
    <div class="smdn-form-group">
        <label class="smdn-form-label" >Count</label>
        <input  name="totalDonationsCount" type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="'.$total_quantity.'">
    </div>
    <div class="smdn-form-group">
          <label class="smdn-form-label" >Total</label>
        <input   type="text"  name="totalDonationAmount" class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="'.$total.'">
   </div>
   
    <div class="smdn-form-group">
         
        <label class="smdn-form-label" >Donations In History</label>
    </div><hr/>
    <div class="smdn-form-group">
        <label class="smdn-form-label" >Count</label>
        <input   name="totalDonationCtLY" type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="'.$total_quantity.'">
    </div>
    <div class="smdn-form-group">
          <label class="smdn-form-label" >Total</label>
        <input  type="text"   name="totalDonationAmtLY" class="smdn-involvement" id="smdn-involvement"  placeholder=" '.$total.'" value="">
   </div>


     <div class="smdn-form-group">
        <input type="submit" class="smdn-btn smdn-btn-info" id=""  name="Update" placeholder=" " value="Update">
    
   </div>
  </form>';


        return $formView;
    }

    public  static function  get_donation_history_reports($wc_api,$customer_id){


        $linesPerPage = 10;
        $currentPageNumber = 0;
        $filterData= array("customer"=>$customer_id,"per_page"=>$linesPerPage, "offset"=>$currentPageNumber);

        if(isset($_POST['currentPageNumber']) && !empty($_POST['currentPageNumber'])){
            $currentPageNumber = $_POST['currentPageNumber'];
        }

        if(isset($_POST['searchButton']) && !empty($_POST['filterName'])){
            $searchName=$_POST['filterName'];
            $filterData= array("customer"=>$customer_id,"search"=>$searchName, "per_page"=>$linesPerPage, "offset"=>$currentPageNumber);
        }
        // $filterData= array('customer'=>$customer_id,'per_page'=>200);


        //var_dump($customer_id);
        // var_dump($allOrders);


        //  $allOrders = $wc_api->get_customer_orders($customer_id);

        $membership_table= '<div id=""><h2>Woo Order History</h2>';
        $membership_table .="<div>
                                <form method='post' name='form' style='background: #0a4b78; float: right;width: 100%' >
                                    <div style='float: right'>
                                    <input type='hidden' id='currentPageNumber' name='currentPageNumber' value='".$currentPageNumber."'>
                                    <input type='text' name='filterName' value='".$searchName."'>
                                    <input type='submit' id='searchButton' name='searchButton' value='Search'>
                                    </div>
                                </form>
                            </div>
                            <br/> ";
        $membership_table.='<table class="table"><thead style="color: white; background-color: rgb(69, 88, 97);">

                                   <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="4"></th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Sl</th>
                                        <th rowspan="2">Date</th>
                                        <th rowspan="2">Order#</th>
                                        <th rowspan="2">First Name</th>
                                        <th rowspan="2">Last Name</th>
                                        <th rowspan="2">Subtotal</th>
                                        <th rowspan="2">Total</th>
                                      </tr>
                                     
                                  </thead>';
        $membership_table.='<tbody>';

        if(!empty($customer_id)){

            $allOrders = $wc_api->get_customer_orders($filterData) ; //$wc_api->get_customer_orders($filterData);


            $sl_no = 0;
            $grand_total=0;
            $grand_total_tax=0;
            $line_number = 0 + $currentPageNumber * $linesPerPage;
            foreach($allOrders as $order ){
                $line_number++;
                $order_id=$order->id;

                //  var_dump($order);


                $order_status=$order->status;
                $order_paid_status=($order->payment_details->paid ? 'Paid': 'Unpaid');

                $order_customer_id=$order->customer->id;
                $firstname=$order->billing->first_name;
                $lastname=$order->billing->last_name;
                $ship_to_company=$order->customer->billing->company;
                $order_billing_addresss1=$order->customer->billing->address_1;
                $order_billing_addresss2=$order->customer->billing->address_2;
                $ship_to_city=$order->customer->billing->city;
                $ship_to_state=$order->customer->billing->state;
                $ship_to_zip=$order->customer->billing->zip;
                $order_date=date_i18n( 'Y-m-d H:m:s', strtotime($order->date_created) );
                $paid_date=date_i18n( get_option( 'date_format' ), strtotime($order->completed_at) );
                $total_wo_gst=$order->total;
                $order_total=$order->total;


                if(!empty($order_id) && isset($customer_id) && !empty($customer_id)){

                    $membership_table.="<tr>
                                      <td>$line_number</td>
                                      <td>$order_date</td>
                                      <td>$order_id</td>
                                      <td>$firstname</td>
                                      <td>$lastname</td>           
                                      <td>" . wc_price($order_total) . "</td>
                                      <td>" . wc_price($order_total) . "</td>
                                     
                                </tr>";

                }
            }
        }
        $membership_table.=' </tbody> ';

        $membership_table.='<tfoot>    <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan="4"></th>
                                      <th></th>                                  
                                      <th><a href="JavaScript:PrevPage();">Prev</a> </td>
                                      <th><a href="JavaScript:NextPage();">Next</a> </td>                                   
                                    </tr>
                                    </tfoot>';

        $membership_table.='</table></div>';


        return $membership_table;


    }

    public static function  get_donation_history_database_reports($wc_api,$customer_id){
        $donorHistory=parent::get_MajorDonor_history_data_by_user_id($customer_id);



        $membership_table= '<div id=""><h2>Nop Donation History</h2><table class="table">';
        $membership_table.='<thead style="color: white; background-color: rgb(69, 88, 97);">
                                    <tr>
                                        <th rowspan="2">Date</th>
                                        <th rowspan="2">Source</th>
                                        <th rowspan="2">Order/Trans#</th>
                                        <th rowspan="2">Amount</th>
                                       
                                      </tr>
                                     
                                  </thead>';
        $membership_table.='<tbody>';


        foreach($donorHistory as $donor ){


            //   var_dump($donor);


            $order_date=$donor->OriginalCreateDate;
            $Source=$donor->Source;
            $OrderNumber=$donor->OrderNumber;
            $TotalAmount=$donor->TotalAmount;



            if(isset($customer_id) && !empty($customer_id)){

                $membership_table.="<tr>
                                      <td>$order_date</td>
                                      <td>$Source</td>
                                      <td>$OrderNumber</td>
                                                        
                                      <td>" . wc_price($TotalAmount) . "</td>
                                     
                                </tr>";

            }
        }


        $membership_table.=' </tbody> ';

        $membership_table.='<tfoot><tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan=""></th>
                                      <th></th>                                  
                                      <th></td>
                                      <th></td>                                  
                                    </tr></tfoot>';

        $membership_table.='</table></div>';


        return $membership_table;


    }

    public static function get_donation_woo_notes($wc_api,$customer_id){

        $linesPerPage = 10;
        $currentPageNumber = 0;
        $filterData= array("customer"=>$customer_id,"per_page"=>$linesPerPage, "offset"=>$currentPageNumber);
        if(isset($_POST['currentPageNumber']) && !empty($_POST['currentPageNumber'])){
            $currentPageNumber = $_POST['currentPageNumber'];
        }

        if(isset($_POST['searchButton']) && !empty($_POST['filterName'])){
            $searchName=$_POST['filterName'];
            $filterData= array("customer"=>$customer_id,"search"=>$searchName, "per_page"=>$linesPerPage, "offset"=>$currentPageNumber);
        }



        $membership_table = '<div id=""><h2>Woo Donation Notes</h2><table class="table">';
        $membership_table .="<div>
                                <form method='post' name='form' style='background: #0a4b78; float: right;width: 100%' >
                                    <div style='float: right'>
                                    <input type='hidden' id='currentPageNumber' name='currentPageNumber' value='".$currentPageNumber."'>
                                    <input type='text' name='filterName' value='".$searchName."'>
                                    <input type='submit' id='searchButton' name='searchButton' value='Search'>
                                    </div>
                                </form>
                            </div>
                            <br/> ";
        $membership_table.='<thead style="color: white; background-color: rgb(69, 88, 97);">
                                <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan=""></th>
                                      <th></th>                                  
                                      <th> </td>
                                      <th><a href="JavaScript:PrevPage();">Prev</a> <a href="JavaScript:NextPage();">Next</a> </td>                                   
                                </tr>
                                <tr>
                                <th rowspan="2">Sl#</th>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Note</th>
                                <th rowspan="2">Created By</th>
                                </tr>
                                
                                </thead>';
        $membership_table.='<tbody>';

        $allOrders = $wc_api->get_customer_orders($filterData) ;
        if(isset($customer_id) && !empty($customer_id) && $allOrders->errors[0]->code !='200'){


            $line_number = 0 + $currentPageNumber * $linesPerPage;

            foreach($allOrders as $order ){
                //var_dump($donor_note);

                $getAllWooNotes= $wc_api->get_order_notes( $order->id );
                if(isset($getAllWooNotes) && !empty($getAllWooNotes) && $getAllWooNotes->errors[0]->code !='200') {
                    //  foreach($getAllWooNotes as $getWooNote){
                    $line_number++;
                    $note_created_date = $getAllWooNotes[0]->date_created;
                    $note_created_by = $getAllWooNotes[0]->author;
                    $note = $getAllWooNotes[0]->note;
                    $customer_note = $getAllWooNotes[0]->customer_note;
                }
//var_dump($customer_note);

                $membership_table.="<tr>
                                    <td> $line_number</td>
                                    <td><a href='".home_url('/update-note/?customer_id='.$customer_id.'&order_id='.$order->id)."'>$note_created_date</a></td>
                                    <td>$note</td>
                                    <td>$note_created_by</td>
                                    
                                    </tr>";


                //  }
            }
        }


        $membership_table.=' </tbody> ';

        $membership_table.='<tfoot><tr style="color: white; background-color: rgb(69, 88, 97);">
                            <th scope="row" colspan=""></th>
                            <th></th>
                            <th></td>
                            <th></td>
                           
                            </tr>
                              <tr  style="color: white; background-color: rgb(69, 88, 97);">
                                      <th scope="row" colspan=""></th>
                                      <th></th>                                  
                                      <th></td>
                                      <th> <a href="JavaScript:PrevPage();">Prev</a> <a href="JavaScript:NextPage();">Next</a> </td>                                   
                                </tr>
                            
                            </tfoot>';

        $membership_table.='</table></div>';


        return $membership_table;


    }

    public static function get_donation_data_base_notes($wc_api,$customer_id){
        $donor_notes=parent::get_MajorDonor_note_by_user_id($customer_id);



        $membership_table= '<div id=""><h2>Nop Donation Notes</h2><table class="table">';
        $membership_table.='<thead style="color: white; background-color: rgb(69, 88, 97);">
                                <tr>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Note</th>
                                <th rowspan="2">Created By</th>
                                </tr>
                                
                                </thead>';
        $membership_table.='<tbody>';


        foreach($donor_notes as $donor_note ){

            $note_created_date=$donor_note->CreateDate;
            $note=$donor_note->Note;
            $note_created_by=$donor_note->CreatedBy;
            $order_id=$donor_note->NopId;


            if(isset($customer_id) && !empty($customer_id)){

                $membership_table.="<tr>
                                    <td><a href='".home_url('/update-note/?customer_id='.$customer_id.'&nop_id='.$order_id)."'>$note_created_date</a></td>
                                    <td>$note</td>
                                    <td>$note_created_by</td>
                                    
                                    </tr>";

            }
        }


        $membership_table.=' </tbody> ';

        $membership_table.='<tfoot><tr style="color: white; background-color: rgb(69, 88, 97);">
                            <th scope="row" colspan=""></th>
                            <th></th>
                            <th></td>
                           
                            </tr></tfoot>';

        $membership_table.='</table></div>';


        return $membership_table;


    }




}

new EditMaintainMajorDonor();