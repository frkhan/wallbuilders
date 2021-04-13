<?php

namespace Samadhan;

use WC_Countries;
use Samadhan\UserCRUD;

class ItineraryReport {

    public function __construct()
    {
        add_shortcode('smdn_itinerary_report','Samadhan\ItineraryReport::itinerary_form_report');
    }


    public static function itinerary_form_report()
    {
        //var_dump($_GET);
        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        global $wpdb;
        $table_name = $wpdb->prefix . "SCH_Itinerary";
        $ItineraryDate = $_GET['date'];
        $ItineraryDate = date("Y-m-d H:i:s", strtotime($ItineraryDate));
        $SpeakerId=$_GET['SelectSpeaker'];
        $retrieve_weather = $wpdb->get_results("SELECT * FROM $table_name where   ItineraryDate='$ItineraryDate' and SpeakerId='$SpeakerId'");
        $WeatherCity=' ';
        $WeatherState=' ';


        /* INPUT INTO ITINERARY TABLE the Weather Information  */



        if(isset($retrieve_weather[0]->WeatherCity)&&isset($retrieve_weather[0]->WeatherState))
        {

            $WeatherCity=$retrieve_weather[0]->WeatherCity;
            $WeatherState=$retrieve_weather[0]->WeatherState;
            if(isset($_POST['weather_button']))
            {
                global $wpdb;
                $table_name = $wpdb->prefix . "SCH_Itinerary";
                $WeatherCity=$_POST['WeatherCity'];
                $WeatherState=$_POST['WeatherState'];
                $wpdb->query($wpdb->prepare(
                    "UPDATE  $table_name
                   SET WeatherCity =' $WeatherCity ',WeatherState=' $WeatherState' where
                   ItineraryDate='$ItineraryDate' and SpeakerId='$SpeakerId'"

                ));
            }







        }
//        if(isset($retrieve_weather[0]->WeatherState))
//        {
//            $WeatherState=$retrieve_weather[0]->WeatherState;
//        }

        if(isset($_POST['weather_button'])&&$WeatherCity===' '&& $WeatherState===' ')
        {

            global $wpdb;
            $table_name = $wpdb->prefix . "SCH_Itinerary";
            $wpdb->insert(
                $table_name,
                array(
                    'SpeakerId' => $_GET['SelectSpeaker'],
                    'ItineraryDate' =>  $_GET['date'],
                    'WeatherCity'=>$_POST['WeatherCity'],
                    'WeatherState'=>$_POST['WeatherState'],
                    'CreateDate'=> $_GET['date']
                ));
            $WeatherCity=$_POST['WeatherCity'];
            $WeatherState=$_POST['WeatherState'];
        }


        /* END OF  ITINERARY TABLE the weather information details */

        /* INSERT THE data Into itinerary details table */

        if(isset($_POST['travel_aggrement']) && $_POST['travel_aggrement']==='addAggrement')
        {

            global $wpdb;
            $table_name = $wpdb->prefix . "SCH_Itinerary";
            $ItineraryDate = $_GET['date'];
            $ItineraryDate = date("Y-m-d H:i:s", strtotime($ItineraryDate));
            $retrieve_ItineraryId = $wpdb->get_results("SELECT Id FROM $table_name where   ItineraryDate='$ItineraryDate'");
            $retrieve_ItineraryId = $retrieve_ItineraryId[0]->Id;
            /* dataBase fields*/
            $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
            $DetailType = "Travel Arrangements";
            $Title = $_POST['travel_title'];
            $TextA = $_POST['arrangements'];
            $ItineraryId = $retrieve_ItineraryId;
            $CreateDate = $_GET['date'];
            $insetFlag='true';
            /* check the value in DB or Not */
            $checkTable= $wpdb->get_results("SELECT Title FROM $table_name where     ItineraryId ='$ItineraryId'");
            foreach ($checkTable as $checkTable)
            {
                if($checkTable->Title===$Title)
                {
                    $insetFlag='flase';
                }
            }
            //var_dump($insetFlag);
            if($insetFlag==='true')
            {
                $wpdb->query($wpdb->prepare(
                    "INSERT INTO $table_name
   (ItineraryId,DetailType,Title,TextA,CreateDate)
   values ('$ItineraryId', '$DetailType','$Title', '$TextA','$CreateDate'
         )"
                ));
            }
            unset($_POST);

        }
        if(isset($_POST['add_direction']) && $_POST['add_direction']==='addDirection')
        {
            global $wpdb;
            $table_name = $wpdb->prefix . "SCH_Itinerary";
            $ItineraryDate = $_GET['date'];
            $ItineraryDate = date("Y-m-d H:i:s", strtotime($ItineraryDate));
            $retrieve_ItineraryId = $wpdb->get_results("SELECT Id FROM $table_name where   ItineraryDate='$ItineraryDate'");
            $retrieve_ItineraryId = $retrieve_ItineraryId[0]->Id;
            /* dataBase fields*/
            $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
            $DetailType = "Directions";
            $Title = $_POST['direction_title'];
            $TextA = $_POST['direction_from'];
            $TextB = $_POST['direction_to'];
            $TextC = $_POST['directions'];
            $ItineraryId = $retrieve_ItineraryId;
            $CreateDate = $_GET['date'];
            $insetFlag='true';
            $checkTable= $wpdb->get_results("SELECT Title FROM $table_name where     ItineraryId ='$ItineraryId'");
            foreach ($checkTable as $checkTable)
            {
                if($checkTable->Title===$Title)
                {
                    $insetFlag='flase';
                }
            }
            //var_dump($insetFlag);
            if($insetFlag==='true')
            {
                $wpdb->query($wpdb->prepare(
                    "INSERT INTO $table_name
   (ItineraryId,DetailType,Title,TextA,TextB,TextC,CreateDate)
   values ('$ItineraryId', '$DetailType','$Title', '$TextA','$TextB','$TextC','$CreateDate'
         )"
                ));
            }

            unset($_POST);

        }
        if(isset($_POST['add_note']) && $_POST['add_note']==='addNote')
        {
            global $wpdb;
            $table_name = $wpdb->prefix . "SCH_Itinerary";
            $ItineraryDate = $_GET['date'];
            $ItineraryDate = date("Y-m-d H:i:s", strtotime($ItineraryDate));
            $retrieve_ItineraryId = $wpdb->get_results("SELECT Id FROM $table_name where   ItineraryDate='$ItineraryDate'");
            $retrieve_ItineraryId = $retrieve_ItineraryId[0]->Id;
            /* dataBase fields*/
            $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
            $DetailType = "Notes";
            $Title = $_POST['note_title'];
            $TextA = $_POST['notes'];
            $ItineraryId = $retrieve_ItineraryId;
            $CreateDate = $_GET['date'];
            $insetFlag='true';
            $checkTable= $wpdb->get_results("SELECT Title FROM $table_name where     ItineraryId ='$ItineraryId'");
            foreach ($checkTable as $checkTable)
            {
                if($checkTable->Title===$Title)
                {
                    $insetFlag='flase';
                }
            }
            //var_dump($insetFlag);
            if($insetFlag==='true')
            {
                $wpdb->query($wpdb->prepare(
                    "INSERT INTO $table_name
   (ItineraryId,DetailType,Title,TextA,CreateDate)
   values ('$ItineraryId', '$DetailType','$Title', '$TextA','$CreateDate'
         )"
                ));
            }


            unset($_POST);
        }

        /* END OF INSERTION THE data Into itinerary details table */


        /* START OF UPDATING THE itinerary details table */
        if(isset($_POST['update_aggrement']))
        {
            $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
            $Title = $_POST['travel_title_update'];
            $TextA = $_POST['arrangements_update'];
            $UpdateID=$_POST['update_aggrement_id'];
            $wpdb->query($wpdb->prepare(
                "UPDATE  $table_name
                   SET Title =' $Title ',TextA=' $TextA' where
                   Id='$UpdateID'"

            ));

        }

        if(isset($_POST['update_direction']))
        {
            $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
            $Title = $_POST['direction_title_update'];
            $TextA = $_POST['direction_from_update'];
            $TextB = $_POST['direction_to_update'];
            $TextC = $_POST['directions_update'];
            $UpdateID=$_POST['update_direction_id'];
            $wpdb->query($wpdb->prepare(
                "UPDATE  $table_name
                   SET Title =' $Title ',TextA=' $TextA',TextB='$TextB',TextC='$TextC' where
                   Id='$UpdateID'"

            ));

        }
        if(isset($_POST['update_note']))
        {
            $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
            $Title = $_POST['note_title_update'];
            $TextA = $_POST['notes_update'];
            $UpdateID=$_POST['update_note_id'];
            $wpdb->query($wpdb->prepare(
                "UPDATE  $table_name
                   SET Title =' $Title ',TextA=' $TextA' where
                   Id='$UpdateID'"

            ));

        }



        /* SEQUENCE GENERATION CODE AGAINEST SPECFIC ItineraryId*/

        global $wpdb;
        $table_name = $wpdb->prefix . "SCH_ItineraryDetails";
        $ItineraryDate =  $_GET['date'];
        $ItineraryDate = date("Y-m-d H:i:s",strtotime($ItineraryDate));

        $retrieve_ItineraryId = $wpdb->get_results("SELECT  ItineraryId from $table_name WHERE CreateDate ='$ItineraryDate'");

        $retrieve_ItineraryId=$retrieve_ItineraryId[0]->ItineraryId;

        $retrieve_Id = $wpdb->get_results("SELECT Id FROM $table_name where    ItineraryId ='$retrieve_ItineraryId'");

        $sequence=' ';
        $i=0;
        foreach ($retrieve_Id as $retrieve_Id)
        {
            //echo (int)($retrieve_Id->Id).'<br>';
            $sequence=(int)($retrieve_Id->Id);
            $i++;
            $wpdb->query($wpdb->prepare(
                "UPDATE  $table_name
                   SET Sequence='$i',ModifyDate=' $ItineraryDate' where
                   Id='$sequence'"

            ));

        }

        /*  END OF SEQUENCE GENERATION CODE AGAINEST ItineraryId  */

        /* THE First TABLE DATA OUTPUTTING */

        if($retrieve_weather)
        {
            $retrieve_details= $wpdb->get_results("SELECT * FROM $table_name where     ItineraryId ='$retrieve_ItineraryId'");
        }

        $table='<table>';
        $table .='<thead>';
        $table .='<tr>';
        $table .='<th>Seq</th>';
        $table .='<th>Title</th>';
        $table .=' <th>Type</th>';
        $table .='</tr>';
        $table .='</thead>';
        $table .='<tbody>';
        $updateButtonId=0;
        foreach ($retrieve_details as $details)
        {
            $toUpdateId=$details->Id;
            $toUpdateTitle=$details->Title;
            $toUpdateTextA=$details->TextA;
            $toUpdateTextB=$details->TextB;
            $toUpdateTextC=$details->TextC;
            $SID=$details->DetailType.$details->Id;
            $passInfo=$toUpdateTitle.','.$toUpdateTextA.','.$toUpdateTextB.' ,'.$toUpdateTextC;
            if($details->DetailType==='Travel Arrangements')
            {
                $updateButtonId=1;
            }
            if($details->DetailType==='Directions')
            {
                $updateButtonId=2;
            }
            if($details->DetailType==='Notes')
            {
                $updateButtonId=3;
            }
            $table .='<tr>';
            $table .='<td>';
            $table .=$details->Sequence;
            $table .='</td>';
            $table .= '<td><input type="hidden" value="'.$passInfo.'" id="'.$SID.'">

            <a href="#"  onclick="updateButton('.$updateButtonId.','.$details->Id.')" >';
            $table .= $details->Title;
            $table .= '</a></td>';
            $table .='<td>';
            $table .=$details->DetailType;
            $table .='</td>';
            $table .='</tr>';
        }
        $table .='</tbody>';
        $table .='</table>';


        /* Next Day and Previous Day */

        $startDate=$_GET['date'];

        $SelectSpeaker=$_GET['SelectSpeaker'];
        $PreviousDate = date ("Y-m-d", strtotime("-1 day", strtotime($startDate)));
        $nextDate=date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
        $linkPreviousDate=home_url( '/itinerary_form_report?date='.$PreviousDate.'&SelectSpeaker='.$SelectSpeaker );
        $linknextDate=home_url( '/itinerary_form_report?date='.$nextDate.'&SelectSpeaker='.$SelectSpeaker );



        /* END OF THE First TABLE DATA OUTPUTTING  */



        /* STARTING THE ALL FORM WITH JQUERY */

        $form= '
    
      <style>
    #row1{
    display:flex;
    flex-direction:row;
    width: 100%;
}

#column1{
    display:flex;
    flex-direction:column;
    width: 80%;
}
.headerSection{
    background-color: #0274be;
    margin-bottom: 0px;
    display: block;
    color: #ffffff;
    padding: 4px 15px;
}
.closeButton{
    float: right;
    position: relative;
    top: -31px;
    right: 4px;
    background: white;
    width: 24px;
    padding: 0 8px;
}
#column2{
    display:flex;
    flex-direction:column;
    width:21%;
    margin-left: 20px;
}
         }
       </style>
  <div id="row1">
    <div id="column1">
    <div style="background-color: #0274be"><h3 style="padding: 4px 15px;color: #fff;">'.$_GET["date"].'</h3></div>
     
         <div>
                       ' . $table . '  
                    </div>
                        <div>
                       
                    
                    <a href="'.$linkPreviousDate.'"><button class="smdn-type" >previous day</button></a>
                    
                    <button class="smdn-type" id="Add_Travel_Arrangements_1"> Add Travel Arrangements </button>
                    <button class="smdn-type" id="Add_Directions_button"> Add Directions </button>
                    <button class="smdn-type" id="Add_Notes_button" > Add Notes  </button>
                    <a href="'.$linknextDate.'"><button class="smdn-type" >Next day</button></a>
                    <button class="smdn-type" style="margin-top: 5px"> print </button>
                   
                </div>
                <div></div>
                   <div id="Add_Travel_Arrangements" style="margin-top: 20px">
                  <span  class="headerSection"  >Add Travel Arrangements  </span>
                   <span class="smdn-type closeButton" onclick="closeButton(1)">X</span>
                   <div>
                   <form method="post">
                  <table style="border: none">
                  <tr>
                  <td>Title</td>
                  <td><input type="text" style="width:100%;" name="travel_title"></td>
                </tr>
                  <td>Arrangements</td>
                  <td><textarea style="border: none" name="arrangements"></textarea></td>
                </tr>
                </table>
                <button class="smdn-type" name="travel_aggrement" value="addAggrement" >add</button>
                </form>
                   </div>
                   
                  </div>
                    <div id="Add_Directions_header" style="margin-top: 20px">
                     <span  class="headerSection"  >Add Directions   </span>
                     <span class="smdn-type closeButton" onclick="closeButton(2)">X</span>
           
                    <form method="post">
                     <table style="border: none">
                  <tr>
                  <td>Title</td>
                  <td><input type="text" style="width:100%"; name="direction_title"></td>
                 </tr>
                 <tr>
                  <td>From</td>
                  <td><input type="text" style="width:100%"; name="direction_from"></td>
                 </tr>
                 <tr>
                  <td>To</td>
                  <td><input type="text" style="width:100%;" name="direction_to"></td>
                 </tr>
                  <td>Directions</td>
                  <td><textarea style="border: none" name="directions"></textarea></td>
                </tr>
                </table>
                <button class="smdn-type" name="add_direction" value="addDirection">add</button>
                 </form>
                  </div>  
                      <div id="Add_Notes_header" style="margin-top: 20px">
                         <span  class="headerSection"  >Add Notes </span>
                         <span class="smdn-type closeButton" onclick="closeButton(3)" >X</span>
                
                   <form method="post">
                  <table style="border: none">
                  <tr>
                  <td>Title</td>
                  <td><input type="text" style="width:100%;" name="note_title"></td>
                 </tr>
                 </tr>
                  <td>Notes</td>
                  <td><textarea style="border: none" name="notes"></textarea></td>
                </tr>
                </table>
                   <button class="smdn-type" name="add_note" value="addNote">add</button>
                   </form>
                  </div>               
                   </div>
                   
                    <div id="column2">
                    <form method="post">
                    <div id="Weather Location">
                  <span style="background-color: #0274be;margin-bottom: 0px;display: block;color: #ffffff;padding:4px 15px;" >Weather Location                                
                 </span>
                  <table style="border: none">
                  <tr>
                  <td>City</td>
                  <td><input type="text" style="width:100%;" name="WeatherCity" value="'.$WeatherCity.'"></td>
                 </tr>
                 </tr>
                  <td>State</td>
                  <td>     <select  id="" name="WeatherState">
                      
   <option value="'.$WeatherState.' selected="selected"">'.$WeatherState.'</option> 
   <option value="Alaska">Alaska</option>
   <option value="Alabama">Alabama</option>
   <option value="Arkansas">Arkansas</option>
   <option value="American Samoa">American Samoa</option>
   <option value="Arizona">Arizona</option>
   <option value="California">California</option>
   <option value="Colorado">Colorado</option>
   <option value="Connecticut">Connecticut</option>
   <option value="District of Columbia">District of Columbia</option>
   <option value="Delaware">Delaware</option>
   <option value="Florida">Florida</option>
   <option value="Georgia">Georgia</option>
    <option value="Guam">Guam</option>
   <option value="Hawaii">Hawaii</option>
   <option value="Iowa">Iowa</option>
   <option value="Idaho">Idaho</option>
   <option value="Illinois">Illinois</option>
   <option value="Indiana">Indiana</option>
   <option value="Kansas">Kansas</option>
   <option value="Kentucky">Kentucky</option>
   <option value="Louisiana">Louisiana</option>
   <option value="Massachusetts">Massachusetts</option>
   <option value="Maryland">Maryland</option>
   <option value="Maine">Maine</option>
   <option value="Michigan">Michigan</option>
   <option value="Minnesota">Minnesota</option>
   <option value="Missouri">Missouri</option>
   <option value="Mississippi">Mississippi</option>
   <option value="Montana">Montana</option>
   <option value="North Carolina">North Carolina/option>
   <option value="North Dakota">North Dakota</option>
   <option value="Nebraska">Nebraska</option>
   <option value="New Hampshire">New Hampshire</option>
   <option value="New Jersey">New Jersey</option>
   <option value="New Mexico">New Mexico</option>
   <option value="Nevada">Nevada</option>
    <option value="New York">New York</option>
   <option value="Ohio">Ohio</option>
   <option value="Oklahoma">Oklahoma</option>
   <option value="Oregon">Oregon</option>
   <option value="Pennsylvania">Pennsylvania</option>
   <option value="Puerto Rico">Puerto Rico/option>
   <option value="Rhode Island">Rhode Island</option>
   <option value="South Carolina">South Carolina/option>
   <option value="South Dakota">South Dakota</option>
   <option value="Tennessee">Tennessee</option>
   <option value="Texas">Texas</option>
   <option value="Utah">Utah</option>
   <option value="Virginia">Virginia</option>
   <option value="Virgin Islands">Virgin Islands</option>
   <option value="Vermont">Vermont</option>
     <option value="Washington">Washington</option>
      <option value="Wisconsin">Wisconsin</option>       
      <option value="West Virginia">West Virginia/option>  
      <option value="Wyoming">Wyoming/option>          
                        </select></td>
                </tr>
                </table>
                 <button class="smdn-type" style="margin-bottom: 5px" name="weather_button">Update</button>
                   <span style="background-color: #0274be;margin-bottom: 0px;display: block;color: #ffffff;padding:4px 15px;">Scheduled Events for the Day</span>
                  </div> 
                    
                </div>
                </form>
                </div>
                
                
                
                
                
                 <div id="update_Travel_Arrangements" style="margin-top: 20px">
                  <span  class="headerSection"  >update Travel Arrangements  </span>
                   <span class="smdn-type closeButton" onclick="closeButton(1)">X</span>
                   <div>
                   <form method="post">
                  <table style="border: none">
                  <tr>
                  <td>Title</td>
                  <td><input type="text" style="width:100%;" name="travel_title_update" id="toUpTitle" value=""></td>
                </tr>
                  <td>Arrangements</td>
                  <td><textarea style="border: none" name="arrangements_update" id="toUpTextA"></textarea></td>
                </tr>
                </table>
                <input type="hidden" name="update_aggrement_id" id="update_aggrement_id">
                <button class="smdn-type" name="update_aggrement" value="addAggrement" >update</button>
                </form>
                   </div>
                   
                  </div>   
                 
                    <div id="update_Directions_header" style="margin-top: 20px">
                     <span  class="headerSection"  >update Directions   </span>
                     <span class="smdn-type closeButton" onclick="closeButton(2)">X</span>
           
                    <form method="post">
                     <table style="border: none">
                  <tr>
                  <td>Title</td>
                  <td><input type="text" style="width:100%"; name="direction_title_update" id="updateDirectionTitle"></td>
                 </tr>
                 <tr>
                  <td>From</td>
                  <td><input type="text" style="width:100%"; name="direction_from_update" id="UpdateDirectionTextA"></td>
                 </tr>
                 <tr>
                  <td>To</td>
                  <td><input type="text" style="width:100%;" name="direction_to_update" id="UpdateDirectionTextB"></td>
                 </tr>
                  <td>Directions</td>
                  <td><textarea style="border: none" name="directions_update" id="UpdateDirectionTextC"></textarea></td>
                </tr>
                </table>
                <input type="hidden" name="update_direction_id" id="update_direction_id">
                <button class="smdn-type" name="update_direction" value="addDirection">update</button>
                 </form>
                  </div>   
                 
                    <div id="update_Notes_header" style="margin-top: 20px">
                         <span  class="headerSection"  >update Notes </span>
                         <span class="smdn-type closeButton" onclick="closeButton(3)" >X</span>
                
                   <form method="post">
                  <table style="border: none">
                  <tr>
                  <td>Title</td>
                  <td><input type="text" style="width:100%;" name="note_title_update" id="UpdateNoteTitle"></td>
                 </tr>
                 </tr>
                  <td>Notes</td>
                  <td><textarea style="border: none" name="notes_update" id="UpdateNoteTextA"></textarea></td>
                </tr>
                </table>
                <input type="hidden" id="update_note_id" name="update_note_id">
                   <button class="smdn-type" name="update_note" value="addNote">update</button>
                   </form>
                  </div>
                  
                  
                     ';
        $form .="<script>
              
                function updateButton(id,id2){
                         if(id==1){
                            
                              /* jQuery('#update_Travel_Arrangements').show(); */
                             jQuery('#update_Travel_Arrangements').show();
                            
                             console.log(id2);
                             var IdUpdateTitle='Travel Arrangements'+id2;
                             var UpdateTitle=document.getElementById(IdUpdateTitle).value;
                             console.log(UpdateTitle);
                             var stringArray = UpdateTitle.split(',');
                             console.log(stringArray);
                              jQuery('#update_aggrement_id').val(id2);
                             jQuery('#toUpTitle').val(stringArray[0]);
                             jQuery('#toUpTextA').val(stringArray[1]);
                             
                         }
                        if(id==2){
                              jQuery('#update_Directions_header').show(); 
                              console.log(id2);
                                var IdUpdateTitle='Directions'+id2;
                              var UpdateTitle=document.getElementById(IdUpdateTitle).value;
                              console.log(UpdateTitle);
                              var stringArray = UpdateTitle.split(',');
                              console.log(stringArray);
                              jQuery('#update_direction_id').val(id2);
                              jQuery('#updateDirectionTitle').val(stringArray[0]);
                              jQuery('#UpdateDirectionTextA').val(stringArray[1]);
                              jQuery('#UpdateDirectionTextB').val(stringArray[2]);
                              jQuery('#UpdateDirectionTextC').val(stringArray[3]);
                              
                         }
                         if(id==3){
                             
                              jQuery('#update_Notes_header').show(); 
                              console.log(id2);
                              var IdUpdateTitle='Notes'+id2;
                              var UpdateTitle=document.getElementById(IdUpdateTitle).value;
                              console.log(UpdateTitle);
                              var stringArray = UpdateTitle.split(',');
                              console.log(stringArray);
                              jQuery('#update_note_id').val(id2);
                              jQuery('#UpdateNoteTitle').val(stringArray[0]);
                              jQuery('#UpdateNoteTextA').val(stringArray[1]);
                              
                         }
                         
                     }
                function closeButton(id){
                         if(id==1){
                              jQuery('#Add_Travel_Arrangements').hide();
                             jQuery('#update_Travel_Arrangements').hide();
                         }
                        if(id==2){
                              jQuery('#Add_Directions_header').hide();
                               jQuery('#update_Directions_header').hide(); 
                         }
                         if(id==3){
                              jQuery('#Add_Notes_header').hide();
                               jQuery('#update_Notes_header').hide(); 
                         }
                         
                     }
                 jQuery(function (){
                      jQuery('#Add_Directions_header').hide();
                     jQuery('#Add_Notes_header').hide();
                     jQuery('#Add_Travel_Arrangements').hide();
                     
                     jQuery('#Add_Travel_Arrangements_1').click(function (){
                          
                         jQuery('#Add_Travel_Arrangements').show();
                          
                     });
                     jQuery('#Add_Directions_button').click(function (){
                          jQuery('#Add_Directions_header').show();
                            
                     });
                     jQuery('#Add_Notes_button').click(function (){
                          jQuery('#Add_Notes_header').show();
                          
                     });
                      jQuery('#update_Travel_Arrangements').hide();
                     jQuery('#update_Directions_header').hide();
                     jQuery('#update_Notes_header').hide(); 
                      
                  
                     
                    
                     
                     
                 });
               </script>";


        /* END OF  THE ALL FORM WITH JQUERY */


        return $form;


    }



}

new ItineraryReport();