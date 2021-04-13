<?php

namespace Samadhan;

class EventForm extends UserCRUD {

    public function __construct()
    {
        add_shortcode('smdn_events_form', 'Samadhan\EventForm::get_form_views');
    }


    public function get_form_views(){


        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }

        $formView ='<form id="smdnEvents" >
       <h2>Schedule Request Details</h2>
       <div class="smdn-form-group" >
    
            <label class="smdn-form-label">Event</label>
            <input type="text" v-model="events.event" class="smdn-evne" id="smdn-evnet"  placeholder="" value="">
          
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Type</label>
            <input type="text" v-model="events.type" class="smdn-type" id="smdn-type"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Speaker</label>
            <input type="text"  v-model="events.speaker" class="smdn-speaker" id="smdn-speaker"  placeholder=" " value=""> <span style="margin-left: 10px">  Alt Speaker  <input type="checkbox" name="" id="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Start Date Time</label>
            <input type="date" v-model="events.startDate" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
       </div>
         <div class="smdn-form-group">
            <label class="smdn-form-label">End Date Time</label>
            <input type="date" v-model="events.endDate" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Organization</label>
            <input type="text" v-model="events.organization" class="smdn-organization" id="smdn-organization"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Contact</label>
            <input type="text" v-model="events.contact" class="smdn-contact" id="smdn-contact"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Contact date</label>
            <input type="date"   v-model="events.contactDate" class="smdn-contact-date" id="smdn-contact-date"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Deadline</label>
            <input type="date"  v-model="events.deadline" class="smdn-deadline" id="smdn-deadline"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Setting/Location</label>
            <input type="text" v-model="events.setting" class="smdn-setting" id="smdn-setting"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address</label>
            <input type="text"  v-model="events.address" class="smdn-address" id="smdn-address"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Airport</label>
            <input type="text" v-model="events.airport" class="smdn-airport" id="smdn-airport"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Attendence</label>
            <input type="text" v-model="events.attendence" class="smdn-attendence" id="smdn-attendence"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Audience</label>
            <input type="text" class="smdn-audience" id="smdn-audience"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label" > Involvement</label>
            <input type="text" v-model="events.involvement" class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
       </div>
         <div class="smdn-form-group">
            <label class="smdn-form-label">Other Participants</label>
            <input type="text" v-model="events.participants" class="smdn-participants" id="smdn-participants"  placeholder=" " value="">
       </div>
    
        <div class="smdn-form-group">
            <label class="smdn-form-label">Declined</label>
            <input type="text" v-model="events.declined" class="smdn-declined" id="smdn-declined"  placeholder=" " value="">
       </div>
    
         <div class="smdn-form-group">
            <label class="smdn-form-label"></label>
            <input type="button" class="smdn-btn smdn-btn-info" id="" v-on:click="eventSubmitButton" placeholder=" " value="Save">
            <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Event">
            <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Save">
            <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Save">
       </div>
      </form>';
        return $formView;
    }


}

new EventForm();
