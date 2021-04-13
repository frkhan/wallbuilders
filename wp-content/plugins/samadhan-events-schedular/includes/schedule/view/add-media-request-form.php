<?php
namespace Samadhan;
use Samadhan\UserCRUD;

class MediaRequest extends SchedulerFunctions {

    public function __construct()
    {
        add_shortcode('smdn_add_media_request_form','Samadhan\MediaRequest::get_add_media_request_form');
    }

    public function get_add_media_request_form(){

        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }


        $media_request_id=$_GET['media_request_id'];
        if(!empty($media_request_id) && isset($media_request_id)){
            $buttonName="Update";
            $titleName="Update Media Request";
        }else{
            $buttonName="Save";
            $titleName="Add Media Request";
        }

        $formView ='<form id="SchedularMediaRequestForm" >
        <input id="media_request_id" type="hidden" value="'.$media_request_id.'" >
        <input id="media_request_id" type="hidden"  v-model="setMediaRequestId"value="" >
         <div class="smdn-form-group">
              <h2 >{{controls.message}}</h2>
         </div>
          <div class="smdn-form-group">
               <h2>'.$titleName.'</h2>
         </div>
         <div  v-if="controls.loader" class="loader"></div><hr/>
       <div class="smdn-form-group" >
            <label class="smdn-form-label">Media Type</label>
            <select id="" v-model="organization.mediaType" name="" style="width:40%;">
                <option value="Church">Church</option>
                <option value="Convention">Convention</option>
                <option value="Magazine">Magazine</option>
                <option value="Movie/Documentary">Movie/Documentary</option>
                <option value="Newspaper">Newspaper</option>
                <option value="Other">Other</option>
                <option value="Podcast">Podcast</option>
                <option selected="selected" value="Radio">Radio</option>
                <option value="Radio &amp; TV">Radio &amp; TV</option>
                <option value="SchoolProject">SchoolProject</option>
                <option value="Simulcast">Simulcast</option>
                <option value="TV">TV</option>
                <option value="Video">Video</option>
                <option value="Web Article/Blog">Web Article/Blog</option>
                <option value="Unknown">Unknown</option>
              </select>
              <span style="padding: 0 20px ;"> Active </span>
              <select  v-model="organization.active" name="" id="" style="width:10%;">
                    <option value="Yes">Yes</option>
                    <option selected="selected" value="No">No</option>
                </select>
          
       </div>
          <div class="smdn-form-group">
            <label class="smdn-form-label">Red Flag </label>
              <select  v-model="organization.redFlag" name="" id="" style="width:10%;">
                    <option value="0">Select</option>
                    <option selected="selected" value="1">Red Flag Alert</option>
                </select>
       </div>
       <hr/>
        <div class="smdn-form-group">
            <h2 class="smdn-form-label">SHOW OR ORGANIZATION</h2>
       </div><hr/>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Station/Org</label>
            <input type="text" v-model="organization.station" class="smdn-type" id="smdn-type"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Show Name</label>
            <input type="text" v-model="organization.name" class="smdn-type" id="smdn-type"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Show Type</label>
            <select v-model="organization.type" name="" id="" style="width:25%;">
                <option value="Christian">Christian</option>
                <option value="Secular">Secular</option>
                <option value="Jewish">Jewish</option>
                <option value="Conservative/Politic">Conservative/Politic</option>
                <option value="Both C &amp; S">Both C &amp; S</option>
                <option value="Undefined">Undefined</option>
             </select>
            <span style="padding: 0 20px ;"> Live Audience</span>
             <select v-model="organization.audiencec" name="" id="" style="width:10%;">
                <option value="Yes">Yes</option>
                <option selected="selected" value="No">No</option>
            </select>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Host Name</label>
            <input type="text" v-model="organization.host" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
       </div>
         <div class="smdn-form-group">
            <label class="smdn-form-label">Topic</label>
            <input type="text"  v-model="organization.topic" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div><hr/>
          <div class="smdn-form-group">
            <h2 class="smdn-form-label">INTERVIEW</h2>
       </div><hr/>
        <div class="smdn-form-group">
            <label class="smdn-form-label">DateTime</label>
            <input type="date"  v-model="inverview.date" class="smdn-organization" id="smdn-organization"  placeholder=" " value="">
            <input name="" type="text"  v-model="inverview.hour" value="12" id="" placeholder="12" style="width:5%;"> :
               <input name=""  v-model="inverview.minutes" type="text" value="00" id="" placeholder="00" style="width:5%;">
            <select  v-model="inverview.amPm" name="" id="" style="width:10%;">
                <option selected="selected" value="AM">AM</option>
                <option value="PM">PM</option>
            </select>
            <select v-model="inverview.extraTime" name="" id="" style="width:10%;">
                <option value="AKST">AKST</option>
                <option value="EST">EST</option>
                <option selected="selected" value="CST">CST</option>
                <option value="HAST">HAST</option>
                <option value="MST">MST</option>
                <option value="PST">PST</option>
            
