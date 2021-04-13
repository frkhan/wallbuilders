<?php

namespace Samadhan;



class PastorAddView extends UserCRUD
{
    public  function __construct(){
        add_shortcode('smdn_pastor_form','Samadhan\PastorAddView::get_contact_form_pastor');
      }


    // Contacts form pastor
    public static function get_contact_form_pastor($atts){

        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }

        $attribute = shortcode_atts( array(
            'role' => ' '
        ), $atts );


        if(isset($_GET['user_id']) && !empty($_GET['user_id']))
        {
            $add = 'Edit';
            $save =  'Update';
            $user_id = $_GET['user_id'];
            $get_data = get_userdata($user_id);
            //var_dump($get_data);


            $Email=$get_data->data->user_email;
            $Salutation=get_user_meta($user_id,'Salutation',true);
            $FirstName=get_user_meta($user_id,'first_name',true);
            $MiddleName=get_user_meta($user_id,'MiddleName',true);
            $LastName=get_user_meta($user_id,'last_name',true);

            $Title=get_user_meta($user_id,'Title',true);
            $Church=get_user_meta($user_id,'Church',true);
            $Notes=get_user_meta($user_id,'Notes',true);
            $Address1=get_user_meta($user_id,'billing_address_1',true);
            $Address2=get_user_meta($user_id,'billing_address_2',true);
            $City=get_user_meta($user_id,'billing_city',true);
            $State=get_user_meta($user_id,'billing_state',true);
            $Zip=get_user_meta($user_id,'billing_postcode',true);
            $Phone=get_user_meta($user_id,'billing_phone',true);
            $PBAttendee=get_user_meta($user_id,'PBAttendee',true);
            $SchedulingDB=get_user_meta($user_id,'SchedulingDB',true);
            $AAPastor=get_user_meta($user_id,'AAPastor',true);
            $ListPastor=get_user_meta($user_id,'ListPastor',true);
            $BRRPastor=get_user_meta($user_id,'BRRPastor',true);
            $HISPastor=get_user_meta($user_id,'HISPastor',true);
            $NopId=get_user_meta($user_id,'NopId',true);

            $PBAttendeechecked= ($PBAttendee==='on')? 'checked':'';
            $SchedulingDBchecked= ($SchedulingDB==='on')? 'checked':'';
            $AAPastorchecked= ($AAPastor==='on')? 'checked':'';
            $ListPastorchecked= ($ListPastor==='on')? 'checked':'';
            $BRRPastorchecked= ($BRRPastor==='on')? 'checked':'';
            $HISPastorchecked= ($HISPastor==='on')? 'checked':'';
        }
        else
        {
            $add = 'Add';
            $save =  'Save';


            $userRoll=$attribute['role'];

            $Email=$_POST['Email'];
            $Salutation=$_POST['Salutation'];
            $FirstName=$_POST['FirstName'];
            $MiddleName=$_POST['MiddleName'];
            $LastName=$_POST['LastName'];
            $Title=$_POST['Title'];
            $Church=$_POST['Church'];
            $Notes=$_POST['Notes'];
            $Address1=$_POST['Address1'];
            $Address2=$_POST['Address2'];
            $City=$_POST['City'];
            $State=$_POST['State'];
            $Zip=$_POST['Zip'];
            $Phone=$_POST['Phone'];
            $PBAttendee=$_POST['PBAttendee'];
            $SchedulingDB=$_POST['SchedulingDB'];
            $AAPastor=$_POST['AAPastor'];
            $ListPastor=$_POST['ListPastor'];
            $BRRPastor=$_POST['BRRPastor'];
            $HISPastor=$_POST['HISPastor'];
            $NopId=$_POST['NopId'];
        }

        $user_pass = wp_generate_password( 100, $Email );
        $userdata = array(
            'user_login' => esc_attr($Email),
            'user_email' => esc_attr($Email),
            'user_pass' => esc_attr($user_pass),
            'first_name' => esc_attr($FirstName),
            'last_name' => esc_attr($LastName),
            'user_nicename' => esc_attr($MiddleName),
            'display_name' => esc_attr($FirstName.' '.$LastName),
        );


        $userMetaData=array(
            'MiddleName'=>$MiddleName,
            'Salutation'=>$Salutation,
            'Title'=>$Title,
            'Church'=>$Church,
            'Notes'=>$Notes,
            'billing_first_name'=>$FirstName,
            'billing_last_name'=>$LastName,
            'billing_address_1'=>$Address1,
            'billing_address_2'=>$Address2,
            'billing_city'=>$City,
            'billing_state'=>$State,
            'billing_postcode'=>$Zip,
            'billing_phone'=>$Phone,
            'PBAttendee'=>$PBAttendee,
            'SchedulingDB'=>$SchedulingDB,
            'AAPastor'=>$AAPastor,
            'ListPastor'=>$ListPastor,
            'BRRPastor'=>$BRRPastor,
            'HISPastor'=>$HISPastor,
            'NopId'=>$NopId
        );



        $getStates=parent::samadhan_get_country_states();

        $options="<option value='-1'>Select</option>";
        foreach ($getStates->data['states'] as $key=>$stateName){

            if($key==$State){

                $selected="selected";
            }else{
                $selected='';
            }
            $options .="<option $selected value='$key'>$stateName</option>";
        }

