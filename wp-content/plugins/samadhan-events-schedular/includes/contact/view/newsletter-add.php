<?php

namespace Samadhan;

class NewsletterAddView extends UserCRUD{

    public function __construct()
    {
        add_shortcode('smdn_signup_form','Samadhan\NewsletterAddView::get_contact_form_mailing_signup');
    }

    public static function get_contact_form_mailing_signup($atts){

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
            $FirstName=get_user_meta($user_id,'first_name',true);
            $MiddleName=get_user_meta($user_id,'nickname',true);
            $LastName=get_user_meta($user_id,'last_name',true);

            $Title=get_user_meta($user_id,'Title',true);
            $Address1=get_user_meta($user_id,'billing_address_1',true);
            $Address2=get_user_meta($user_id,'billing_address_2',true);
            $City=get_user_meta($user_id,'billing_city',true);
            $State=get_user_meta($user_id,'billing_state',true);
            $Zip=get_user_meta($user_id,'billing_postcode',true);
            $Phone=get_user_meta($user_id,'billing_phone',true);
            $Prompted_list=get_user_meta($user_id,'Prompted',true);

        }
        else {
            $add = 'Add';
            $save = 'Save';


            $userRoll = $attribute['role'];

            $Email = $_POST['Email'];
            $Title = $_POST['Title'];
            $Address1 = $_POST['Address1'];
            $Address2 = $_POST['Address2'];
            $Comments = $_POST['Comments'];
            $FirstName = $_POST['FirstName'];
            $MiddleName = $_POST['MiddleName'];
            $LastName = $_POST['LastName'];
            $City = $_POST['City'];
            $State = $_POST['State'];
            $Zip = $_POST['Zip'];
            $Phone = $_POST['Phone'];
            $Prompted_list = $_POST['Prompted'];
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
            'Title'=>$Title,
            'billing_first_name'=>$FirstName,
            'billing_last_name'=>$LastName,
            'billing_address_1'=>$Address1,
            'billing_address_2'=>$Address2,
            'billing_city'=>$City,
            'billing_state'=>$State,
            'billing_postcode'=>$Zip,
            'billing_phone'=>$Phone,
            'Comments'=>$Comments,
            'Prompted'=>$Prompted_list

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


        $options1="<option value='-1'>Select</option>";
        $prompted=array(
            'Wallbuilder Post Card',
            'Catalog',
            'Church',
            'Friends Or Family',
            'NewsLetter',
            'Other',
            'Presentation',
            'Radio',
            'TV Cornerstone',
            'TV Daystar',
            'TV KennethCopeland',
            'TV SkyAngel',
            'TV TBN',
            'TV Other'
        );
        foreach ($prompted as $promp){
            $Get_Prompted_list=get_user_meta($user_id,'Prompted',true);
            //var_dump($Get_Prompted_list);
            $Prompted_list_updated = $_POST['Prompted'];
            if($Get_Prompted_list==$promp || $Prompted_list_updated==$promp)
            {
                $selected= 'selected= "selected"';
            }
            else
            {
                $selected = '';
            }
            $options1 .="<option $selected value='$promp'>$promp</option>";
        }


        if(isset($_POST['save']) && $_POST['save']=='Update' && !empty($Email)){
            $userdataupdate= array(
                'ID' => $user_id,
                'user_email' => esc_attr($Email),
            );

            $userRoll = $attribute['role'];

            $Email = $_POST['Email'];
            $Title = $_POST['Title'];
            $Address1 = $_POST['Address1'];
            $Address2 = $_POST['Address2'];
            $Comments = $_POST['Comments'];
            $FirstName = $_POST['FirstName'];
            $MiddleName = $_POST['MiddleName'];
            $LastName = $_POST['LastName'];
            $City = $_POST['City'];
            $State = $_POST['State'];
            $Zip = $_POST['Zip'];
            $Phone = $_POST['Phone'];
            $Prompted_list = $_POST['Prompted'];

            $userMetaData=array(
                'first_name' => esc_attr($FirstName),
                'last_name' => esc_attr($LastName),
                'Title'=>$Title,
                'billing_first_name'=>$FirstName,
                'billing_last_name'=>$LastName,
                'billing_address_1'=>$Address1,
                'billing_address_2'=>$Address2,
                'billing_city'=>$City,
                'billing_state'=>$State,
                'billing_postcode'=>$Zip,
                'billing_phone'=>$Phone,
                'Comments'=>$Comments,
                'Prompted'=>$Prompted_list

            );



            $results=parent::update_user_data($userdataupdate,$userMetaData);

        }


        if(isset($_POST['save']) && $_POST['save']=='Save' && !empty($Email)){
            $results=parent::save_user_data($userdata,$userMetaData,$userRoll);

        }



        $outPut='<form method="post"> <div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;">'.$add.' Your Contact Information</h2></div>

        <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
              <div style="width:100%;margin-top: 30px; margin-left: 70px;">
              <h2>'.$results.'</h2>
              <div class="smdn-form-group">
                 <label class="smdn-form-label">Title</label>
                 <input  required type="text"  name="Title" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$Title.'"> 
            </div>
            
              <div class="smdn-form-group">
                 <label class="smdn-form-label">First Name</label>
                 <input  required type="text"  name="FirstName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$FirstName.'"> 
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Last Name</label>
                 <input required type="text"  name="LastName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$LastName.'">
            </div>

            <div class="smdn-form-group">
                 <label class="smdn-form-label">Mailing Address 1</label>
                 <input  required type="text"  name="Address1" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$Address1.'" >
            </div>
            <div class="smdn-form-group">
                  <label class="smdn-form-label">Mailing Address 2</label>
                 <input required type="text"  name="Address2" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Address2.'">
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
                 <label class="smdn-form-label">Email Address</label>
                 <input required type="Email"  class="smdn-type" id="smdn-type" name="Email" placeholder=" " value="'.$Email.'"> 
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">What prompted you to sign up on our mailing list today?</label>
                 <select name="Prompted" > 
                  '.$options1.'
                   </select>
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Comments</label>
                 <input type="text"  name="Comments" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Comments.'">
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

new NewsletterAddView();