<?php
namespace Samadhan;
use Samadhan\UserCRUD;

class SpeakerReport {

    public function __construct()
    {
        add_shortcode('smdn_schedule_speakers_report','Samadhan\SpeakerReport::get_schedule_speakers_report');
    }

    public static function get_schedule_speakers_report(){
        //  EventFunctons::samadhan_get_all_event_request_data();


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }


        $outPut ='<div id="ScheduleRickGreenTable">
                    <div class="srchItem">Speaker &nbsp;&nbsp;
                     <select type="text" v-model="SpeakerId"  v-on:change="filterScheduleGreenSpeaker" class="smdn-speaker" id="smdn-speaker"  >
                        <option  selected value="all">All</option>
                        <option :value="state.Id" v-for="state in getSpeakerValues"   >{{ state.FirstName }} </option>
                    </select> 
                     &nbsp;&nbsp;&nbsp;&nbsp;
                      
                    </div>
    <table class="table table-hover">
  <thead>
    <tr style="background: #546666;color:#fff;">
       <th colspan="7" >Scheduled Events</th>
      <th colspan="" >
          <select type="text" v-model="perPage" v-on:click="postPageEntities"class="smdn-type" id="smdn-type"  >
                <option disabled value="">Select</option>
                <option v-for="page in getPageList"  >{{ page }} </option>
            </select> </th>
      <th colspan="">Search: <input type="text" v-model="searchValue" v-on:change="searchScheduleGreen" id="searchValue" class="searchValue" placeholder="Search.."></th>
   
    </tr>
    <tr style="background: #546666;color:#fff;">
      <th >Event Date</th>
      <th >Event</th>
      <th >Type</th>
      <th >Organization</th>
      <th >city</th>
      <th >State</th>
      <th >Contact Name</th>
      <th >Contact Phone</th>
      <th >Speaker</th>
    </tr>
  </thead>
  <tbody >

    <tr v-for="(item, index) in displayedData">
      <td >{{item.EventEndDate}}</td>
      <td>{{item.Event}}</td>
      <td>{{item.EventType}}</td>
      <td>{{item.Organization}}</td>
      <td>{{item.City}}</td>
      <td>{{item.State}}</td>
      <td>{{item.FirstName}} {{item.LastName}}</td>
       <td>{{item.Phone1}}</td>
      <td>{{item.FullName}}</td>
    
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
                    <input type="button"  class="smdn-type" id="smdn-type"  placeholder=" " value="Create Itinerary">
               </div>

</div>';
        return $outPut;

    }


}

new SpeakerReport();
