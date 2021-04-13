<?php
namespace Samadhan;
use Samadhan\UserCRUD;

class CreateItinerary extends SchedulerFunctions {

    public function __construct()
    {
        add_shortcode('smdn_create_itinerary_form','Samadhan\CreateItinerary::get_create_itinerary_form');
    }


    public  static function get_create_itinerary_form(){


        if(UserCRUD::is_unauthorized()){
            return UserCRUD::unauthorized_message();
        }

        $calender='';
        $startDate=' ';
        $endDate=' ';
        $showType=' ';

        $getAllSpeaker= parent::get_speaker_all_data();
        $speakerOption="<option selected value=''>Select</option>";
        foreach ($getAllSpeaker['speaker'] as $key=>$speaker){
            if(!empty($_POST['SelectSpeaker']) && $_POST['SelectSpeaker']==$speaker->Id){
                $selected="selected";
            }else{
                $selected=" ";
            }
            $speakerOption .="<option $selected value='$speaker->Id'>$speaker->FirstName</option>";

        }

        if(isset($_POST['Submit']))
        {
            $startDate= $_POST['starDate'];
            $endDate= $_POST['endDate'];
            $SelectSpeaker= $_POST['SelectSpeaker'];

            $i=0;
            while (strtotime($startDate) < strtotime($endDate)) {
                $i++;
                if($i==1)
                {
                    $startDate = date ("Y-m-d", strtotime("+0 day", strtotime($startDate)));
                }
                else
                {
                    $startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
                }

                $link=home_url( '/itinerary-report?date='.$startDate.'&SelectSpeaker='.$SelectSpeaker );

                $calender .= '<a href="'.$link.'" ><button style="background-color: aquamarine !important;margin: 10px">' .$startDate. '</button></a>';
                if($i%3==0)
                    echo '<br/>';

            }

        }

        $formView =' <form method="post">
 
 <div id=""> <div class="smdn-form-group" >
        <h2 class="">Select a Start Date</h2>
   </div><hr/>
    <div class="smdn-form-group">
        <label class="smdn-form-label">Start Date</label>
        <input name="starDate" type="date"  class="smdn-type" id="smdn-type"  placeholder=" " value="'.$_POST['starDate'].'">
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label">End Date</label>
        <input name="endDate" type="date"  class="smdn-type" id="smdn-type" placeholder=" " value="'.$_POST['endDate'].'">
   </div>
    <div class="smdn-form-group">
        <label class="smdn-form-label">Select Speaker</label>
        <select  name="SelectSpeaker"   id="">
            '.$speakerOption.'
        
        </select>
        
   </div>
         <div class="smdn-form-group" style="    padding-left: 20px;">
                  
                    <input type="button" style="margin-right:10px; " class="smdn-type" id="smdn-type"  placeholder=" " value="Previous">
                    <input type="submit"  class="smdn-type" id="smdn-type"   placeholder=" " value="Submit" name="Submit">
               </div> </div>  </form>';

        $formView .= $calender;
        return $formView;

    }
}

new CreateItinerary();
