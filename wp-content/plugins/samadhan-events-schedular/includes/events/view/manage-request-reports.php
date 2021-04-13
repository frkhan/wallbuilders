<?php
namespace Samadhan;

class ManageRequest extends UserCRUD {

    public function __construct()
    {
        add_shortcode('smdn_manage_request_reports','samadhan\ManageRequest::get_manage_events_request_report');
    }


    public static function get_manage_events_request_report(){

        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }


        $outPut='<div id="reportShowTable" > 
       <div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;"> Search Options</h2></div>
        <div  v-if="controls.loader" class="loader"></div>
         <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
                 <div style="width: 46%;margin-top: 30px; margin-left: 70px;">
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Event Name</label>
                    <input type="text" v-model="eventfilter.Event" class="smdn-type" id="smdn-type"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Event Type</label>
                    <select v-model="eventfilter.EventType" name="" id="" style="width:60%;">
                        <option selected="selected" value="All">All</option>
                        <option value="Church">Church</option>
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
                    <label class="smdn-form-label">Organization</label>
                    <input type="text" v-model="eventfilter.Organization" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text" v-model="eventfilter.City"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group" >
                    <label class="smdn-form-label">State</label> 
                    <input type="text" v-model="eventfilter.State"   class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
                  <div class="smdn-form-group" >
                    <label class="smdn-form-label">Speaker</label> 
                  <select type="text"  v-model="eventfilter.SpeakerRequestId"  class="smdn-speaker" id="smdn-speaker"  >
                        <option disabled selected value="">Select</option>
                        <option :value="state.Id" v-for="state in getSpeakerValues"   >{{ state.FullName }} </option>
                    </select> 
                   
               </div>
           </div>
           
     <div style="width: 46%; float: right; margin-top: 30px; " >     
         <div class="smdn-form-group">
            <label class="smdn-form-label">Request Status</label>
            <select v-model="eventfilter.Status" name="" id="" style="width:30%;">
                <option selected="selected" value="All">All</option>
                <option value="Unanswered">Unanswered</option>
                <option value="Scheduled">Scheduled</option>
                <option value="Declined">Declined</option>
            
            </select>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Req First Name</label>
            <input type="text" v-model="eventfilter.FirstName"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Req Last Name</label>
            <input type="text" v-model="eventfilter.LastName"   class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Req Email</label>
            <input type="text"  v-model="eventfilter.Email"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Requests Submitted in Last</label>
            <input name=""  v-model="eventfilter.ContactDate"  type="text" value="365" id="" style="width:10%;"> Days
       </div>
      
         <div class="smdn-form-group">
            <input type="button"  v-on:click="eventsRequestFilter" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="Search">
       </div>
   </div>
   </div>';

        $outPut .='<div  >
    <table class="table table-hover">
  <thead>
    <tr style="background: #546666;color:#fff;">
     <th colspan="8">All Scheduling Requests Submitted in Last 365 Days</th>
      <th>
          <select type="text" v-model="perPage" v-on:click="postPageEntities"class="smdn-type" id="smdn-type"  >
                <option disabled value="">Select</option>
                <option v-for="page in getPageList"  >{{ page }} </option>
            </select> </th>
      <th colspan="10">Search: <input type="text" v-model="searchValue" v-on:change="searchEvents" id="searchValue" class="searchValue" placeholder="Search.."></th>
   
    </tr>
    <tr style="background: #546666;color:#fff;">
      <th >Contacted</th>
      <th >Event</th>
      <th >Type</th>
      <th >Event Date</th>
      <th >Status</th>
      <th >Deadline</th>
      <th >Speaker</th>
      <th >Alt</th>
      <th >First Name</th>
      <th >Last Name</th>
      <th >Email</th>
      <th >Phone </th>
      <th >Red Flag</th>
    </tr>
  </thead>
  <tbody  >

    <tr v-for="(item, index) in displayedData">
      <td >{{item.ContactDate}}</td>
      <td><a :href="homeUrl+`${item.RequestId}`" >  {{item.Event}}</a></td>
      <td>{{item.EventType}}</td>
      <td>{{item.EventStartDate}} - {{item.EventEndDate}}</td>
      <td>{{item.status}}</td>
      <td>{{item.Deadline}}</td>
      <td>{{item.Nickname}}</td>
      <td v-if="item.AltSpeaker==1">Yes</td>
      <td v-if="item.AltSpeaker==0">No</td>
      <td>{{item.FirstName}}</td>
      <td>{{item.LastName}}</td>
      <td>{{item.Email}}</td>
      <td>{{item.Phone1}}</td>
      <td>{{item.RedFlagId}}</td>
    </tr>
    
  </tbody>
</table>
<nav v-if="paginationBlock" aria-label="Page navigation">
              
                    <ul class="pagination" style="display: inline-flex; list-style-type: none;margin: 0 0 22px 0;" v-if="totalPages>0">
                        <li class="page-item prevButton">
                            <button type="button" class="page-link" v-if="page != 1" @click="page--"> Prev </button>
                        </li>
                        
                            <li style="padding: 0 4px;" class="page-item buttonlist" v-for="pageNumber in totalPages" v-if="Math.abs(pageNumber - page) <1.5 || pageNumber == totalPages - 1 || pageNumber == 0">
    <button  @click="setPage(pageNumber)"  :class="{current: page === pageNumber, last: (pageNumber == totalPages - 1 && Math.abs(pageNumber - page) <= 2), first:(pageNumber == 0 && Math.abs(pageNumber - page) <= 2)}">{{ pageNumber+1 }}</button>
    </li>
    
     <!--                   <li class="page-item buttonlist">
                            <button type="button" class="page-link" v-for="pageNumber in pages.slice(page-1, page+2)" @click="page = pageNumber" :class="{current: page === pageNumber}"> {{pageNumber}} </button>
                            <button type="button" class="page-link" v-if="page < pages.length" > {{totalPages}} </button>
                        </li>-->
                         
                            
                     
                        <li class="page-item nextButton" >
                            <button type="button" @click="page++"  v-if="page<=pages.length" :class="{lastButtonHidden: page === totalPages}" class="page-link"> Next </button>
                        </li>
                    </ul>
		        </nav>	
		        <div class="smdn-form-group" style="    padding-left: 20px;">
                                  
                    <input type="button" style="margin-right:10px; " class="smdn-type" id="smdn-type"  placeholder=" " value="Previous">
                    <input type="button"  class="smdn-type" id="smdn-type"  placeholder=" " value="Add">
                </div>

</div>

</div>';
        return $outPut;

    }


}

new ManageRequest();