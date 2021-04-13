<?php
namespace Samadhan;
use Samadhan\UserCRUD;

class MediaReports extends schedulerFunctions {

    public function __construct()
    {
        add_shortcode('smdn_manage_media_reports','Samadhan\MediaReports::get_manage_media_request_report');
    }


    public static function get_manage_media_request_report(){
        //  EventFunctons::samadhan_get_all_event_request_data();


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        $outPut='<div id="ManageMediaRequestReport" >

          <div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;"> Search Options</h2></div>
            <div  v-if="controls.loader" class="loader"></div>
           <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
                 <div style="width: 46%;margin-top: 30px; margin-left: 70px;">
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Show Name</label>
                    <input type="text"  v-model="filter.ShowName" class="smdn-type" id="smdn-type"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Show Type</label>
                    <select v-model="filter.ShowType" name="" id="" style="width:25%;">
                        <option value="Christian">All</option>
                        <option value="Christian">Christian</option>
                        <option value="Secular">Secular</option>
                        <option value="Jewish">Jewish</option>
                        <option value="Conservative/Politic">Conservative/Politic</option>
                        <option value="Both C &amp; S">Both C &amp; S</option>
                        <option value="Undefined">Undefined</option>
                     </select>
                   
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Host Name</label>
                    <input v-model="filter.HostName" type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Topic</label>
                    <input type="text" v-model="filter.Topic"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
               </div>
                <div class="smdn-form-group" >
                    <label class="smdn-form-label">Media Type</label>
                    <select id="" v-model="filter.MediaType" name="" style="width:40%;">
                        <option value="Church">Church</option>
                        <option value="Convention">Convention</option>
                        <option value="Magazine">Magazine</option>
                        <option value="Movie/Documentary">Movie/Documentary</option>
                        <option value="Newspaper">Newspaper</option>
                        <option value="Other">Other</option>
                        <option value="Podcast">Podcast</option>
                        <option selected="selected" value="Radio">Radio</option>
                        <option value="Radio &amp; TV">Radio &amp; TV</option>
                        <option value="SchoolProject">SchoolProject</option>
                        <option value="Simulcast">Simulcast</option>
                        <option value="TV">TV</option>
                        <option value="Video">Video</option>
                        <option value="Web Article/Blog">Web Article/Blog</option>
                        <option value="Unknown">Unknown</option>
                      </select>
                   
                  
               </div>
           </div>
           
     <div style="width: 46%; float: right; margin-top:30px;" >     
         <div class="smdn-form-group">
            <label class="smdn-form-label">Contact Name</label>
            <input type="text"  v-model="filter.ContactName" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Contact Email</label>
            <input type="text" v-model="filter.Email"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
      <div class="smdn-form-group">
        <label class="smdn-form-label">Speaker</label>
         <select type="text" v-model="filter.SpeakerId"  class="smdn-speaker" id="smdn-speaker"  >
                        <option  selected value="">Select</option>
                        <option :value="state.Id" v-for="state in getSpeakerValues"   >{{ state.FullName }} </option>
           </select> 
          
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Interview Date</label>
            <input type="date" v-model="filter.InterviewDateTime" class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">
       </div>
         <div class="smdn-form-group">
            <input type="button"  class="smdn-end-date" id="smdn-end-date" v-on:click="mediaRequestFilter" placeholder=" " value="Search">
       </div>
   </div>
   </div>';

        $outPut .='<div >
    <table class="table table-hover">
  <thead>
    <tr style="background: #546666;color:#fff;">
   <th colspan="7">All Media Requests</th>
      <th  >
          <select type="text" v-model="perPage" v-on:click="postPageEntities"class="smdn-type" id="smdn-type"  >
                <option disabled value="">Select</option>
                <option v-for="page in getPageList"  >{{ page }} </option>
            </select> </th>
      <th colspan="6">Search: <input type="text" v-model="searchValue" v-on:change="searchEvents" id="searchValue" class="searchValue" placeholder="Search.."></th>
   
    </tr>
    <tr style="background: #546666;color:#fff;">
      <th >Interview DateTime</th>
      <th >Speaker</th>
      <th >Show Name</th>
      <th >Host Name</th>
      <th >Topic</th>
      <th >Show Type</th>
      <th >Media Type</th>
      <th >ContactName</th>
      <th >Phone</th>
      <th >Email</th>
      <th >Red Flag</th>
    </tr>
  </thead>
  <tbody >

    <tr v-for="(item, index) in displayedData">
      <td @click="addToCount(4)" ><a :href="pageUrl+`${item.Id}`" >{{item.InterviewDateTime}}</a></td>
      <td>{{item.Nickname}}</td>
      <td><a :href="pageUrl+`${item.Id}`" >{{item.ShowName}}</a></td>
      <td>{{item.HostName}}</td>
      <td>{{item.Topic}}</td>
      <td>{{item.ShowType}}</td>
      <td>{{item.MediaType}}</td>
      <td>{{item.ContactName}}</td>
      <td>{{item.Phone}}</td>
      <td>{{item.Email}}</td>
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

</div></div>';
        return $outPut;

    }

}

new MediaReports();
