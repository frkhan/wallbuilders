<?php
namespace Samadhan;
use Samadhan\UserCRUD;

use DateTime;
use WC_Countries;

class ScheduleRequestInformation extends SchedulerFunctions{

    public function __construct()
    {
        add_shortcode('smdn_schedule_request_informtion','Samadhan\ScheduleRequestInformation::scheduling_event_information_form');
    }


    public function scheduling_event_information_form(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        //  var_dump($getEventData[0]->WillingToReturn);


        $event_id=$_GET['event_information_id'];
        if(!empty($event_id) && isset($event_id)){
            $buttonName="Update";
            $titleName="Update Scheduling Request Information";
        }else{
            $buttonName="Save";
            $titleName="Add Scheduling Request Information";
        }


        if(isset($_POST['EventFollowUp'])){
            $willingToReture=(int)$_POST['willingToReture'];
            $FollowUpNotes=$_POST['FollowUpNotes'];
            $FollowUpResponse=$_POST['FollowUpResponse'];

            $resutl=parent::updateEventDataById($event_id,array('WillingToReturn'=>$willingToReture,'FollowUpResponse'=>$FollowUpResponse,'FollowUpNotes'=>$FollowUpNotes));
            if($resutl){
                $getUpdateMsg="<h2 style='color:green'>Updated Successfully Data !!</h2>";
            }else{
                $getUpdateMsg="<h2 style='color:red;'>Updated  Unsuccessfully Data !!</h2>";
            }
        }

        $getEventData= parent::getEventDataById( $event_id);


        $WC_Countries = new WC_Countries();
        $states= $WC_Countries->get_states( 'US' );

        $contactOptions =  "<option value=''>Select</option>";
        foreach ($states as $key=>$state){
            if($getEventData[0]->State==$key){
                $selected="selected='selected'";
            }else{
                $selected="";
            }
            $contactOptions .="<option $selected value='$key'>$state</option>";
        }

        $leaderOptions =  "<option value=''>Select</option>";
        foreach ($states as $key=>$state){
            if($getEventData[0]->LeaderState==$key){
                $selected="selected='selected'";
            }else{
                $selected="";
            }
            $leaderOptions .="<option $selected value='$key'>$state</option>";
        }

        $eventOptions =  "<option value=''>Select</option>";
        foreach ($states as $key=>$state){
            if($getEventData[0]->EventState==$key){
                $selected="selected='selected'";
            }else{
                $selected="";
            }
            $eventOptions .="<option $selected value='$key'>$state</option>";
        }

        $getSpeaker=  parent::get_speaker_all_data();
        //var_dump($getSpeaker['speaker']);
        $speakerOptions =  "<option value=''>Select</option>";
        foreach ($getSpeaker['speaker'] as $speaker){
            if($getEventData[0]->SpeakerRequestId==$speaker->Id){
                $selected="selected='selected'";
            }else{
                $selected="";
            }
            $speakerOptions .="<option $selected value='$speaker->Nickname'>$speaker->FirstName</option>";
        }



        $date = new DateTime($getEventData[0]->ContactDate);
        $contactDate= $date->format('Y-m-d');


        $EventStartDate = new DateTime($getEventData[0]->EventStartDate);
        $getEventStartDate= $EventStartDate->format('Y-m-d');
        $getEventStartHour= $EventStartDate->format('h');
        $getEventStartMinites= $EventStartDate->format('i');
        $getEventStartExtra= $EventStartDate->format('A');

        $EventEndDate = new DateTime($getEventData[0]->EventEndDate);
        $getEventEndDate= $EventEndDate->format('Y-m-d');
        $getEventEndHour= $EventEndDate->format('h');
        $getEventEndMinites= $EventEndDate->format('i');
        $getEventEndExtra= $EventEndDate->format('A');

        $Deadline = new DateTime($getEventData[0]->Deadline);
        $getDeadline= $Deadline->format('Y-m-d');

        // var_dump($getEventData[0]); //FirstTime

        $outPut='<div id="schedulingRequestForm" ><div style="background: #546666" ><h2 style="color: #fff;padding: 12px 12px 9px 42px;"> '.$titleName.'</h2></div>
               
               
           <div style="background: #fff;display: flex;padding-bottom: 20px;margin-bottom: 20px;">
             <input id="eventId" type="hidden" value="'.$event_id.'" >
         
        
            
            <!---first column --------->
                 <div style="width: 46%;margin-top: 30px; margin-left: 40px;">
                 
                  <div class="smdn-form-group">
                     <h2 ></h2>
                    
                </div> 
                <div class="smdn-form-group">
                    <label class=""><h4>Contact</h4></label>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Contact Date</label>
                    <input type="date"  class="smdn-type" id="smdn-type"  placeholder=" " value="'.$contactDate.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">First Request</label>
                    <input type="checkbox"  '.($getEventData[0]->FirstTime==1 ? "checked":"").' class="smdn-type" id="smdn-type"  placeholder=" " value="yes">
               </div>
               
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Organization</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Organization.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Title</label>
                    <input name=""  type="text" id="" style="width:20%;" value="'.$getEventData[0]->Title.'">  <span style="padding: 0 20px;"> Pastor</span> <input type="checkbox" '.($getEventData[0]->Pastor==='1' ? "checked":"").'   class="smdn-type" id="smdn-type"  placeholder=" " value="">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">First Name</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->FirstName.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Last Name</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LastName.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Address1</label>
                    <textarea type="text"   style="width: 50%;" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventAddress1.'"></textarea>
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Address2</label>
                    <textarea type="text"   style="width: 50%;" class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventAddress2.'"></textarea>
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventCity.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">State</label>
                      <select type="text"  class="smdn-State" id="smdn-State"  >
                      
                        '.$contactOptions.'
                    </select> 
                 
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Zip</label>
                    <input type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Zip.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 1</label>
                    <input name="" type="text"  id="" placeholder="999-999-9999" style="width:40%;" value="'.$getEventData[0]->Phone1.'"> <span style="0 20px;"> EXt. </span><input name="" value="'.$getEventData[0]->Extension1.'" type="text" id="" style="width:20%;">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 2</label>
                    <input name="" type="text"  id="" placeholder="999-999-9999" style="width:40%;" value="'.$getEventData[0]->Phone2.'"> <span style="0 20px;"> EXt. </span><input name="" value="'.$getEventData[0]->Extension2.'" type="text" id="" style="width:20%;">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Fax</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder="999-999-9999" value="'.$getEventData[0]->Fax.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Email</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->Email.'">
             </div><hr/>';
        $outPut .='  <div class="smdn-form-group">
                    <label class=""><h4>LEADERSHIP INFORMATION (if different from above)</h4></label>
               </div><hr/>
                           <div class="smdn-form-group">
                    <label class="smdn-form-label">Title</label>
                    <input name=""  type="text" id="" style="width:20%;" value="'.$getEventData[0]->LeaderTitle.'">  <span style="padding: 0 20px;"> Pastor</span> <input type="checkbox" '.($getEventData[0]->LeaderPastor==='1' ? "checked":"").' class="smdn-type" id="smdn-type"  placeholder=" " value="'.$getEventData[0]->Event.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">First Name</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LeaderFirstName.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Last Name</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LeaderLastName.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Address1</label>
                    <textarea style="width: 50%;"  type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LeaderAddress1.'">'.$getEventData[0]->LeaderAddress1.'</textarea>
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Address2</label>
                    <textarea style="width: 50%;"  type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LeaderAddress2.'">'.$getEventData[0]->LeaderAddress2.'</textarea>
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LeaderCity.'">
               </div>';
        $outPut .='  <div class="smdn-form-group">
                    <label class="smdn-form-label">State</label>
                    <select type="text"  class="smdn-State" id="smdn-State"  >
                    '.$leaderOptions.'
                    </select> 
                    
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Zip</label>
                    <input  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->LeaderZip.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 1</label>
                    <input  name="" type="text" id="" placeholder="999-999-9999" value="'.$getEventData[0]->LeaderPhone1.'"> 
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Phone 2</label>
                    <input  name="" type="text" id="" placeholder="999-999-9999" value="'.$getEventData[0]->LeaderPhone2.'" > 
               </div>
             
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Email</label>
                    <input type="text"  class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->LeaderEmail.'">
               </div>
               
           </div>';



        $outPut .='  <div style="width: 46%; float: right; margin-top:30px;" >   
             <!---second column --------->  
         <div class="smdn-form-group">
            <label class=""><h4>Event Information</h4></label>
       </div>
        <div class="smdn-form-group">
            <label class="smdn-form-label">Name</label>
            <input type="text"   class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Event.'">
       </div>
      <div class="smdn-form-group">
        <label class="smdn-form-label">Red Flag <i class="form-icon fugue-3 flag-green">&nbsp;&nbsp;</i></label>
            <select   name="" id="" style="width:40%;">
                <option '.($getEventData[0]->RedFlagId==='0' ? "selected":"").'  value="0">Select</option>
                <option '.($getEventData[0]->RedFlagId==='1' ? "selected":"").' value="1">Red Flag Alert</option>
            </select> 
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Type</label>
            <select   name="" id="" style="width:60%;">
                    <option selected="selected" value="Church">Church</option>
                    <option '.($getEventData[0]->EventType==="University" ? "selected" : "").' value="University">University</option>
                    <option '.($getEventData[0]->EventType==="School" ? "selected":"").' value="School">School</option>
                    <option '.($getEventData[0]->EventType==="Legislative" ? "selected":"").' value="Legislative">Legislative</option>
                    <option '.($getEventData[0]->EventType==="Capitol Tour" ? "selected":"").' value="Capitol Tour">Capitol Tour</option>
                    <option '.($getEventData[0]->EventType==="Civic Group" ? "selected":" ").' value="Civic Group">Civic Group</option>
                    <option '.($getEventData[0]->EventType==="Military" ? "selected":"").' value="Military">Military</option>
                    <option '.($getEventData[0]->EventType==="Other" ? "selected":"").' value="Other">Other</option>
                
                </select>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Setting/Location</label>
            <input type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Setting.'">
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address1</label>
            <textarea style="width: 50%;"  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->EventAddress1.'">'.$getEventData[0]->EventAddress1.'</textarea>
       </div>
       <div class="smdn-form-group">
            <label class="smdn-form-label">Address2</label>
            <textarea style="width: 50%;"   type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->EventAddress2.'">'.$getEventData[0]->EventAddress2.'</textarea>
       </div>
      
            <div class="smdn-form-group">
                    <label class="smdn-form-label">City</label>
                    <input type="text"   class="smdn-start-date" id="smdn-start-date"  placeholder=" " value="'.$getEventData[0]->EventCity.'">
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">State</label>
                      <select type="text"  class="smdn-State" id="smdn-State"  >
                     '.$eventOptions.'
                    </select> 
             
               </div>';
        $outPut .=' <div class="smdn-form-group">
                    <label class="smdn-form-label">Zip</label>
                    <input type="text"   class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->EventZip.'">
               </div>
              <div class="smdn-form-group">
                    <label class="smdn-form-label">Airport</label>
                    <input type="text"   class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Airport.'">
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Time To Airport</label>
                    <input type="text"   class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->TimeToAirport.'"> Minutes 
               </div>
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Start DateTime</label>
                        <input  name="" type="date" id="" style="width:30%;margin-right: 30px;" class="hasDatepicker" value="'.$getEventStartDate.'">
                        <span class="form-icon fugue-2 calendar-day" style="; margin-left:-18px;"></span>
                        <input  name="" type="text" value="'.$getEventStartHour.'" id="" placeholder="12" style="width:10%;" > : <input name=""  type="text" value="'.$getEventStartMinites.'" id="" placeholder="00" style="width:10%;">
                        <select  name="" id="" style="width:15%;">
                            <option '.($getEventStartExtra==="AM" ? "selected":"").'  value="AM">AM</option>
                            <option '.($getEventStartExtra==="PM" ? "selected":"").' value="PM">PM</option>
                        
                        </select>
                  
               </div>
                     <div class="smdn-form-group">
                    <label class="smdn-form-label">End DateTime</label>
                        <input  name="" type="date" id="" value="'.$getEventEndDate.'" style="width:30%;margin-right: 30px;" class="hasDatepicker">
                        <span class="" style=" margin-left:-18px;"></span>
                        <input  name="" type="text" value="'.$getEventEndHour.'" id="" placeholder="12" style="width:10%;"> : <input name=""  type="text" value="'.$getEventEndMinites.'" id="" placeholder="00" style="width:10%;">
                        <select  name="" id="" style="width:15%;">
                         <option '.($getEventEndExtra==="AM" ? "selected":"").'  value="AM">AM</option>
                            <option '.($getEventEndExtra==="PM" ? "selected":"").' value="PM">PM</option>
                        
                        </select>
                  
               </div>
               
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Attendance</label>
                    <input  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Attendance.'">
               </div>  
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Audience</label>
                    <input  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Audience.'"> 
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Speaker</label>
                    <select type="text" class="smdn-speaker" id="smdn-speaker"  >
                       '.$speakerOptions.'
                    </select>  
               </div>
                <div class="smdn-form-group">
                    <label class="smdn-form-label">Alt Speaker</label>
                    <select   name="" id="" style="width:20%;">
                        <option '.($getEventData[0]->AltSpeaker==='1' ? "selected":"").'  value="1">Yes</option>
                        <option '.($getEventData[0]->AltSpeaker==='0' ? "selected":"").' value="0">No</option>
                    
                    </select>
               </div>
               
             <div class="smdn-form-group">
                    <label class="smdn-form-label">Involvement</label>
                    <textarea style="width: 50%;" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Event.'">'.$getEventData[0]->Involvement.' </textarea>
               </div> 
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Other Participants</label>
                    <textarea style="width: 50%;"  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">'.$getEventData[0]->OtherParticipants.' </textarea>
               </div>  
                <div class="smdn-form-group">
                    <label class="smdn-form-label">How they know?</label>
                    <textarea style="width: 50%;" type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="">'.$getEventData[0]->HowTheyKnow.' </textarea>
               </div> 
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Notes</label>
                    <textarea style="width: 50%;"  type="text"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value=""> '.$getEventData[0]->Notes.'</textarea>
               </div>
               <div class="smdn-form-group">
                    <label class="smdn-form-label">Deadline</label>
                    <input  type="date"  class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getDeadline.'"> 
               </div>
                 <div class="smdn-form-group">
                    <label class="smdn-form-label">Decline</label>
                    <input  type="checkbox" '.($getEventData[0]->Decline==='1' ? "checked":"").' class="smdn-end-date" id="smdn-end-date"  placeholder=" " value="'.$getEventData[0]->Decline.'"><span style="0 20px">Reason</span> 
                    <select  name="" id="" style="width:40%;">
                            <option '.($getEventData[0]->DeclineReasonId==="0" ? "selected":"").' selected="selected" value="0">Select</option>
                            <option '.($getEventData[0]->DeclineReasonId==="1" ? "selected":"").' value="1">Event Cancelled</option>
                            <option '.($getEventData[0]->DeclineReasonId==="2" ? "selected":"").' value="2">Duplicate Request</option>
                            <option '.($getEventData[0]->DeclineReasonId==="3" ? "selected":"").' value="3">All Speakers Booked</option>
                            <option '.($getEventData[0]->DeclineReasonId==="4" ? "selected":"").' value="4">Dead Request</option>
                            <option '.($getEventData[0]->DeclineReasonId==="5" ? "selected":"").' value="5">Other</option>
                        
                        </select>
               </div>  
               
         
   </div>
   </div></div>';

        $outPut .='<form method="post"> <div class="box grid_6" style="margin-bottom:5px;">
			<div class="box-head" style="background: rgb(84, 102, 102);">
				<h2 style="color: #fff;padding: 15px;">Event Follow Up</h2>
            </div>
            <div class="box-content" style="min-height: 0px; display: block;">
                <div class="form-row">
                    <div class="form-label">Willing to Return</div>
                    <div class="form-item"><select name="willingToReture" id="" style="width:10%;">
	<option '.($getEventData[0]->WillingToReturn==='1' ? "selected" : "").' value="1">Yes</option>
	<option '.($getEventData[0]->WillingToReturn==='0' ? "selected" : "").' value="0">No</option>

</select></div>
                    <br>
                </div>
                <div class="form-row">
                    <div class="form-label">Follow Up Response</div>
                    <div class="form-item"><textarea name="FollowUpResponse" rows="6" cols="20" id="" style="width:90%;">'.$getEventData[0]->FollowUpResponse.'</textarea></div>
                </div>
                <div class="form-row">
                    <div class="form-label">Follow Up Notes</div>
                    <div class="form-item"><textarea name="FollowUpNotes" rows="6" cols="20" id="" style="width:90%;">'.$getEventData[0]->FollowUpNotes.'</textarea></div>
                </div>
                <div class="form-row" style="padding-top:20px; padding-bottom: 20px;">
                    <input type="submit" name="EventFollowUp" value="Save" id="" class="button big wbblue">
                </div>           
                <div class="form-row">
                    <div style="color:red;">'.$getUpdateMsg.'</div>
                </div>   
             </div>
        </div></form>';



        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;


        if(isset($_GET['perPage']) && !empty($_GET['perPage'])){
            $posts_per_page=$_GET['perPage'];

        }else{
            $posts_per_page=10;

        }


        if(isset($_POST['searchButton'] ) && !empty($_POST['searchName'])){

            $searchName=$_POST['searchName'];
            $posts_per_page=$_POST['perPage'];
            $offset = 0;

            $resuts=parent::get_topic_data($searchName,$offset,$posts_per_page);

        }else{
            $offset = ($paged - 1) * $posts_per_page;

            $resuts=parent::get_topic_data($searchName='',$offset,$posts_per_page);
        }

        $total=$resuts['totalTopics'];
        $body='';
        $count_entity = 0;
        foreach ($resuts['allTopics'] as $resut){
            $count_entity++;
            $body .='<tr rel="tpc1783" class="tpcItem odd">
                                    <td class="tpcTitle">'.$resut->Topic.'</td>
                                    <td class="tpcDesc  sorting_1">'.$resut->Description.'</td>
                                </tr>';
        }

        $firstItemNo = ($paged-1)* $posts_per_page+1;
        $lastItemNo = $firstItemNo + $count_entity-1; // $firstItemNo + PageSize
        $totalEntries = $total;

        $totalPages = ceil($total / $posts_per_page);
        // var_dump($totalPages);
        $big = 999999999;
        $pagination = paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var( 'paged' ) ),
            'total' =>  $totalPages,
            'add_args' => array(
                'perPage' => $posts_per_page,
            )
        ) );



        $outPut .='<form method="post" <div class="box grid_6" style="margin-bottom:5px;">
			<div class="box-head" style="background: rgb(84, 102, 102);">
				<h2 style="color: #fff;padding: 15px;">Event Topics</h2>
            </div>
            
            <div class="box-content no-pad" style="min-height: 0px; display: block;">
                <div id="dtList_wrapper" class="dataTables_wrapper" role="grid"><div class="fg-toolbar ui-toolbar ui-corner-tl ui-corner-tr ui-helper-clearfix">
                <div id="dtList_length" class="dataTables_length">
                <label>Show 
                     <select size="1" name="perPage" aria-controls="dtList">
                            <option '.($posts_per_page==10 ? "selected": "").' value="10" selected="selected">10</option>
                            <option '.($posts_per_page==25 ? "selected": "").' value="25">25</option>
                            <option '.($posts_per_page==50 ? "selected": "").' value="50">50</option>
                            <option '.($posts_per_page==100 ? "selected": "").' value="100">100</option>
                            </select>
                             entries</label>
                             <label style="margin-left: 100px;">Search: <input type="text" name="searchName" value="'.$searchName.'" aria-controls="dtList"></label>
                             <label > <input type="submit"  value="Search"  name="searchButton" class="btn btn--secondary" aria-controls="dtList"></label>
                             </div></div><table class="display dataTable" id="dtList" aria-describedby="dtList_info">
                    <thead>
                        <tr role="row"><th class="ui-state-default" role="columnheader" tabindex="0" aria-controls="dtList" rowspan="1" colspan="1" style="width: 296px;" aria-label="Topic: activate to sort column ascending"><div class="DataTables_sort_wrapper">Topic<span class="DataTables_sort_icon css_right ui-icon ui-icon-carat-2-n-s"></span></div></th><th class="ui-state-default" role="columnheader" tabindex="0" aria-controls="dtList" rowspan="1" colspan="1" style="width: 504px;" aria-sort="ascending" aria-label="Description: activate to sort column descending"><div class="DataTables_sort_wrapper">Description<span class="DataTables_sort_icon css_right ui-icon ui-icon-triangle-1-n"></span></div></th></tr>
                    </thead>
                    
                <tbody role="alert" aria-live="polite" aria-relevant="all">
                '.$body.'
                
             </tbody>';
        $outPut .="<tfoot><tr bgcolor='#d3d3d3'><td colspan='20'> Showing " .$firstItemNo  . " to " . $lastItemNo . " of " .$totalEntries. " entries  <span style='float: right;'>$pagination</span></td>
                    
                    </tr></tfoot>";
        $outPut .='</table></form><input type="button" id="addTopic" class="button small wbblue" value="Add Topic"> 
            </div>
        </div>';



        if(isset($_POST['SaveTopic'])){
            $Topic=$_POST['Topic'];
            $Description=$_POST['Description'];
            $resutl=parent::save_topic_data(array('EventId'=>$event_id,'Topic'=>$Topic,'Description'=>$Description));
            if($resutl){
                $message="<h2 style='color:green'>Save Successfully Data !!</h2>";
            }else{
                $message="<h2 style='color:red;'>Save Unsuccessfully Data !!</h2>";
            }
        }

        $outPut .='<form method="post"><div id="addTopicBox" class="box grid_6" style="margin-bottom: 5px; display: block;">
			<div id="tpcHead" class="box-head" style="background: rgb(84, 102, 102);"><h2 style="color: #fff;padding: 15px;">Add Event Topic <i class="fugue-2 cross-button" id="tpcClose"></i></h2></div>
            <div class="box-content">
                 <div>'.$message.'</div>
                <input type="hidden" name="" id="" value="">
                <div class="form-row">
                    <div class="form-label">Topic</div>
                    <div class="form-item"><input name="Topic" type="text" id="" style="width:90%;"></div>
                </div>
                <div class="form-row">
                    <div class="form-label">Description</div>
                    <div class="form-item"><textarea name="Description" rows="6" cols="20" id="" style="width:90%;"></textarea></div>
                </div>
                <div class="form-row">
                    <input type="submit" name="SaveTopic" value="Save Topic" id="SaveTopic" class="button big wbblue btnSvTpc">
                    <input type="submit" name="UpdateTopic" value="Update Topic" id="UpdateTopic" class="button big wbblue" style="display: none;">
                    <input type="submit" name="DeleteTopic" value="Delete Topic" id="DeleteTopic" class="button big wbblue">
                </div>
                <div class="form-row">
                    <div style="color:red;"></div>
                </div>
             </div>
        </div></form>';

        $outPut .="<script>
                  jQuery(function (){
                      jQuery('#addTopicBox').hide();
                      
                      jQuery('#addTopic').click(function (){
                       
                           jQuery('#addTopicBox').show();
                      });
                  });
            </script> ";
        return $outPut;
    }


}

new ScheduleRequestInformation();