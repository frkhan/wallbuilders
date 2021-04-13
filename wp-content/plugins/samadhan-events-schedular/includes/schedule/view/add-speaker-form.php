<?php
namespace Samadhan;
use Samadhan\UserCRUD;
class SpeakerAdd {

    public function __construct()
    {
        add_shortcode('smdn_add_speakers_form','Samadhan\SpeakerAdd::add_manage_speakers_form');
    }


    public function add_manage_speakers_form(){

        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        $speakerId=$_GET['speaker_id'];
        if(!empty($speakerId)){
            $buttonName="Update";
            $titleName="Update Speaker";
            $emailField='readonly';
        }else{
            $buttonName="Save";
            $emailField='';
            $titleName="Add Speaker";
        }

        $formView ='<div id="addManageSpeakersForm">  
        <div class="smdn-form-group">
             <input type="hidden" id="speakerId" value="'.$speakerId.'">
             <input type="hidden"v-model="addSpeaker.updateSpeakerId" value="">
          
             <h2 >{{controls.message}}</h2>
            
        </div>
        <div class="smdn-form-group">
             <h2 >'. $titleName .'</h2>
            
       </div>
       <div  v-if="controls.loader" class="loader"></div>
       <hr/>
     
        <div class="smdn-form-group">
            <label class="smdn-form-label">First Name</label>
            <input v-model="addSpeaker.firstName" type="text"  class="smdn-type" id="smdn-type"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Last Name</label>
            <input v-model="addSpeaker.lastName" type="text"  class="smdn-type" id="smdn-type"  placeholder=" " value="">
       </div>
      
      <div class="smdn-form-group">
            <label class="smdn-form-label">Nickname</label>
            <input v-model="addSpeaker.nickName" type="text"  class="smdn-type" id="smdn-type"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Email</label>
            <input  v-model="addSpeaker.email" type="text"  class="smdn-type" id="smdn-type"  placeholder=" " value="" '.$emailField.'>
       </div>
      
             <div class="smdn-form-group" style="    padding-left: 20px;">
                      
                        <a href="'.home_url('/all_speaker_list').'"> <input type="button" style="margin-right:10px; " class="smdn-type" id="smdn-type"  placeholder=" " value="Previous"></a>
                        <input type="button"  v-on:click="addSpeakerSubmitButton" class="smdn-type" id="smdn-type"  placeholder=" " value="'.$buttonName.'">
                   </div></div> ';

        return $formView;
    }

}

new SpeakerAdd();
