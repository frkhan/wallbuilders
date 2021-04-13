<?php

namespace Samadhan;

class ContactInformation extends UserCRUD {

    public function __construct()
    {
        add_shortcode('smdn_contact_information_form','samadhan\ContactInformation::get_form_contact_information');
    }


    public function get_form_contact_information(){

        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }


        $formView ='<form id="ContactInformation" method="post" name="smdnContact">
         <div class="smdn-form-group" >
          <h2 >{{controls.message}}</h2>
       </div>
       <div class="smdn-form-group" >
          <h2>Your Contact Information</h2>
          <p>Please note that your form will not process if required information is missing. Required information is marked with asterisks (*).</p>
       </div>
       <div  v-if="controls.loader" class="loader"></div>
       <div class="smdn-form-group" >
          <p>Is this your groups first time to request a WallBuilders Speaker? *</p>
          <select  v-model="Contacts.RequestSpeaker" class="smdn-form-control">
              <option disabled value="" >Select</option>
              <option  >Yes</option>
              <option  >No</option>
          </select>
           <span style="color: red">{{notification.RequestSpeaker}}</span>
       </div>
       <div class="smdn-form-group" >
            <label class="">Organization</label>
            <input type="text" v-model="Contacts.Organization" class="smdn-form-control" id="smdn-evnet"  placeholder="" value="">
       </div>
       <div class="smdn-form-group" >
            <label class="">Title</label>
            <input type="text" v-model="Contacts.Title" class="smdn-form-control" id="smdn-evnet"  placeholder="" value="">
       </div>
    
       <div class="smdn-form-group" >
            <label class="">First Name *</label>
            <input type="text" required  v-model="Contacts.FirstName" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value="">
           <span style="color: red">{{notification.FirstName}}</span>
       </div>
       <div class="smdn-form-group" >
            <label class="">Last Name *</label>
            <input type="text" required v-model="Contacts.LastName" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value=""> <span style="margin-left: 10px"> <input type="checkbox" v-model="Contacts.Pastor" name="" id=""> Pastor
            <span style="color: red">{{notification.LastName}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Mailing Address *</label>
            <input type="text" required v-model="Contacts.MailingAddress" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
            <span style="color: red">{{notification.MailingAddress}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">City *</label>
            <input type="text" required v-model="Contacts.City" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
            <span style="color: red">{{notification.City}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">State *</label>
                 <select type="text" v-model="Contacts.State" class="smdn-type" id="smdn-type"  >
                    <option disabled value="">Select</option>
                    <option v-for="state in getStatesValues"  >{{ state }} </option>
                </select> 
            <span style="color: red">{{notification.State}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Zip *</label>
            <input type="text" required v-model="Contacts.Zip" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
            <span style="color: red">{{notification.Zip}}</span>
       
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Phone *</label>
            <input type="text" required v-model="Contacts.Phone" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
           <span style="color: red">{{notification.Phone}}</span>
       
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Cell Phone *</label>
            <input type="text" required v-model="Contacts.CellPhone" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value=""> <span style="margin-left: 10px">  
           <span style="color: red">{{notification.CellPhone}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Fax</label>
            <input type="text"  v-model="Contacts.Fax" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value=""> <span style="margin-left: 10px">  
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Email Address *</label>
            <input type="text" required v-model="Contacts.EmailAddress" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value=""> <span style="margin-left: 10px">  
            <span style="color: red">{{notification.EmailAddress}}</span>
       </div>
    
         <div class="smdn-form-group">
            <label class="smdn-form-label"></label>
            <input type="button" class="smdn-btn smdn-btn-info" id="" v-on:click="contactSubmitButton" placeholder=" " value="Save">
         </div>
      </form>';
        return $formView;
    }


}

new ContactInformation();
