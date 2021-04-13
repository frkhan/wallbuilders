<?php
namespace Samadhan;
use Samadhan\UserCRUD;

class RequestDetailsReport extends SchedulerFunctions{

    public function __construct()
    {
        add_shortcode('smdn_schedule_request_details_report','Samadhan\RequestDetailsReport::get_schedule_request_details_report');
    }


    public static function get_schedule_request_details_report(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }


        //  EventFunctons::samadhan_get_all_event_request_data();

        $event_id=$_GET['event_id'];


        $getEventData= parent::getEventDataById( $event_id);
        // var_dump($getEventData);
        $getAllSpeaker= parent::get_speaker_all_data();

        $body='';
        foreach ($getAllSpeaker['speaker'] as $speaker){
            $body .=" <tr >
                      <td>$speaker->Nickname</td>
                      <td></td>
                      <td><span style='float: left'>Unscheduled</span><span style='float: right'><input checked type='checkbox'></span></td>
                      <td></td>
                      <td>False</td>
                    </tr>";
        }


        $outPut='<div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;"> Request Details</h2></div>

           <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
                 <div style="width:100%;margin-top: 30px; margin-left: 70px;">
                <div class="smdn-form-group">
                  <input id="setEventId" type="hidden" value="'.$event_id.'" >
                    <label class="smdn-form-label">Event</label>
                    <input type="text"  class="smdn-type" id="smdn-type"  placeholder=" " value="'.$getEventData[0]->Event.'"><a href="'.home_url('/edit-scheduling-request/?event_id='.$event_id).'"> <button>Edit</button></a>
               </div>
              
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Type</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventType.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Speaker</label>
                    <input type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Nickname.'"> <span style="padding: 0 20px"> Alt Speaker<input type="checkbox" '.($getEventData[0]->AltSpeaker==1 ? "checked": "").' name="AltSpeaker">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Start DateTime</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventStartDate.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">End DateTime</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventEndDate.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Organization</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Organization.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Contact</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->FirstName.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Contact Date</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->ContactDate.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Deadline</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Deadline.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Setting/Location</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Setting.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Address</label>
                    <textarea style="width:50%" type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Address1.'">'.$getEventData[0]->Address1.'</textarea>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Airport</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Airport.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Attendance</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Attendance.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Involvement</label>
                    <textarea  style="width:50%" type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Involvement.'">'.$getEventData[0]->Involvement.'</textarea>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Other Participants</label>
                    <textarea style="width:50%" type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->OtherParticipants.'">'.$getEventData[0]->OtherParticipants.'</textarea>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Declined</label>
                    <input type="checkbox" '.($getEventData[0]->Decline==1 ? "checked": "").' name="Decline" class="" id="" value="'.$getEventData[0]->Decline.'" >
                   
                    <select name="DeclineReasonId" id="" style="width:40%;">
                            <option selected="selected" value="0">Select</option>
                            <option '.($getEventData[0]->DeclineReasonId==1 ? "selected": "").' value="1">Event Cancelled</option>
                            <option '.($getEventData[0]->DeclineReasonId==2 ? "selected": "").' value="2">Duplicate Request</option>
                            <option '.($getEventData[0]->DeclineReasonId==3 ? "selected": "").' value="3">All Speakers Booked</option>
                            <option '.($getEventData[0]->DeclineReasonId==4 ? "selected": "").' value="4">Dead Request</option>
                            <option '.($getEventData[0]->DeclineReasonId==5 ? "selected": "").' value="5">Other</option>
                        
                        </select>
               </div>
           </div>

   </div>';

        $outPut .='<div id="reportShowTable">
    <table class="table table-hover">
  <thead>
    <tr style="background: #546666;color:#fff;">
 
      <th colspan="4" >
          <select type="text" v-model="perPage" v-on:click="postPageEntities"class="smdn-type" id="smdn-type"  >
                <option disabled value="">Select</option>
                <option v-for="page in getPageList"  >{{ page }} </option>
            </select> </th>
      <th colspan="8">Search: <input type="text" v-model="searchValue" v-on:change="searchEvents" id="searchValue" class="searchValue" placeholder="Search.."></th>
   
    </tr>
    <tr style="background: #546666;color:#fff;">
      
      <th >Speaker</th>
      <th >Prior Event</th>
      <th >This Event</th>
      <th >Next Event</th>
      <th >Conflict</th>
    </tr>
  </thead>
  <tbody >

   '.$body.'
    
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
		        
                 <div class="smdn-form-group">
                        <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Previous">
                        <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Print Request">
                        <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Create Itinerary">
                        <input type="button" class="smdn-btn smdn-btn-info" id=""  placeholder=" " value="Decline Request">
                 </div>

</div>';
        return $outPut;

    }


}

new RequestDetailsReport();