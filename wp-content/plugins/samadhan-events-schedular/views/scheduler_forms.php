<?php


namespace scheduleView;


use Automattic\Jetpack\Sync\Functions;
use DateTime;
use EventFunctons;
use WC_Countries;
use WP_User_Query;

class SMDNSchedularFormView
{
    public  function __construct(){

    }


    public function create_itinerary_form(){

        $formView =' <div id="createItineraryForm"> <div class="smdn-form-group" >
        <h2 class="">Select a Start Date</h2>
   </div><hr/>
    <div class="smdn-form-group">
        <label class="smdn-form-label">Start Date</label>
        <input v-model="createItinerary.startTime" type="date"  class="smdn-type" id="smdn-type"  placeholder=" " value="">
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label">End Date</label>
        <input v-model="createItinerary.endTime" type="date"  class="smdn-type" id="smdn-type"  placeholder=" " value="">
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label">Show Type</label>
        <select v-model="createItinerary.type" name="" id="">
            <option selected="selected" value="0">Select</option>
            <option value="1">David Barton</option>
            <option value="2">Rick Green</option>
            <option value="3">Tim Barton</option>
            <option value="4">Rene Diaz</option>
            <option value="5">Matt Krause</option>
            <option value="6">David Pate</option>
        
        </select>
        
   </div>
         <div class="smdn-form-group" style="    padding-left: 20px;">
                  
                    <input type="button" style="margin-right:10px; " class="smdn-type" id="smdn-type"  placeholder=" " value="Previous">
                    <input type="button"  class="smdn-type" id="smdn-type"  v-on:click="createItinerarySubmitButton"  placeholder=" " value="Submit">
               </div> </div> ';

        return $formView;
    }




}
new SMDNSchedularFormView();