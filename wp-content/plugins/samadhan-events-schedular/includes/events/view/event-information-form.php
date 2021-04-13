<?php
namespace Samadhan;

class EventInformation extends UserCRUD {

    public function __construct()
    {
        add_shortcode('smdn_event_information_form', 'Samadhan\EventInformation::get_event_information_form');
    }


    public function get_event_information_form(){


        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }

        $formView ='<form id="EventsInformation" method="" name="smdnEvents">
       <div class="smdn-form-group" >
          <h2 >{{controls.message}}</h2>
       </div>
       <h2>Event Information</h2>
       <div  v-if="controls.loader" class="loader"></div>
       <div class="smdn-form-group" >
            <label class="smdn-form-label">Name of Event *</label>
            <input type="text" v-model="eventsInfo.name" class="smdn-evne" id="smdn-evnet"  placeholder="" value="">
             <span style="color: red">{{notification.name}}</span>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Type of Event *</label>
            <select type="text" v-model="eventsInfo.eventType" class="smdn-type" id="smdn-type"  >
                 <option disabled value="">Select</option>
                <option >Church</option>
                <option >Capitol Tour</option>
                <option >Civic Group</option>
                <option >Military</option>
                <option >Legislative</option>
                <option >School</option>
                <option >University</option>
                <option >Other</option>
            </select>
             <span style="color: red">{{notification.eventType}}</span>
        
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Event Setting/Location *</label>
            <input type="text" v-model="eventsInfo.setting" class="smdn-setting" id="smdn-setting"  placeholder=" " value="">
            <span style="color: red">{{notification.setting}}</span> 
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address</label>
            <input type="text"  v-model="eventsInfo.address" class="smdn-address" id="smdn-address"  placeholder=" " value="">
            <span style="color: red">{{notification.address}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">City</label>
            <input type="text"  v-model="eventsInfo.city" class="smdn-address" id="smdn-address"  placeholder=" " value="">
      
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">State</label>
                <select type="text" v-model="eventsInfo.state" class="smdn-type" id="smdn-type"  >
                    <option disabled value="">Select</option>
                    <option v-for="state in getStatesValues"  >{{ state }} </option>
                </select>   
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Zip</label>
            <input type="text"  v-model="eventsInfo.zip" class="smdn-address" id="smdn-address"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Closest Airport to Event *</label>
            <input type="text"  v-model="eventsInfo.closestAirport" class="smdn-address" id="smdn-address"  placeholder=" " value="">
            <span style="color: red">{{notification.closestAirport}}</span>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Travel Time to/from Airport (if applicable)</label>
            <input type="text"  v-model="eventsInfo.travelToFromAirport" class="smdn-speaker" id="smdn-speaker"  placeholder=" " value=""> 
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Event Start Date /</label>
            <input type="date" v-model="eventsInfo.eventStartDate" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
             <span style="color: red">{{notification.eventStartDate}}</span>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Event Start Time *</label>
            <input type="text" v-model="eventsInfo.eventStartTime" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
            <span style="color: red">{{notification.eventStartTime}}</span>
       </div>
         <div class="smdn-form-group">
            <label class="smdn-form-label">Event End Date *</label>
           <input type="date" v-model="eventsInfo.eventEndDate" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
           <span style="color: red">{{notification.eventEndDate}}</span>
       </div>
       <div class="smdn-form-group"> 
            <label class="smdn-form-label">Event End Time *</label>
            <input type="text" v-model="eventsInfo.eventEndTime" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
            <span style="color: red">{{notification.eventEndTime}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Expected Attendance *</label>
            <input type="text" v-model="eventsInfo.ExpectedAttendance" class="smdn-attendence" id="smdn-attendence"  placeholder=" " value="">
          <span style="color: red">{{notification.ExpectedAttendance}}</span>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Description of Audience *</label>
            <textarea type="text" v-model="eventsInfo.descriptionAudience" class="smdn-organization" id="smdn-organization"  placeholder=" " value=""></textarea>
            <span style="color: red">{{notification.descriptionAudience}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Speaker Preference *</label>
                <select type="text" v-model="eventsInfo.speakerPreference" class="smdn-type" id="smdn-type"  >
                     <option disabled value="">Select</option>
                    <option >David Barton</option>
                    <option >Tim Barton</option>
                    <option >Rene Diaz</option>
                    <option >Rick Green</option>
                    <option >Matt Krause</option>
                    <option >David Pate</option>
                </select>   
                     <span style="color: red">{{notification.speakerPreference}}</span>
                 </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Alternate Speaker *</label>
                 <select type="text" v-model="eventsInfo.alternateSpeaker" class="smdn-type" id="smdn-type"  >
                            <option disabled value="">Select</option>
                            <option >No</option>
                            <option >Yes</option>
                     </select>   
                <span style="color: red">{{notification.alternateSpeaker}}</span>
                 </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Speakers Involvement *</label>
            <textarea type="text"  v-model="eventsInfo.speakersInvolvement" class="smdn-deadline" id="smdn-deadline"  placeholder=" " value=""></textarea>
            <span style="color: red">{{notification.speakersInvolvement}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Other Participants *</label>
            <textarea type="text" v-model="eventsInfo.otherParticipants" class="smdn-airport" id="smdn-airport"  placeholder=" " value=""></textarea>
              <span style="color: red">{{notification.otherParticipants}}</span>
       </div>
    
       <div class="smdn-form-group">
            <label class="smdn-form-label">Deadline *</label>
            <input type="date" v-model="eventsInfo.eventDeadline" class="smdn-audience" id="smdn-audience"  placeholder=" " value="">
            <span style="color: red">{{notification.eventDeadline}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label" > How did you hear about us? *</label>
            <input type="text" v-model="eventsInfo.hearAboutUs" class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
            <span style="color: red">{{notification.hearAboutUs}}</span>
       </div>
         <div class="smdn-form-group">
            <label class="smdn-form-label">Comments</label>
            <textarea type="text" v-model="eventsInfo.eventComments" class="smdn-participants" id="smdn-participants"  placeholder=" " value=""></textarea>
       </div>';
            $formView .=self::get_form_terms_and_conditions();
            $formView .='<div class="smdn-form-group">
            <label class="smdn-form-label"></label>
            <input type="button" id="eventButton" class="smdn-btn smdn-btn-info" :disabled="controls.buttonDisable" id="" v-on:click="eventInfoSubmitButton" placeholder=" " value="Save">
    
         </div>
      </form>';

        return $formView;
    }



    public static function get_form_terms_and_conditions(){

        $outPut=' <div class="smdn-form-group" >
                <label class="">Terms of Agreement *</label></br>
                <i>(Approval of each term listed below is required.)</i></br></br>
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox" v-bind="conditions.terms1" name="" @click="checkedStatus(1)" id=""> 1. WallBuilder Presentations agrees to provide a speaker for the indicated date, 
                 presentation time, and location. No additional appearances or activities shall be planned by the Sponsor nor expected of the Speaker unless approved by the WallBuilders Scheduling Office prior to the event date.

           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms2" name=""  @click="checkedStatus(2)"  id=""> 2. Event sponsor agrees to pay the travel expense by the indicated due date. These expenses are billed at a flat rate, and include all applicable airfare, lodging, transportation, food, tolls/tips/misc. A proof of final payment will be mailed from the WallBuilders office. WallBuilder Presentations reserves the right to cancel participation if payment is not received by the requested date.
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms3" name="" id="" @click="checkedStatus(3)" > 3. The Sponsoring Group agrees to provide an honorarium or love offering in addition to the travel expense.
          
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms4" name="" id="" @click="checkedStatus(4)" > 4. WallBuilder Presentations will make all travel arrangements.
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms5" name="" id="" @click="checkedStatus(5)" > 5. The speakers address may not be audio or video taped or broadcast unless previous permission has been secured from WallBuilder Presentations.
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms6" name="" id="" @click="checkedStatus(6)"> 6. One week prior to the event we will send out an e-blast to WallBuilders followers that live within a 1 hour radius of the event venue promoting the event. If the event is closed and you do not wish to promote the event to our followers please let us know in advance.
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms7" name="" id="" @click="checkedStatus(7)"> 7. In extreme circumstances, WallBuilder Presentations reserves the right to cancel this appearance due to a speakers illness or an unforeseen emergency or overriding obligation or professional responsibility and will not be responsible for expenses or losses incurred by the Event Sponsor. However, all reasonable efforts will be made to avoid any cancellations.
          
           </div>
           <div class="smdn-form-group" >
                <label class=""></label>
                <span style="margin-left: 10px"> <input type="checkbox"  v-bind="conditions.terms8"  name="" id="" @click="checkedStatus(8)">  8. All payments are to be made to WallBuilder Presentations and sent by mail. Do not give the payment to the speaker. The W-9 Form will accompany this contract.
           </div>
     ';
        return $outPut;
    }





}

new EventInformation();