        if(isset($_POST['save']) && $_POST['save']==='Update' && !empty($Email)){
            $userdataupdate= array(
                'ID' => $user_id,
                'user_email' => esc_attr($Email),
                'user_nicename' => esc_attr($MiddleName)
            );

            $userRoll=$attribute['role'];

            $Email=$_POST['Email'];
            $Salutation=$_POST['Salutation'];
            $FirstName=$_POST['FirstName'];
            $MiddleName=$_POST['MiddleName'];
            $LastName=$_POST['LastName'];
            $Title=$_POST['Title'];
            $Church=$_POST['Church'];
            $Notes=$_POST['Notes'];
            $Address1=$_POST['Address1'];
            $Address2=$_POST['Address2'];
            $City=$_POST['City'];
            $State=$_POST['State'];
            $Zip=$_POST['Zip'];
            $Phone=$_POST['Phone'];
            $PBAttendee=$_POST['PBAttendee'];
            $SchedulingDB=$_POST['SchedulingDB'];
            $AAPastor=$_POST['AAPastor'];
            $ListPastor=$_POST['ListPastor'];
            $BRRPastor=$_POST['BRRPastor'];
            $HISPastor=$_POST['HISPastor'];
            $NopId=$_POST['NopId'];


            $userMetaData=array(

                'first_name' => esc_attr($FirstName),
                'last_name' => esc_attr($LastName),
                'MiddleName'=>$MiddleName,
                'Salutation'=>$Salutation,
                'Title'=>$Title,
                'Church'=>$Church,
                'Notes'=>$Notes,
                'billing_first_name'=>$FirstName,
                'billing_last_name'=>$LastName,
                'billing_address_1'=>$Address1,
                'billing_address_2'=>$Address2,
                'billing_city'=>$City,
                'billing_state'=>$State,
                'billing_postcode'=>$Zip,
                'billing_phone'=>$Phone,
                'PBAttendee'=>$PBAttendee,
                'SchedulingDB'=>$SchedulingDB,
                'AAPastor'=>$AAPastor,
                'ListPastor'=>$ListPastor,
                'BRRPastor'=>$BRRPastor,
                'HISPastor'=>$HISPastor,
                'NopId'=>$NopId
            );

            $results=parent::update_user_data($userdataupdate,$userMetaData);

        }


        if(isset($_POST['save']) && $_POST['save']==='Save' && !empty($Email)){
            $results=parent::save_user_data($userdata,$userMetaData,$userRoll);

        }





        $outPut='<form method="post"> <div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;">'.$add.' Pastor</h2></div>

        <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
              <div style="width:100%;margin-top: 30px; margin-left: 70px;">
              <h2>'.$results.'</h2>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Email</label>
                 <input required type="Email"  class="smdn-type" id="smdn-type" name="Email" placeholder=" " value="'.$Email.'"> 
            </div>
           
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Salutation</label>
                 <input type="text" name="Salutation" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Salutation.'">
            </div>
              <div class="smdn-form-group">
                 <label class="smdn-form-label">First Name</label>
                 <input  required type="text"  name="FirstName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$FirstName.'"> 
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Middle Name</label>
                 <input type="text"  name="MiddleName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$MiddleName.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Last Name</label>
                 <input type="text"  name="LastName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$LastName.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Title</label>
                 <input type="text" name="Title" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Title.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Church</label>
                 <input type="text" name="Church" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Church.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Notes</label>
                 <textarea type="text" name="Notes" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Notes.'">'.$Notes.'</textarea>
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Address Line 1</label>
                 <input type="text"  name="Address1" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Address1.'">
            </div>
              <div class="smdn-form-group">
                 <label class="smdn-form-label">Address Line 2</label>
                 <input type="text"  name="Address2" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Address2.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">City</label>
                 <input type="text"  name="City" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$City.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">State</label>
                 <select name="State" > 
                  '.$options.'
                   </select>
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Zip</label>
                 <input type="text"  name="Zip" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Zip.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Phone</label>
                 <input type="text"  name="Phone" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Phone.'">
            </div>
             <div class="smdn-form-group">
                 
                 <span style="padding: 0 20px"> PB Attendee</span><input '.$PBAttendeechecked.' type="checkbox" name="PBAttendee">
                 <span style="padding: 0 20px"> Scheduling DB</span><input '.$SchedulingDBchecked.' type="checkbox" name="SchedulingDB">
                 <span style="padding: 0 20px"> AA Pastor</span><input '.$AAPastorchecked.' type="checkbox" name="AAPastor">
                
            
            </div>
                 <div class="smdn-form-group">
                 
                 
                 <span style="padding: 0 20px"> List Pastor</span><input  '.$ListPastorchecked.' type="checkbox" name="ListPastor">
                 <span style="padding: 0 20px"> BRR Pastor</span><input '.$BRRPastorchecked.' type="checkbox" name="BRRPastor">
                 <span style="padding: 0 20px"> HIS Pastor</span><input '.$HISPastorchecked.' type="checkbox" name="HISPastor">
    
            
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Nop Id</label>
                 <input style="width:50%" type="text"  name="NopId" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$NopId.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label"></label>
                 <input type="submit"  name="save" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$save.'">
            </div>
        </div>

</div></form>';


        return $outPut;

    }

}


new PastorAddView();