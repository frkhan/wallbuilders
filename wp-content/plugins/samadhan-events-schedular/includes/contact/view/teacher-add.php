<?php

namespace Samadhan;



class TeacherAddView extends UserCRUD{

    public function __construct()
    {
        add_shortcode('smdn_teacher_form','Samadhan\TeacherAddView::get_contact_form_teacher_registration');
    }

    public static function get_contact_form_teacher_registration($atts){

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

            $MailingAddress=get_user_meta($user_id,'billing_address_1',true);
            $City=get_user_meta($user_id,'billing_city',true);
            $State=get_user_meta($user_id,'billing_state',true);
            $Zip=get_user_meta($user_id,'billing_postcode',true);
            $Phone=get_user_meta($user_id,'billing_phone',true);
            $DateOfBirth=get_user_meta($user_id,'DateOfBirth',true);
            $SchoolName=get_user_meta($user_id,'SchoolName',true);
            $GradeLevelTeach=get_user_meta($user_id,'GradeLevelTeach',true);
            $Subjects=get_user_meta($user_id,'Subjects',true);
            $Duration=get_user_meta($user_id,'Duration',true);
            $GradeLevelTaught=get_user_meta($user_id,'GradeLevelTaught',true);
            $SpecificTopics=get_user_meta($user_id,'SpecificTopics',true);
            $Sessions=get_user_meta($user_id,'Sessions',true);



        }
        else {
            $add = 'Add';
            $save = 'Save';


            $userRoll = $attribute['role'];

            $Email = $_POST['Email'];
            $FirstName = $_POST['FirstName'];
            $MiddleName = $_POST['MiddleName'];
            $LastName = $_POST['LastName'];
            $City = $_POST['City'];
            $State = $_POST['State'];
            $Zip = $_POST['Zip'];
            $Phone = $_POST['Phone'];
            $MailingAddress = $_POST['MailingAddress'];
            $DateOfBirth = $_POST['DateOfBirth'];
            $SchoolName = $_POST['SchoolName'];
            $GradeLevelTeach = $_POST['GradeLevelTeach'];
            $Subjects = $_POST['Subjects'];
            $Duration = $_POST['Duration'];
            $GradeLevelTaught = $_POST['GradeLevelTaught'];
            $SpecificTopics = $_POST['SpecificTopics'];
            $Sessions = $_POST['Sessions'];

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

            'first_name'=>$FirstName,
            'last_name'=>$LastName,

            'billing_first_name'=>$FirstName,
            'billing_last_name'=>$LastName,

            'billing_city'=>$City,
            'billing_state'=>$State,
            'billing_postcode'=>$Zip,
            'billing_phone'=>$Phone,

            'billing_address_1'=>$MailingAddress,
            'billing_address_2'=>'',


            'DateOfBirth'=>$DateOfBirth,
            'SchoolName'=>$SchoolName,
            'GradeLevelTeach'=>$GradeLevelTeach,
            'Subjects'=>$Subjects,
            'Duration'=>$Duration,
            'GradeLevelTaught'=>$GradeLevelTaught,
            'SpecificTopics'=>$SpecificTopics,
            'Sessions'=>$Sessions
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


        $Sessions=array(
            'July 5-7,2021',
            'July 8-10,2021'
        );

        $options1="<option value='-1'>Select</option>";
        foreach ($Sessions as $session){

            $options1 .="<option value='$session'>$session</option>";
        }



        if(isset($_POST['save']) && $_POST['save']=='Update' && !empty($Email)){
            $userdataupdate= array(
                'ID' => $user_id,
                'user_email' => esc_attr($Email),
            );

            $userRoll = $attribute['role'];

            $Email = $_POST['Email'];
            $FirstName = $_POST['FirstName'];
            $MiddleName = $_POST['MiddleName'];
            $LastName = $_POST['LastName'];
            $City = $_POST['City'];
            $State = $_POST['State'];
            $Zip = $_POST['Zip'];
            $Phone = $_POST['Phone'];
            $MailingAddress = $_POST['MailingAddress'];
            $DateOfBirth = $_POST['DateOfBirth'];
            $SchoolName = $_POST['SchoolName'];
            $GradeLevelTeach = $_POST['GradeLevelTeach'];
            $Subjects = $_POST['Subjects'];
            $Duration = $_POST['Duration'];
            $GradeLevelTaught = $_POST['GradeLevelTaught'];
            $SpecificTopics = $_POST['SpecificTopics'];
            $Sessions = $_POST['Sessions'];

            $userMetaData=array(
                'first_name' => esc_attr($FirstName),
                'last_name' => esc_attr($LastName),
                'billing_first_name'=>$FirstName,
                'billing_last_name'=>$LastName,
                'billing_city'=>$City,
                'billing_state'=>$State,
                'billing_postcode'=>$Zip,
                'billing_phone'=>$Phone,

                'billing_address_1'=>$MailingAddress,
                'billing_address_2'=>'',

                'DateOfBirth'=>$DateOfBirth,
                'SchoolName'=>$SchoolName,
                'GradeLevelTeach'=>$GradeLevelTeach,
                'Subjects'=>$Subjects,
                'Duration'=>$Duration,
                'GradeLevelTaught'=>$GradeLevelTaught,
                'SpecificTopics'=>$SpecificTopics,
                'Sessions'=>$Sessions
            );


            $results=parent::update_user_data($userdataupdate,$userMetaData);

        }


        if(isset($_POST['save']) && $_POST['save']=='Save' && !empty($Email)){
            $results=parent::save_user_data($userdata,$userMetaData,$userRoll);

        }


        $outPut='<form method="post"> <div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;">'.$add.' Teacher Conference Registration</h2></div>

        <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
              <div style="width:100%;margin-top: 30px; margin-left: 70px;">
              <h2>'.$results.'</h2>
              <div class="smdn-form-group">
                 <label class="smdn-form-label">First Name</label>
                 <input  required type="text"  name="FirstName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$FirstName.'"> 
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Last Name</label>
                 <input required type="text"  name="LastName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$LastName.'">
            </div>
              
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Mailing Address</label>
                 <input required type="text" name="MailingAddress" class="smdn-type" id="smdn-type"  placeholder=" " value="'.$MailingAddress.'"> 
            </div>
           
             <div class="smdn-form-group">
                 <label class="smdn-form-label">Date of Birth</label>
                 <input required type="date" name="DateOfBirth" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$DateOfBirth.'">
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
                 <label class="smdn-form-label">What is the name of the school where you teach?</label>
                 <input required type="text"  class="smdn-type" id="smdn-type" name="SchoolName" placeholder=" " value="'.$SchoolName.'"> 
            </div>
            
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">What grade levels do you teach?</label>
                 <input required type="text"  class="smdn-type" id="smdn-type" name="GradeLevelTeach" placeholder=" " value="'.$GradeLevelTeach.'"> 
            </div>
            
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">What subject(s) do you teach?</label>
                 <input required type="text"  class="smdn-type" id="smdn-type" name="Subjects" placeholder=" " value="'.$Subjects.'"> 
            </div>
            
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">How long have you been a teacher?</label>
                 <input required type="text"  class="smdn-type" id="smdn-type" name="Duration" placeholder=" " value="'.$Duration.'"> 
            </div>
            
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">What grade levels have you taught?</label>
                 <input required type="text"  class="smdn-type" id="smdn-type" name="GradeLevelTaught" placeholder=" " value="'.$GradeLevelTaught.'"> 
            </div>
            
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">In addition to the topics identified, are there any other specific topics you’d like addressed at the conference?</label>
                 <input required type="text"  class="smdn-type" id="smdn-type" name="SpecificTopics" placeholder=" " value="'.$SpecificTopics.'"> 
            </div>
            
            <div class="smdn-form-group">
                 <label class="smdn-form-label">Which session would you like to attend?</label>
                 <select name="Sessions" > 
                  '.$options1.'
                   </select>
            </div>
            

             <div class="smdn-form-group">
                 <label class="smdn-form-label"></label>
                 <input type="submit"  name="save" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value='.$save.'>
            </div>
        </div>

</div></form>';


        return $outPut;

    }

}

new TeacherAddView();