<?php

namespace Samadhan;

class InternAddView extends UserCRUD{

    public  function __construct(){

        add_shortcode('smdn_intern_form','Samadhan\InternAddView::get_intern_form');
    }


    public static function get_intern_form($atts){

        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }


        $attribute = shortcode_atts( array(
            'role' => ''
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
            //$MiddleName=get_user_meta($user_id,'nickname',true);
            $Address=get_user_meta($user_id,'billing_address_1',true);
            $LastName=get_user_meta($user_id,'last_name',true);
            $DateofBirth=get_user_meta($user_id,'Dateofbirth',true);
            $City=get_user_meta($user_id,'billing_city',true);
            $State=get_user_meta($user_id,'billing_state',true);
            $Zip=get_user_meta($user_id,'billing_postcode',true);
            $Phone=get_user_meta($user_id,'billing_phone',true);
            $Currentschool=get_user_meta($user_id,'Currentschool',true);
            $Major=get_user_meta($user_id,'Major',true);
            $Occupations=get_user_meta($user_id,'Occupations',true);
            $meetaclass=get_user_meta($user_id,'Meetaclass',true);
            $Postgraduatelevel=get_user_meta($user_id,'Postgraduatelevel',true);
            $Participateprogram=get_user_meta($user_id,'Participateprogram',true);
            $expectingprogram=get_user_meta($user_id,'Expectingprogram',true);
            $strengthsexpertise=get_user_meta($user_id,'Strengthsexpertise',true);
            $weaknessesworking=get_user_meta($user_id,'Weaknessesworking',true);
            $socialmediaaccounts=get_user_meta($user_id,'Socialmediaaccounts',true);
            $abouttheprogram=get_user_meta($user_id,'Abouttheprogram',true);
            $attachresume=get_user_meta($user_id,'Attachresume',true);




        }
        else {
            $add = 'Add';
            $save = 'Save';

            $userRoll = $attribute['role'];

            //$MiddleName = $_POST['MiddleName'];
            $FirstName = $_POST['FirstName'];
            $LastName = $_POST['LastName'];
            $Address = $_POST['Address'];
            $DateofBirth = $_POST['Dateofbirth'];
            $City = $_POST['City'];
            $State = $_POST['State'];
            $Zip = $_POST['Zip'];
            $Phone = $_POST['Phone'];
            $Email = $_POST['Email'];
            $Currentschool = $_POST['Currentschool'];
            $Major = $_POST['Major'];
            $Occupations = $_POST['Occupations'];
            $sessionattend = $_POST['Sessionattend'];
            $meetaclass = $_POST['Meetaclass'];
            $Postgraduatelevel = $_POST['Postgraduatelevel'];
            $Participateprogram = $_POST['Participateprogram'];
            $expectingprogram = $_POST['Expectingprogram'];
            $strengthsexpertise = $_POST['Strengthsexpertise'];
            $weaknessesworking = $_POST['Weaknessesworking'];
            $socialmediaaccounts = $_POST['Socialmediaaccounts'];
            $abouttheprogram = $_POST['Abouttheprogram'];
            $attachresume = $_POST['Attachresume'];
        }

        $user_pass = wp_generate_password( 100, $Email);

        $userdata = array(
            'user_login' => esc_attr($Email),
            'user_email' => esc_attr($Email),
            'user_pass' => esc_attr($user_pass),
            'first_name' => esc_attr($FirstName),
            'last_name' => esc_attr($LastName),
            'user_nicename' => esc_attr($FirstName),
            'display_name' => esc_attr($FirstName.' '.$LastName),
        );

        $userMetaData=array(

            'first_name'=>$FirstName,
            'last_name'=>$LastName,
            'Dateofbirth'=>$DateofBirth,
            'billing_first_name'=>$FirstName,
            'billing_last_name'=>$LastName,
            'billing_address_1'=>$Address,
            'billing_address_2'=>'',
            'billing_city'=>$City,
            'billing_state'=>$State,
            'billing_postcode'=>$Zip,
            'billing_phone'=>$Phone,
            'Email'=>$Email,
            'Currentschool'=>$Currentschool,
            'Major'=>$Major,
            'Occupations'=>$Occupations,
            'Sessionattend'=>$sessionattend,
            'Meetaclass'=>$meetaclass,
            'Postgraduatelevel'=>$Postgraduatelevel,
            'Participateprogram'=>$Participateprogram,
            'Expectingprogram'=>$expectingprogram,
            'Strengthsexpertise'=>$strengthsexpertise,
            'Weaknessesworking'=>$weaknessesworking,
            'Socialmediaaccounts'=>$socialmediaaccounts,
            'Abouttheprogram'=>$abouttheprogram,
            'Attachresume'=>$attachresume

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

        if(isset($_POST['save']) && $_POST['save']=='Update' && !empty($Email)){
            $userdataupdate= array(
                'ID' => $user_id,
                'user_email' => esc_attr($Email),
            );

            $userRoll = $attribute['role'];

            $FirstName = $_POST['FirstName'];
            $LastName = $_POST['LastName'];
            $Address = $_POST['Address'];
            $DateofBirth = $_POST['Dateofbirth'];
            $City = $_POST['City'];
            $State = $_POST['State'];
            $Zip = $_POST['Zip'];
            $Phone = $_POST['Phone'];
            $Email = $_POST['Email'];
            $Currentschool = $_POST['Currentschool'];
            $Major = $_POST['Major'];
            $Occupations = $_POST['Occupations'];
            $sessionattend = $_POST['Sessionattend'];
            $meetaclass = $_POST['Meetaclass'];
            $Postgraduatelevel = $_POST['Postgraduatelevel'];
            $Participateprogram = $_POST['Participateprogram'];
            $expectingprogram = $_POST['Expectingprogram'];
            $strengthsexpertise = $_POST['Strengthsexpertise'];
            $weaknessesworking = $_POST['Weaknessesworking'];
            $socialmediaaccounts = $_POST['Socialmediaaccounts'];
            $abouttheprogram = $_POST['Abouttheprogram'];
            $attachresume = $_POST['Attachresume'];


            $userMetaData=array(

                'first_name'=>$FirstName,
                'last_name'=>$LastName,
                'Dateofbirth'=>$DateofBirth,
                'billing_first_name'=>$FirstName,
                'billing_last_name'=>$LastName,
                'billing_address_1'=>$Address,
                'billing_address_2'=>'',
                'billing_city'=>$City,
                'billing_state'=>$State,
                'billing_postcode'=>$Zip,
                'billing_phone'=>$Phone,
                'Email'=>$Email,
                'Currentschool'=>$Currentschool,
                'Major'=>$Major,
                'Occupations'=>$Occupations,
                'Sessionattend'=>$sessionattend,
                'Meetaclass'=>$meetaclass,
                'Postgraduatelevel'=>$Postgraduatelevel,
                'Participateprogram'=>$Participateprogram,
                'Expectingprogram'=>$expectingprogram,
                'Strengthsexpertise'=>$strengthsexpertise,
                'Weaknessesworking'=>$weaknessesworking,
                'Socialmediaaccounts'=>$socialmediaaccounts,
                'Abouttheprogram'=>$abouttheprogram,
                'Attachresume'=>$attachresume

            );

            $results=parent::update_user_data($userdataupdate,$userMetaData);

        }




        if(isset($_POST['save']) && $_POST['save']=='Save' && !empty($Email)){
            $results=parent::save_user_data($userdata,$userMetaData,$userRoll);

        }





        $output='<form method="post"> 
              <div style="background: #546666" >
                      <h2 style="color: #fff;padding: 12px 12px 9px 42px;">'.$add.' Leadership Training Registration</h2>
              </div>

        <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
              <div style="width:100%;margin-top: 30px; margin-left: 70px;">
              <h2>'.$results.'</h2>
                        
             <div class="smdn-form-group">
                 <label class="smdn-form-label">First Name</label>
                 <input  required type="text"  name="FirstName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$FirstName.'"> 
            </div>
          
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Last Name</label>
                 <input type="text"  name="LastName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$LastName.'">
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Mailing Address</label>
                 <input type="text" name="Address" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Address.'">
            </div>
            
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Date of Birth</label>
                 <input type="date" name="Dateofbirth" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$DateofBirth.'">
            </div>
                         
             <div class="smdn-form-group">
                 <label class="smdn-form-label">City</label>
                 <input type="text" name="City" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$City.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">State</label>
                 <select name="State" > 
                  '.$options.'
                   </select>
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Zip</label>
                 <input type="number"  name="Zip" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Zip.'">
            </div>
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Phone</label>
                 <input type="number"  name="Phone" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Phone.'">
            </div>
              
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Email Address</label>
                 <input required type="Email"  class="smdn-type" id="smdn-type" name="Email" placeholder=" " value="'.$Email.'"> 
            </div>
              
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Current school attending</label>
                 <input type="text" required name="Currentschool" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Currentschool.'">
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Major</label>
                 <input type="text"  name="Major" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Major.'">
            </div>
                
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Current Occupation</label>
                 <input type="text"  name="Occupations" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Occupations.'">
            </div>
            
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Which session would you like to attend?</label>
                 
                 <select name="Sessionattend" > 
                      <option value="June 7 -18, 2021">June 7 -18, 2021</option>
                      <option value="July 12 - 23, 2021">July 12 - 23, 2021</option>
                  </select>
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Are you applying to the program to meet a class requirement?</label>
                   <select name="Meetaclass" id="Meetaclass" >
                         <option value="No">No</option> 
                         <option value="Yes">Yes</option> 
                   </select>
            </div>
            
            <div class="smdn-form-group" id="postgraduatelevel">
                     <label class="smdn-form-label">Is it for college, graduate, post-graduate level, or other (please specify)?</label>
                     <textarea type="text"  name="postgraduatelevel" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Postgraduatelevel.'"></textarea>
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Why do you want to participate in the program?</label>
                 <textarea type="text"  name="Participateprogram" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$Participateprogram.'"> '.$Participateprogram.'</textarea>
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">What are you expecting to get out of the program?</label>
                 <textarea type="text"  name="Expectingprogram" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$expectingprogram.'">'.$expectingprogram.' </textarea>
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">What are some of your strengths or areas of expertise?</label>
                 <textarea type="text"  name="Strengthsexpertise" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$strengthsexpertise.'"> '.$strengthsexpertise.'</textarea>
            </div>
             
             <div class="smdn-form-group">
                 <label class="smdn-form-label">What are some of your weaknesses or areas you are working on?</label>
                 <textarea type="text"  name="Weaknessesworking" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$weaknessesworking.'">'.$weaknessesworking.'</textarea>
                 <span>If you do not have any social media accounts, please note so in the above field.</span>
            </div>
            
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Please provide links to your social media accounts.</label>
                 <textarea type="text"  name="Socialmediaaccounts" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$socialmediaaccounts.'">'.$socialmediaaccounts.'</textarea>
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">How did you hear about the program?</label>
                 <textarea type="text"  name="Abouttheprogram" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$abouttheprogram.'">'.$abouttheprogram.'</textarea>
            </div>
            
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Please attach your current resume.</label>
                 <input type="file"  name="Attachresume" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$attachresume.'">
            </div>
            
       
             <div class="smdn-form-group">
                 <label class="smdn-form-label"></label>
                 <input type="submit" name="save" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$save.'">
            </div>
        </div>

</div></form>';

        $output .="
      <script>
      jQuery(function () {
          
         jQuery('#postgraduatelevel').hide();
        
         jQuery('#Meetaclass').change(function () {
         if (jQuery(this).val() == 'Yes') {
             jQuery('#postgraduatelevel').show();
         } else {
             jQuery('#postgraduatelevel').hide();
                     }
                 });
         });

     </script>";

        return $output;

    }

}

new InternAddView();