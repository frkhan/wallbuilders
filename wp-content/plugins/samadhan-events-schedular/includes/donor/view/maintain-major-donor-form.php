<?php

namespace Samadhan;
use Samadhan\UserCRUD;

class MaintainMajorDonorForm {

    public function __construct()
    {
        add_shortcode('maintain_major_donor_form','Samadhan\MaintainMajorDonorForm::get_maintain_major_donor_form');
    }


    public function get_maintain_major_donor_form(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }


        $formView ='<form id="maintainManageDonorForm" >
         <div class="smdn-form-group">
              <h2 >{{controls.message}}</h2>
         </div>
          <div class="smdn-form-group">
               <h2>Maintain Major Donor</h2>
         </div>
         <div  v-if="controls.loader" class="loader"></div><hr/>
   <div class="smdn-form-group" >
        <label class="smdn-form-label">Major Donor Id</label>
        <input type="text" v-model="donor.Id" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
        <button>Get Donor</button>
      
   </div>
   
    <div class="smdn-form-group">
        <label class="smdn-form-label">First Name</label>
        <input type="text" v-model="donor.FirstName" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
   </div>
     <div class="smdn-form-group">
        <label class="smdn-form-label">Last Name</label>
        <input type="text"  v-model="donor.LastName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
   </div>
     <div class="smdn-form-group">
        <label class="smdn-form-label">Company</label>
        <input type="text"  v-model="donor.Company" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
   </div>
   
   <div class="smdn-form-group">
        <label class="smdn-form-label">Email</label>
        <input v-model="donor.Email" type="text"  class="smdn-setting" id="smdn-setting"  placeholder=" " value="">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Address1</label>
        <input v-model="donor.Address1" type="text"  class="smdn-address" id="smdn-address"  placeholder=" " value="">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Address2</label>
        <input v-model="donor.Address2" type="text"  class="smdn-address" id="smdn-address"  placeholder=" " value="">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">City, State</label>
        <input v-model="donor.City" type="text"  class="smdn-airport" id="smdn-airport"  placeholder=" " value="">
              <select type="text" v-model="donor.State" class="smdn-State" id="smdn-State"  >
                        <option disabled value="">Select</option>
                        <option :value="index" v-for="(state ,index) in getStatesValues"   >{{ state }} </option>
              </select> 
    </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Zip Code</label>
        <input v-model="donor.Zip" type="text"  class="smdn-attendence" id="smdn-attendence"  placeholder=" " value="">
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label">Phone</label>
        <input v-model="donor.Phone" type="text" class="smdn-audience" id="smdn-audience"  placeholder=" " value="">
     
   </div>
   <div class="smdn-form-group">
        <label class="smdn-form-label" > Inactive</label>
        <input v-model="donor.activeStatus"  type="checkbox"  class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
    
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label" > Mail</label>
        <select v-model="donor.emailOption"  name="" id="">
                <option value="Opt In">Opt In</option>
                <option value="Donor Gifts Only">Donor Gifts Only</option>
                <option selected="selected" value="Opt Out">Opt Out</option>
                <option value="No MD TY Gifts">No MD TY Gifts</option>
                <option value="Receipts Only">Receipts Only</option>
                <option value="No TY Letters">No TY Letters</option>
                <option value="Year End Tax Only">Year End Tax Only</option>
            
            </select>
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label" > Current Level</label>
        <input v-model="donor.currentLevel"  type="hidden"  class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
    
   </div>
     <div class="smdn-form-group">
        <label class="smdn-form-label" >Nop Id</label>
        <input v-model="donor.NopID"  type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
    
   </div>
    <div class="smdn-form-group">
         
        <label class="smdn-form-label" >Donations In Last Year</label>
    </div><hr/>
    <div class="smdn-form-group">
        <label class="smdn-form-label" >Count</label>
        <input v-model="donor.totalDonationsCount"  type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
    </div>
    <div class="smdn-form-group">
          <label class="smdn-form-label" >Total</label>
        <input v-model="donor.totalDonationAmount"  type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
   </div>
   
    <div class="smdn-form-group">
         
        <label class="smdn-form-label" >Donations In History</label>
    </div><hr/>
    <div class="smdn-form-group">
        <label class="smdn-form-label" >Count</label>
        <input v-model="donor.totalDonationCtLY"  type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
    </div>
    <div class="smdn-form-group">
          <label class="smdn-form-label" >Total</label>
        <input v-model="donor.totalDonationAmtLY"  type="text"   class="smdn-involvement" id="smdn-involvement"  placeholder=" " value="">
   </div>


     <div class="smdn-form-group">
        <input type="button" class="smdn-btn smdn-btn-info" id=""  v-on:click="MajorDonorSubmitButton" placeholder=" " value="Update">
    
   </div>
  </form>';
        return $formView;
    }



}


new MaintainMajorDonorForm();