            </select>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Length</label>
            <input type="text" v-model="inverview.length" class="smdn-contact" id="smdn-contact"  placeholder=" " value=""> 
         
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Type</label>
            <select v-model="inverview.type" name="" id="" style="width:25%;">
                <option selected="selected" value="Taped">Taped</option>
                <option value="Live">Live</option>
            
            </select>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Speaker</label>
              <select type="text" v-model="inverview.speaker" class="smdn-speaker" id="smdn-speaker"  >
                            <option disabled value="">Select</option>
                            <option :value="state.Id" v-for="state in getSpeakerValues"   >{{ state.FullName }} </option>
               </select> 
                  
       </div><hr/>';
        $formView .='<div class="smdn-form-group">
        <h2 class="smdn-form-label">CONTACT INFORMATION</h2>
       </div><hr/>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Name</label>
            <input v-model="contact.name" type="text"  class="smdn-setting" id="smdn-setting"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address</label>
            <input v-model="contact.address" type="text"  class="smdn-address" id="smdn-address"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">City, State</label>
            <input v-model="contact.city" type="text"  class="smdn-airport" id="smdn-airport"  placeholder=" " value="">
                  <select type="text" v-model="contact.state" class="smdn-State" id="smdn-State"  >
                            <option disabled value="">Select</option>
                            <option :value="index" v-for="(state ,index) in getStatesValues"   >{{ state }} </option>
                  </select> 
        </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Zip</label>
            <input v-model="contact.zip" type="text"  class="smdn-attendence" id="smdn-attendence"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Phone</label>
            <input v-model="contact.phone" type="text" class="smdn-audience" id="smdn-audience"  placeholder=" " value="">
            <span style="padding:0 20px;">Phone 2</span>
            <input v-model="contact.phone2"  name="" type="text" id="" style="width:35%;">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label" > Email</label>
            <input v-model="contact.email"  type="text"  class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
            <span style="padding:0 20px;">Phone 3</span>
            <input  v-model="contact.phone3"  name="" type="text" id="" style="width:35%;">
       </div><hr/>
         <div class="smdn-form-group">
            <h2 class="smdn-form-label">OTHER INFORMATION</h2>
       </div><hr/>
       
         <div class="smdn-form-group">
            <label class="smdn-form-label">Notes</label>
            <textarea  v-model="others.notes"  type="text"  class="smdn-participants" id="smdn-participants"  placeholder=" " value=""></textarea>
       </div>
    
        <div class="smdn-form-group">
            <label class="smdn-form-label">Date Created</label>
              <input readonly v-model="others.createDate"  name="" type="text" id="" style="width:35%;">
       </div>
    
         <div class="smdn-form-group">
          
            <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Previous">
            <input type="button" class="smdn-btn smdn-btn-info" id=""  v-on:click="MediaRequestSubmitButton" placeholder=" "  value="'.$buttonName.'">
            <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Delete">
       </div>
      </form>';
        return $formView;
    }


}

new MediaRequest();