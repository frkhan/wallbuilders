<?php

namespace Samadhan;
use Samadhan\UserCRUD;

class UpdateDonorNote extends MajorDonorReports {

    public function __construct()
    {
        add_shortcode('pod_update_donor_note','Samadhan\UpdateDonorNote::get_edit_donor_note_form');
    }


    public static function get_edit_donor_note_form(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }


        if(isset($_POST["getDonorSearch"]) && !empty($_POST["getDonorSearch"])){
            $customer_id=$_POST["WooId"];
        }else{
            $customer_id=$_GET["customer_id"];
            $order_id=$_GET["order_id"];
            $nop_id=$_GET["nop_id"];


        }

// if(self::authorized()){

        /*
        $consumer_key = get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret = get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


        $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,true);
        */
        $wc_api = parent::getWCApiClient();
        //$data=new Samadhan\SMDNdonorFormView();
        $major_donor= self::get_edit_donation_note_forms($wc_api,$customer_id,$order_id,$nop_id);

        return $major_donor;



// return rest_ensure_response(array('status'=>$data));


// }

    }


    public static function get_edit_donation_note_forms($wc_api,$customer_id,$order_id,$nop_id){

        if(!empty($customer_id)){

            $customerData = $wc_api->get_customer($customer_id) ;

            if(isset($order_id) && !empty($order_id)){
                $getWooNote= $wc_api->get_order_notes( $order_id);
                $getOrder_id=$order_id;
                $noteUrl="update-note/?customer_id=$customer_id&order_id=$order_id";


                //var_dump($getWooNote);
            }
            if(isset($nop_id) && !empty($nop_id)){
                $getNopNote=parent::get_MajorDonor_note_by_user_id($customer_id,$nop_id);
                $getNote=$getNopNote[0]->Note;
                $note_created_date= $getNopNote[0]->CreateDate;
                $note_update_date= $getNopNote[0]->ModifyDate;
                $getOrder_id=$nop_id;
                $noteUrl="update-note/?customer_id=$customer_id&nop_id=$order_id";
                // var_dump($getNopNote);
            }



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



            if(isset($_POST["Update"])){
                $user_id=$_POST["wooId"];
                $NopID=$_POST['nopId'];
                $MajorDonorId=$_POST["majorDonorId"];
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
                $noteAdded=$_POST["noteAdded"];
                $noteUpdate=$_POST["noteUpdate"];
                $note=$_POST["note"];

                $arrayNoteData=array(
                    'WooId'=>$user_id,
                    'MajorDonorId'=>$MajorDonorId,
                    'NopId'=>$NopID,
                    'CreateDate'=>$noteAdded,
                    'CreatedBy'=>$FirstName .'  '. $LastName,
                    'ModifyDate'=>$noteUpdate,
                    'ModifiedBy'=>$FirstName .'  '. $LastName,
                    'Note'=>$note,
                );

                $customer_details = [

                    'id' => $user_id,
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

                //$customer_id=$majorDonor["WooId"];
                $where=array("WooId"=>$customer_id,"NopId"=>$NopID);
                $getResults=parent::update_MajorDonor_note_data($arrayNoteData,$where);
                //if($results>0){
                //  $results=EventFunctons::save_MajorDonor_note_data($majorDonor);
                //}

                if($getResults){
                    $getNote=$getResults[0]->Note;
                    $note_created_date= $getResults[0]->CreateDate;
                    $note_update_date= $getResults[0]->ModifyDate;
                    $message="<h2>Update Successfuly !!!</h2>";
                }else{
                    $message="<h2>Update Unsuccessfuly !!!</h2>";
                }

            }

            if(isset($_POST['Delete']) && !empty($_POST['Delete'])){
                $NopID=$_POST['nopId'];
                $where=array("WooId"=>$customer_id,"NopId"=>$NopID);
                $getResults=parent::delete_MajorDonor_note_data($where);
                if($getResults){
                    $noteUrl="maintain-donor/?customer_id=$customer_id";
                    $message="<h2>Delete Successfuly !!!</h2>";
                }else{
                    $message="<h2>Delete Unsuccessfuly !!!</h2>";
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

        $formView ='<form id="" method="post" action="'.home_url('/'.$noteUrl).'" >
                           <div class="smdn-form-group">
                             
                           </div>
                            <div class="smdn-form-group">
                                 <h2>Major Donor Notes</h2>
                           </div>
                           <div  >'.$message.'</div><hr/>
                     <div class="smdn-form-group" >
                      <label class="smdn-form-label">WooId</label>
                      <input type="text" name="wooId" class="smdn-start-date" id="smdn-start-date" placeholder=" " value="'.$customer_id.'">
                      <input type="hidden" name="nopId" class="smdn-nopId" id="smdn-nopId" placeholder=" " value="'.$getOrder_id.'">
                      <input type="hidden" name="orderId" class="smdn-orderId" id="smdn-orderId" placeholder=" " value="'.$order_id.'">
                     </div>
                      <div class="smdn-form-group" >
                      <label class="smdn-form-label">Major Donor Id</label>
                      <input type="text" name="majorDonorId" class="smdn-majorDonorId" id="smdn-majorDonorId" placeholder=" " value="'.$major_donor_id.'">
                     </div>
                     
                      <div class="smdn-form-group">
                          <label class="smdn-form-label">Name</label>
                          <input type="text"  name="FirstName" class="smdn-FirstName" id="smdn-FirstName"  placeholder=" " value="'.$first_name.' '.$last_name.'">
                          </div>
                    
                       <div class="smdn-form-group">
                          <label class="smdn-form-label">Company</label>
                          <input type="text"    name="Company" class="smdn-company" id="smdn-company"  placeholder=" " value="'.$company.'">
                     </div>
                     
                     <div class="smdn-form-group">
                          <label class="smdn-form-label">Address1</label>
                          <input  type="text"  name="Address1" class="smdn-address1" id="smdn-address1"  placeholder=" " value="'.$address_1.'">
                     </div>
                     <div class="smdn-form-group">
                          <label class="smdn-form-label">Address2</label>
                          <input  type="text"  name="Address2" class="smdn-address2" id="smdn-address2"  placeholder=" " value="'.$address_2.'">
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
                          <input type="text"  class="smdn-zip" name="Zip" id="smdn-zip"  placeholder=" " value="'.$postcode.'">
                     </div>
                     <div class="smdn-form-group">
                          <label class="smdn-form-label">Note Added</label>
                          <input  type="text" class="smdn-noteAdded" name="noteAdded" id="smdn-noteAdded"  placeholder=" " value="'.$note_created_date.'">
                       
                     </div>
                     
                     <div class="smdn-form-group">
                          <label class="smdn-form-label">Note Updated</label>
                          <input  type="text" class="smdn-noteUpdate" name="noteUpdate" id="smdn-noteUpdate"  placeholder=" " value="'.$note_update_date.'">
                       
                     </div>
                    
                      <div class="smdn-form-group">
                          <label class="smdn-form-label" > Note</label>
                          <textarea  type="text" name="note" class="smdn-note" id="smdn-note"  placeholder=" " value="'.$getNote.'">'.$getNote.'</textarea>
                      </div>
                      
                      <div class="smdn-form-group">
                          <input type="submit" class="smdn-btn smdn-btn-info" id=""  name="Delete" placeholder=" " value="Delete">
                          <input type="submit" class="smdn-btn smdn-btn-info" id=""  name="Update" placeholder=" " value="Update">
                    
                     </div>
                     
                    </form>';


        return $formView;
    }




}
new UpdateDonorNote();