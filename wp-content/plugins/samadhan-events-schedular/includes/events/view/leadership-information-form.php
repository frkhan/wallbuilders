<?php
namespace Samadhan;

class LeadershipInformation extends UserCRUD {

    public function __construct()
    {
        add_shortcode('smdn_leadership_information_form','Samadhan\LeadershipInformation::get_form_leadership_information');
    }


    public function get_form_leadership_information(){

        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }

        $formView ='<form id="LeaderInformation" method="post" name="">

       <div class="smdn-form-group" >
          <h2 >{{controls.message}}</h2>
       </div>
       <div class="smdn-form-group" >
          <h2>Leadership Information</h2>
       </div>
       <div v-if="controls.loader" class="loader"></div>
       <div class="smdn-form-group" >
            <label class="">Title</label>
            <input type="text" v-model="Leaders.Title" class="smdn-form-control" id="smdn-evnet"  placeholder="" value="">
            
       </div>
    
       <div class="smdn-form-group" >
            <label class="">First Name *</label>
            <input type="text" required  v-model="Leaders.FirstName" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value="">
            <span style="color: red">{{notification.FirstName}}</span>
       </div>
       <div class="smdn-form-group" >
            <label class="">Last Name *</label>
             <input type="text" required v-model="Leaders.LastName" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value=""> <span style="margin-left: 10px"> <input type="checkbox" name="" id=""> Pastor
             <span style="color: red">{{notification.LastName}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Mailing Address *</label>
            <input type="text" required v-model="Leaders.MailingAddress" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
            <span style="color: red">{{notification.MailingAddress}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">City *</label>
            <input type="text" required v-model="Leaders.City" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
            <span style="color: red">{{notification.City}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">State *</label>
              <select type="text" v-model="Leaders.State" class="smdn-type" id="smdn-type"  >
                    <option disabled value="">Select</option>
                    <option v-for="state in getStatesValues"  >{{ state }} </option>
                </select> 
            
            <span style="color: red">{{notification.State}}</span>
        </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Zip *</label>
            <input type="text" required v-model="Leaders.Zip" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
            <span style="color: red">{{notification.Zip}}</span>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Phone </label>
            <input type="text" required v-model="Leaders.Phone" class="smdn-form-control" id="smdn-form-control"  placeholder=" " value="">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Cell Phone </label>
            <input type="text" required v-model="Leaders.CellPhone" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value="">
       </div>
    
       <div class="smdn-form-group">
            <label class="smdn-form-label">Email Address *</label>
            <input type="text" required v-model="Leaders.EmailAddress" class="smdn-form-control" id="smdn-speaker"  placeholder=" " value="">
            <span style="color: red">{{notification.EmailAddress}}</span>
       </div>
    
         <div class="smdn-form-group">
            <label class="smdn-form-label"></label>
            <input type="button" class="smdn-btn smdn-btn-info" id="" v-on:click="leadersSubmitButton" placeholder=" " value="Save">
         </div>
      </form>';
        echo $formView;
    }


}

new LeadershipInformation();