<?php
namespace Samadhan;
use Samadhan\UserCRUD;

class SchedulingRequest {

    public function __construct()
    {
        add_shortcode('smdn_add_scheduling_request_form','Samadhan\SchedulingRequest::add_scheduling_request_form');
    }

    public function add_scheduling_request_form(){

        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        $event_id=$_GET['event_id'];
        if(!empty($event_id) && isset($event_id)){
            $buttonName="Update";
            $titleName="Update Scheduling Request";
        }else{
            $buttonName="Save";
            $titleName="Add Scheduling Request";
        }



        $outPut='<div id="schedulingRequestForm" ><div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;"> '.$titleName.'</h2></div>
               
                <div  v-if="controls.loader" class="loader"></div>
           <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
             <input id="eventId" type="hidden" value="'.$event_id.'" >
             <input id="eventId"  v-model="setUpdateEventId" type="hidden" value="" >
        
            
            <!---first column --------->
                 <div style="width: 46%;margin-top: 30px; margin-left: 40px;">
                 
                  <div class="smdn-form-group">
                     <h2 >{{controls.message}}</h2>
                    
                </div> 
                <div class="smdn-form-group">
                    <label class=""><h4>Contact</h4></label>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Contact Date</label>
                    <input type="date" v-model="contacts.ContactDate" class="smdn-type" id="smdn-type"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">First Request</label>
                    <input type="checkbox" v-model="contacts.FirstRequest" class="smdn-type" id="smdn-type"  placeholder=" " value="yes">
               </div>
               
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Organization</label>
                    <input type="text" v-model="contacts.Organization" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Title</label>
                    <input name="" v-model="contacts.Title" type="text" id="" style="width:20%;">  <span style="padding: 0 20px;"> Pastor</span> <input type="checkbox"  v-model="contacts.Pastor"  class="smdn-type" id="smdn-type"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">First Name</label>
                    <input type="text"  v-model="contacts.FirstName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Last Name</label>
                    <input type="text"  v-model="contacts.LastName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Address1</label>
                    <textarea type="text"  v-model="contacts.Address1" style="width: 50%;" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value=""></textarea>
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Address2</label>
                    <textarea type="text"  v-model="contacts.Address2" style="width: 50%;" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value=""></textarea>
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text"  v-model="contacts.City" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">State</label>
                      <select type="text" v-model="contacts.State" class="smdn-State" id="smdn-State"  >
                        <option disabled value="">Select</option>
                        <option :value="index" v-for="(state ,index) in getStatesValues"   >{{ state }} </option>
                    </select> 
                 
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Zip</label>
                    <input type="text" v-model="contacts.Zip"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 1</label>
                    <input name="" type="text" v-model="contacts.Phone1" id="" placeholder="999-999-9999" style="width:40%;"> <span style="0 20px;"> EXt. </span><input name="" v-model="contacts.phoneExt1" type="text" id="" style="width:20%;">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 2</label>
                    <input name="" type="text" v-model="contacts.Phone2" id="" placeholder="999-999-9999" style="width:40%;"> <span style="0 20px;"> EXt. </span><input name="" v-model="contacts.phoneExt2"  type="text" id="" style="width:20%;">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Fax</label>
                    <input type="text"  v-model="contacts.Fax" class="smdn-start-date" id="smdn-start-date"  placeholder="999-999-9999" value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Email</label>
                    <input type="text"  v-model="contacts.Email" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
             </div><hr/>';
        $outPut .='  <div class="smdn-form-group">
                    <label class=""><h4>LEADERSHIP INFORMATION (if different from above)</h4></label>
               </div><hr/>
                           <div class="smdn-form-group">
                    <label class="smdn-form-label">Title</label>
                    <input name="" v-model="leaders.Title" type="text" id="" style="width:20%;">  <span style="padding: 0 20px;"> Pastor</span> <input type="checkbox" v-model="leaders.Pastor" class="smdn-type" id="smdn-type"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">First Name</label>
                    <input type="text" v-model="leaders.FirstName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Last Name</label>
                    <input type="text"  v-model="leaders.LastName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Address1</label>
                    <textarea style="width: 50%;" v-model="leaders.Address1" type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value=""></textarea>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Address2</label>
                    <textarea style="width: 50%;" v-model="leaders.Address2" type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value=""></textarea>
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text"  v-model="leaders.City" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>';
        $outPut .='  <div class="smdn-form-group">
                    <label class="smdn-form-label">State</label>
                    <select type="text" v-model="leaders.State" class="smdn-State" id="smdn-State"  >
                        <option disabled value="">Select</option>
                        <option :value="index" v-for="(state ,index) in getStatesValues"   >{{ state }} </option>
                    </select> 
                    
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Zip</label>
                    <input v-model="leaders.Zip" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 1</label>
                    <input v-model="leaders.Phone1" name="" type="text" id="" placeholder="999-999-9999" > 
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 2</label>
                    <input v-model="leaders.Phone2" name="" type="text" id="" placeholder="999-999-9999" > 
               </div>
             
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Email</label>
                    <input type="text" v-model="leaders.Email"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
               
           </div>';



        $outPut .='  <div style="width: 46%; float: right; margin-top:30px;" >   
             <!---second column --------->  
         <div class="smdn-form-group">
            <label class=""><h4>Event Information</h4></label>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Name</label>
            <input type="text"  v-model="eventsInfo.Name"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
      <div class="smdn-form-group">
        <label class="smdn-form-label">Red Flag <i class="form-icon fugue-3 flag-green">&nbsp;&nbsp;</i></label>
            <select v-model="eventsInfo.RedFlag"  name="" id="" style="width:40%;">
                <option selected="selected" value="0">Select</option>
                <option value="1">Red Flag Alert</option>
            </select> 
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Type</label>
            <select v-model="eventsInfo.Type"  name="" id="" style="width:60%;">
                    <option selected="selected" value="Church">Church</option>
                    <option value="University">University</option>
                    <option value="School">School</option>
                    <option value="Legislative">Legislative</option>
                    <option value="Capitol Tour">Capitol Tour</option>
                    <option value="Civic Group">Civic Group</option>
                    <option value="Military">Military</option>
                    <option value="Other">Other</option>
                
                </select>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Setting/Location</label>
            <input type="text" v-model="eventsInfo.Setting" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address1</label>
            <textarea style="width: 50%;" v-model="eventsInfo.Address1"  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""></textarea>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address2</label>
            <textarea style="width: 50%;" v-model="eventsInfo.Address2"  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""></textarea>
       </div>
      
            <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text"  v-model="eventsInfo.City" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">State</label>
                      <select type="text" v-model="eventsInfo.State" class="smdn-State" id="smdn-State"  >
                        <option disabled value="">Select</option>
                        <option :value="index" v-for="(state ,index) in getStatesValues"   >{{ state }} </option>
                    </select> 
             
               </div>';
        $outPut .=' <div class="smdn-form-group">
                    <label class="smdn-form-label">Zip</label>
                    <input type="text" v-model="eventsInfo.Zip"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
              <div class="smdn-form-group">
                    <label class="smdn-form-label">Airport</label>
                    <input type="text" v-model="eventsInfo.Airport"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Time To Airport</label>
                    <input type="text" v-model="eventsInfo.TimeToAirport" style="margin-right: 20px;" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> Minutes
               </div>
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Start DateTime</label>
                        <input v-model="eventsInfo.StartDate" name="" type="date" id="" style="width:30%; margin-right: 30px;" class="hasDatepicker">
                        <span class="form-icon fugue-2 calendar-day" style="; margin-left:-18px;"></span>
                        <input v-model="eventsInfo.StartHour" name="" type="text" value="12" id="" placeholder="12" style="width:10%;"> : <input name="" v-model="eventsInfo.StartMinutes" type="text" value="00" id="" placeholder="00" style="width:10%;">
                        <select v-model="eventsInfo.StartTimeExt" name="" id="" style="width:10%;">
                            <option selected="selected" value="AM">AM</option>
                            <option value="PM">PM</option>
                        
                        </select>
                  
               </div>
                     <div class="smdn-form-group">
                    <label class="smdn-form-label">End DateTime</label>
                        <input v-model="eventsInfo.EndDate" name="" type="date" id="" style="width:30%; margin-right: 30px;" class="hasDatepicker">
                        <span class="" style=" margin-left:-18px;"></span>
                        <input v-model="eventsInfo.EndHour" name="" type="text" value="12" id="" placeholder="12" style="width:10%;"> : <input name="" v-model="eventsInfo.EndMinutes" type="text" value="00" id="" placeholder="00" style="width:10%;">
                        <select v-model="eventsInfo.EndTimeExt" name="" id="" style="width:10%;">
                            <option selected="selected" value="AM">AM</option>
                            <option value="PM">PM</option>
                        
                        </select>
                  
               </div>
               
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Attendance</label>
                    <input v-model="eventsInfo.AttendanceTime" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>  
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Audience</label>
                    <input v-model="eventsInfo.AudienceTime" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> 
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Speaker</label>
                    <select type="text" v-model="eventsInfo.Speaker" class="smdn-speaker" id="smdn-speaker"  >
                        <option disabled value="">Select</option>
                        <option :value="state.Id" v-for="state in getSpeakerValues"   >{{ state.FullName }} </option>
                    </select>  
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Alt Speaker</label>
                    <select v-model="eventsInfo.AltSpeaker"  name="" id="" style="width:20%;">
                        <option selected="selected" value="1">Yes</option>
                        <option value="0">No</option>
                    
                    </select>
               </div>
               
             <div class="smdn-form-group">
                    <label class="smdn-form-label">Involvement</label>
                    <textarea style="width: 50%;" v-model="eventsInfo.Involvement" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> </textarea>
               </div> 
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Other Participants</label>
                    <textarea style="width: 50%;" v-model="eventsInfo.OtherParticipants" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> </textarea>
               </div>  
                <div class="smdn-form-group">
                    <label class="smdn-form-label">How they know?</label>
                    <textarea style="width: 50%;" v-model="eventsInfo.howTheyKnow" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> </textarea>
               </div> 
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Notes</label>
                    <textarea style="width: 50%;" v-model="eventsInfo.Notes" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> </textarea>
               </div>
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Deadline</label>
                    <input v-model="eventsInfo.Deadline" type="date"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " > 
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Decline</label>
                    <input v-model="eventsInfo.Decline" type="checkbox"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""><span style="0 20px">Reason</span> 
                    <select v-model="eventsInfo.Reason" name="" id="" style="width:40%;">
                            <option selected="selected" value="0">Select</option>
                            <option value="1">Event Cancelled</option>
                            <option value="2">Duplicate Request</option>
                            <option value="3">All Speakers Booked</option>
                            <option value="4">Dead Request</option>
                            <option value="5">Other</option>
                        
                        </select>
               </div>  
               
               
         <div class="smdn-form-group">
            <input type="button"  class="smdn-end-date" id="smdn-end-date" v-on:click="saveScheduleRequistSubmitedButton"  placeholder=" " value="'.$buttonName.'">
       </div>
   </div>
   </div></div>';
        return $outPut;
    }

}

new SchedulingRequest();
