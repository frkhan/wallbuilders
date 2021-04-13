<?php
class EventFunctons{
private static function authorized(){
    $nonce ='';
    if ( isset( $_REQUEST['_wpnonce'] ) ) {
        $nonce = $_REQUEST['_wpnonce'];
    } elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
        $nonce = $_SERVER['HTTP_X_WP_NONCE'];
    }
    return wp_verify_nonce( $nonce, 'wp_rest' );

  }
    /*******************Event Section***********************/

    public static function save_eventsInformation_data(){
    if(self::authorized()){

        $postdata = file_get_contents("php://input");
        $eventData = json_decode($postdata);

        $eventDataPost=$eventData->post;
        $eventDataPostMeta=$eventData->postMeta;

        $post_title= $eventDataPost[0]->eventsInfoName;
        $post_content= $eventDataPost[1]->eventsInfoDescriptionAudience;


        $user_id=get_current_user_id();


        $get_user = new WP_User( $user_id );
        $get_user->add_role( "scheduler_speaker" );

        $data = array(
            'post_author'           => $user_id,
            'post_content'          => $post_content,
            'post_title'            => $post_title,
            'post_status'            => 'publish',
            'post_type'             => 'smdn_eventschedule',
        );

        $post_id= wp_insert_post($data);
//        $loop = new WP_Query( array(
//           'ID'           => $post_id,
//           'post_content'          => $eventDataPost[1]->descriptionAudience,
//           'post_title'            => $eventDataPost[0]->name,
//           'post_type'             => 'smdn_eventschedule',
//
//       ));
        foreach ($eventDataPostMeta as $value){
          foreach($value as $name=>$data){
             update_post_meta($post_id,$name,$data);
         }

        }
        if($post_id){
                $data='Save Successfuly';
                return   rest_ensure_response(array('status'=>$data));
              }
            }

  }
    public static function save_contactsInformation_data(){
    if(self::authorized()){

        $postdata = file_get_contents("php://input");
        $eventData = json_decode($postdata);

        $eventDataUser=$eventData->user;
        $eventDataUserMeta=$eventData->userMeta;

        $user_login=sanitize_text_field($eventDataUser[0]->ContactsFirstName);
        $user_last_name=sanitize_text_field($eventDataUser[1]->ContactsLastName);
        $user_email=sanitize_email($eventDataUser[2]->ContactsEmailAddress);
        $FF=email_exists($user_email);
        if (email_exists($user_email) == false ) {

            $user_pass = wp_generate_password( 100, $user_email );
            $userdata = array(
                'user_login' => esc_attr($user_login),
                'user_email' => esc_attr($user_email),
                'user_pass' => esc_attr($user_pass),
                'first_name' => esc_attr($user_login),
                'last_name' => esc_attr($user_last_name),
                'display_name' => esc_attr($user_login.' '.$user_last_name),
            );


            $register_user_id = wp_insert_user($userdata);
            $get_user_role = new WP_User( $register_user_id );
            $get_user_role->add_role('scheduler_contact');
            wp_set_password( $user_email, $register_user_id );


            foreach ($eventDataUserMeta as $value){
                foreach($value as $name=>$data){
                    $posts= update_user_meta($register_user_id,$name,$data);
                }

            }
            if (!is_wp_error($register_user_id)) {
                wp_set_current_user( $register_user_id, $user_login );
                wp_set_auth_cookie( $register_user_id );
                //wp_redirect(home_url('/'));
                // exit;

            }
            $data='Save Successfuly';
            self::samadhan_sent_email($user_login,$user_email);


         } else {

            $user_id=get_current_user_id();
            if($user_id){
            foreach ($eventDataUserMeta as $value){

                foreach($value as $name=>$data){
                    $posts= add_user_meta($user_id,$name,$data);
                }

              }
                $data='Save Successfuly';
            }else{
                $posts=true;
                $data='Please Login ...';
            }
        }


        if($posts){

            return   rest_ensure_response(array('status'=>$data));
        }

    }

  }
    public static function save_leadersInformation_data(){
    if(self::authorized()){

        $postdata = file_get_contents("php://input");
        $eventData = json_decode($postdata);

        $eventDataUser=$eventData->user;
        $eventDataUserMeta=$eventData->userMeta;

        $user_login=sanitize_text_field($eventDataUser[0]->LeadersFirstName);
        $user_last_name=sanitize_text_field($eventDataUser[1]->LeadersLastName);
        $user_email=sanitize_email($eventDataUser[2]->LeadersEmailAddress);

        if (email_exists($user_email) == false ) {

            $user_pass = wp_generate_password( $length = 12, $include_standard_special_chars = false );

            $userdata = array(
                'user_login' => esc_attr($user_login),
                'user_email' => esc_attr($user_email),
                'user_pass' => esc_attr($user_pass),
                'first_name' => esc_attr($user_login),
                'last_name' => esc_attr($user_last_name),
                'display_name' => esc_attr($user_login.' '.$user_last_name),
            );


            $register_user_id = wp_insert_user($userdata);

            $get_user_role = new WP_User( $register_user_id );
            $get_user_role->add_role('scheduler_contact');
            wp_set_password( $user_email, $register_user_id );

            if (!is_wp_error($register_user_id)) {
                wp_set_current_user( $register_user_id, $user_login );
                wp_set_auth_cookie( $register_user_id );
               // wp_redirect(site_url('/'));
               // exit;

            }

            foreach ($eventDataUserMeta as $value){
                foreach($value as $name=>$data){
                    $posts= update_user_meta($register_user_id,$name,$data);
                }

            }

            $data='Save Successfuly';
            self::samadhan_sent_email($user_login,$user_email);
        } else {

            $user_id=get_current_user_id();
            if($user_id){
                foreach ($eventDataUserMeta as $value){

                    foreach($value as $name=>$data){
                        $posts= add_user_meta($user_id,$name,$data);
                    }

                }
                $data='Save Successfuly';
            }else{
                $posts=true;
                $data='Please Login ...';
            }
        }


        if($posts){

            return   rest_ensure_response(array('status'=>$data));
        }

    }

  }


    public static function samadhan_sent_email($user_login,$email){

      $the_blockName= get_bloginfo();
      $loginUrl=wp_login_url( home_url());
      //$to = get_option( 'admin_email' );
      $to = $email;
      $subject = 'New Create A Account';
       $body = "<p>Hi,$user_login </p> <p>Thanks for creating an account on $the_blockName.</p> 
         <p>Please login here $loginUrl.</p>
         <p><b>User Login :</b> $user_login.</p>
         <p><b>Password :</b> $email.</p>";
      $headers = array('Content-Type: text/html; charset=UTF-8');
      $headers .= 'From: '. $email . "\r\n" .
          'Reply-To: ' . $email . "\r\n";

      wp_mail( $to, $subject, $body, $headers );
  }

    public static function samadhan_get_all_manage_events_request_data(){
      global $wpdb;
      $postdata = file_get_contents("php://input");
      $eventData = json_decode($postdata);


      if(isset($eventData->searchData)) {
          $serarchValue = sanitize_text_field($eventData->searchData);
          $perPage = sanitize_text_field($eventData->perPage);
          $results=self::get_event_media_request_all_data($serarchValue,$perPage);
      }
      elseif(isset($eventData->EventsFilter)){
          $serarchValue=$eventData->EventsFilter;
          $results=self::get_event_media_request_all_data($serarchValue);

      }else{
          $results=self::get_event_media_request_all_data();
      }


      return rest_ensure_response($results);

  }
    public static function samadhan_get_all_event_request_data(){
      global $wpdb;
      $postdata = file_get_contents("php://input");
      $eventData = json_decode($postdata);

      $serarchValue=sanitize_text_field($eventData->searchData);
      $perPage=sanitize_text_field($eventData->perPage);
      if(isset($serarchValue)  && isset($perPage)){
          $data = array(
              'posts_per_page' => $perPage,
              'post_type'             => 'smdn_eventschedule',
              'offset'     => 0,
              's'          => $serarchValue,
          );
          $data=new WP_Query($data);
      }else{

          $data = array(
              'post_type'             => 'smdn_eventschedule',
          );
          $data=new WP_Query($data);
      }


      $getEvents=$data->posts;
      $totalEvents=$data->post_count;

      $allReports=array();
      foreach ($getEvents as $event){
          $event_id=$event->ID;
          $user_id=$event->post_author;
          $startDate=get_post_meta($event_id,'eventsInfoStartDate',true);
          $startTime=get_post_meta($event_id,'eventsInfoStartTime',true);
          $endDate=get_post_meta($event_id,'eventsInfoEndtDate',true);
          $endTime=get_post_meta($event_id,'eventsInfoEndTime',true);
         $eventDate=$startDate.' '.$startTime.' - '.$endDate.' '.$endTime;
         //var_dump($event);
          $eventCreateDate=$event->post_date;
          $eventTitle=$event->post_title;
          $allReports[]=array(
              'userId'=> $user_id,
              'createDate'=> $eventCreateDate,
              'eventName'=> $eventTitle,
              'type'=> get_post_meta($event_id,'eventsInfoEventType',true),
              'eventDate'=> $eventDate,
              'status'=> get_post_meta($event_id,'user_registered',true),
              'deadline'=> get_post_meta($event_id,'eventsInfoDeadline',true),
              'speaker'=> get_post_meta($event_id,'eventsInfoSpeakerPreference',true),
              'air'=> get_post_meta($event_id,'eventsInfoTravelToFromAirport',true),
              'firstName'=> get_user_meta($user_id,'LeadersFirstName',true),
              'lastName'=> get_user_meta($user_id,'LeadersLastName',true),
              'email'=> get_user_meta($user_id,'LeadersEmailAddress',true),
              'phone'=> get_user_meta($user_id,'LeadersPhone',true),
              'redFlag'=> get_user_meta($user_id,'LeadersState',true),
          );

      }

     // var_dump($allReports);
      return rest_ensure_response(array('allReports'=>$allReports,'totalEvent'=>$totalEvents));


  }

  /*******************Schedule Section***********************/

    public static function saveMediaRequestFormData(){
        if(self::authorized()){

            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);

            $mediaRequestId=$eventData->updateMediaRequestId->mediaRequestId;
            $MediaRequest=$eventData->MediaRequest;
            $eventDataUserMeta=$eventData->userMeta;
            $arrayMediaRequestData=array();

            foreach ($MediaRequest as $key=>$value){
                foreach ($value as $k=>$v){
                    $arrayMediaRequestData[$k]=$v;
                }
            }

            if(isset($mediaRequestId) && !empty($mediaRequestId)){
                $results=self::update_sch_media_request_by_media_request_id($mediaRequestId,$arrayMediaRequestData);
                if($results){
                    $data='Update Successfuly';
                    return   rest_ensure_response(array('status'=>$data));
                }
            }else{
                $results=self::save_sch_media_request($arrayMediaRequestData);

                if($results){
                    $data='Save Successfuly';
                    return   rest_ensure_response(array('status'=>$data));
                }
            }


        }

    }
    public static function getMediaRequestDataByRequestID(){
        if(self::authorized()){

            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);

            $RequestID=$eventData->media_request_id;

            $results=self::get_media_request_data_by_request_id($RequestID);


           return   rest_ensure_response($results);


        }

    }


    public static function saveCreateItineraryFormData(){
        if(self::authorized()){

            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);

            $eventDataUser=$eventData->user;
            $eventDataUserMeta=$eventData->userMeta;


                $user_id=get_current_user_id();
                if($user_id){
                    foreach ($eventDataUserMeta as $value){

                        foreach($value as $name=>$data){
                            $posts= add_user_meta($user_id,$name,$data);
                        }

                    }
                    $data='Save Successfully';
                }



            if($posts){

                return   rest_ensure_response(array('status'=>$data));
            }

        }

    }

    //************** Set Function**************//
    public static function saveAddSpeakerFormData(){
        if(self::authorized()){

            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);

            $eventDataUser=$eventData->speaker;
            $user_login=sanitize_text_field($eventDataUser[0]->FirstName);
            $user_last_name=sanitize_text_field($eventDataUser[1]->LastName);
            $user_nick_name=sanitize_text_field($eventDataUser[2]->Nickname);
            $user_email=sanitize_email($eventDataUser[3]->user_email);
            $update_speakerId=sanitize_text_field($eventDataUser[4]->update_speakerId);


            if(isset($update_speakerId) && !empty($update_speakerId)){

                $userdata=array(
                    'ID' => $update_speakerId,
                    'user_email' => esc_attr($user_email),
                ) ;
                $speakerData=array(
                    'Id' => esc_attr($update_speakerId),
                    'Nickname' => esc_attr($user_nick_name),
                    'FirstName' => esc_attr($user_login),
                    'LastName' => esc_attr($user_last_name),
                    'FullName' => esc_attr($user_login.' '.$user_last_name),

                );

                $register_user_id = wp_update_user($userdata);

                self::update_sch_speaker_by_speaker_id($update_speakerId,$speakerData);

                $data='Update Successfully';
                return   rest_ensure_response(array('status'=>$data));
            }else{

            if (!email_exists($user_email) ) {

                $user_pass = wp_generate_password( 100, $user_email );
                $userdata = array(
                    'user_login' => esc_attr($user_login),
                    'user_email' => esc_attr($user_email),
                    'user_pass' => esc_attr($user_pass),
                    'first_name' => esc_attr($user_login),
                    'last_name' => esc_attr($user_last_name),
                    'user_nicename' => esc_attr($user_nick_name),
                    'display_name' => esc_attr($user_login.' '.$user_last_name),
                );
               if(!username_exists($user_login)){


                $register_user_id = wp_insert_user($userdata);

                $get_user_role = new WP_User( $register_user_id );
                $get_user_role->add_role('Speaker');
                wp_set_password( $user_email, $register_user_id );

                $speakerData=array(
                    'Id' => esc_attr($register_user_id),
                    'Nickname' => esc_attr($user_nick_name),
                    'FirstName' => esc_attr($user_login),
                    'LastName' => esc_attr($user_last_name),
                    'FullName' => esc_attr($user_login.' '.$user_last_name),

                );

                $result=self::save_sch_speaker_data($speakerData);
                $data='Save Successfuly '.$user_email;
                //self::samadhan_sent_email($user_login,$user_email);
                   return   rest_ensure_response(array('status'=>$data));
               }else{
                   $data='Already exisiting  '.$user_login;
                   return   rest_ensure_response(array('status'=>$data));
               }

            }else{

                $data='Already exisiting  '.$user_email;
                return   rest_ensure_response(array('status'=>$data));
                }



           }
        }

    }
    public static function saveScheduleRequestFormData(){
        if(self::authorized()){

            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);


            $updateEventId=$eventData->updateEventId;

            $eventDataUser=$eventData->user;
            $eventDataUserMeta=$eventData->userMeta;
            $getEventData=$eventData->events;

            $getRequestData=$eventData->request;

if(!empty($updateEventId)){
            $arrayEventData=array();
            $arrayRequestData=array();
            foreach ($getEventData as $key=>$value){
                foreach ($value as $k=>$v){
                    $arrayEventData[$k]=$v;
                }
            }
            foreach ($getRequestData as $Rkey=>$Rvalue){
                foreach ($Rvalue as $Rk=>$Rv){
                    if($Rk=='EventId'){
                        $arrayRequestData[$Rk]=$updateEventId;
                    }else{
                        $arrayRequestData[$Rk]=$Rv;
                    }
                }
            }
    $updateData= self::updateEventDataById($updateEventId,$arrayEventData);
                 self::updateRequestDataById($updateEventId,$arrayRequestData);

      $message="Update Successully Data!!";

}else{


                    $eventName=$getEventData[0]->Event;
                    $getReports= self::get_event_media_request_all_data();
                    $existingEvent=$getReports['allReports'][0]->Event;
                    if($eventName!=$existingEvent or is_null($existingEvent)){
                    $arrayEventData=array();
                    $arrayRequestData=array();
                    foreach ($getEventData as $key=>$value){
                        foreach ($value as $k=>$v){
                            $arrayEventData[$k]=$v;
                        }
                    }
                    $event_id=self::save_sch_events_data($arrayEventData);

                    foreach ($getRequestData as $Rkey=>$Rvalue){
                        foreach ($Rvalue as $Rk=>$Rv){
                            if($Rk=='EventId'){
                                $arrayRequestData[$Rk]=$event_id;
                            }else{
                            $arrayRequestData[$Rk]=$Rv;
                            }
                        }
                    }

                    $request_id=self::save_sch_request_data($arrayRequestData);
                    $updateData=self::update_sch_events_data_by_request_id($event_id,$request_id);
                    $message="Save Successfully!!";
                }else{
                    $updateData=true;
                    $message="Already exising Event!!";
                }
            }
        }
            if($updateData){

                return   rest_ensure_response(array('status'=>$message));
            }
        }


    public static function save_sch_speaker_data($data=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_Speaker';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }



    public static function update_sch_speaker_by_speaker_id($speaker_id,$updatData){

        global $wpdb;

        $table_name = $wpdb->base_prefix.'SCH_Speaker';


        $where=array('Id'=>$speaker_id, );

        $where= $wpdb->update($table_name, $updatData, $where);
        $requst_id = $wpdb->insert_id;
        return $where;

    }

    public static function getEditEventDataById( ){
        global $wpdb;


        $table_name_event = $wpdb->base_prefix.'SCH_Event';
        $table_name_request = $wpdb->base_prefix.'SCH_Request';
        $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';



        $postdata = file_get_contents("php://input");
        $eventId= json_decode($postdata);
        $eventId = sanitize_text_field($eventId->EventId);

            $wpdb->query("SELECT *
                                FROM {$table_name_event} as e 
                                inner join {$table_name_request} as r on e.RequestId=r.Id
                                inner join {$table_name_speaker} as s on  s.Id=r.SpeakerRequestId 
                                where e.Id=$eventId ");
            return   rest_ensure_response(array('getEventData'=>$wpdb->last_result,'totalSpeaker'=>$wpdb->num_rows));




    }


    public static function updateRequestDataById($event_id,$updatData){

        global $wpdb;

        $table_name = $wpdb->base_prefix.'SCH_Request';


        $where=array('EventId'=>$event_id, );

        $where= $wpdb->update($table_name, $updatData, $where);
        $requst_id = $wpdb->insert_id;
        return $where;

    }

    public static function save_sch_events_data($data=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_Event';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }
    public static function save_sch_request_data($data=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_Request';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }
    public static function save_sch_media_request($data=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_MediaRequest';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }
    public static function update_sch_media_request_by_media_request_id($mediaRequstId,$updatData=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_MediaRequest';
        $where=array('Id'=>$mediaRequstId, );

        $where= $wpdb->update($table_name, $updatData, $where);

        return $where;

    }
    public static function update_sch_events_data_by_request_id($event_id,$RequestId){

        global $wpdb;

        $table_name = $wpdb->base_prefix.'SCH_Event';

        $updatData=array('RequestId'=>$RequestId);
        $where=array('Id'=>$event_id, );

        $where= $wpdb->update($table_name, $updatData, $where);
        $requst_id = $wpdb->insert_id;
        return $where;

    }

    //************** Get Function**************//
    public static function get_event_media_request_all_data($serarchValue='',$perPage=''){

      global $wpdb;
        $table_name_event = $wpdb->base_prefix.'SCH_Event';
        $table_name_request = $wpdb->base_prefix.'SCH_Request';
        $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';
        if(!empty($serarchValue)  && !empty($perPage)){
            $wpdb->query("SELECT  
                                     e.RequestId as RequestId,
                                     r.ContactDate  as ContactDate,
                                     e.Event  as Event,
                                     e.EventType as EventType,
                                     e.EventStartDate as EventStartDate,
                                     e.EventEndDate as EventEndDate,
                                     e.EventEndDate as Deadline,
                                     s.Nickname as Nickname,
                                     r.AltSpeaker as AltSpeaker,
                                     r.FirstName as FirstName,
                                     r.LastName as LastName,
                                     r.Email as Email,
                                     r.Phone1 as Phone1,
                                     e.RedFlagId as RedFlagId 
                                FROM {$table_name_event} as e 
                                inner join {$table_name_request} as r on e.RequestId=r.Id
                                inner join {$table_name_speaker} as s on  s.Id=r.SpeakerRequestId 
                                where e.Event like '%$serarchValue%' 
                                or s.Nickname like '%$serarchValue%' 
                                or r.FirstName like '%$serarchValue%' 
                                or r.LastName like '%$serarchValue%' 
                                or r.Phone1 like '%$serarchValue%' 
                                or e.Event like '%$serarchValue%' 
                             
                                ORDER BY e.Id DESC ");

            return array('allReports'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows);

        }elseif(is_array($serarchValue) && !empty($serarchValue)) {

            $Event = $serarchValue[0]->Event;
            $EventType = $serarchValue[1]->EventType;
            $Organization = $serarchValue[2]->Organization;
            $City = $serarchValue[3]->City;
            $State = $serarchValue[4]->State;
            $SpeakerRequestId = $serarchValue[5]->SpeakerRequestId;
            $Status = $serarchValue[6]->Status;
            $FirstName = $serarchValue[7]->FirstName;
            $LastName = $serarchValue[8]->LastName;
            $Email = $serarchValue[9]->Email;
            $ContactDate = $serarchValue[10]->ContactDate;


            $wpdb->query("SELECT  *
                                     
                                FROM {$table_name_event} as e 
                                inner join {$table_name_request} as r on e.RequestId=r.Id
                                inner join {$table_name_speaker} as s on  s.Id=r.SpeakerRequestId 
                                where e.Event like '%$Event%' 
                                or e.EventType like '%$EventType%' 
                                or r.Organization like '%$Organization%' 
                                or e.City like '%$City%' 
                                or e.State like '%$State%' 
                              
                                or r.FirstName like '%$FirstName%' 
                                or r.LastName like '%$LastName%' 
                                or r.Email like '%$Email%' 
                                or r.SpeakerRequestId like '%$SpeakerRequestId%' 
                                or r.ContactDate like '%$ContactDate%' 
                             
                                ORDER BY e.Id DESC ");

            return array('allReports'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows);

        }
        else{
            $wpdb->query("SELECT  
                                     e.RequestId as RequestId,
                                     r.ContactDate  as ContactDate,
                                     e.Event  as Event,
                                     e.EventType as EventType,
                                     e.EventStartDate as EventStartDate,
                                     e.EventEndDate as EventEndDate,
                                     e.EventEndDate as Deadline,
                                     s.Nickname as Nickname,
                                     r.AltSpeaker as AltSpeaker,
                                     r.FirstName as FirstName,
                                     r.LastName as LastName,
                                     r.Email as Email,
                                     r.Phone1 as Phone1,
                                     e.RedFlagId as RedFlagId 
                                     FROM {$table_name_event} as e inner join {$table_name_request} as r on e.RequestId=r.Id inner join {$table_name_speaker} as s on  s.Id=r.SpeakerRequestId ORDER BY e.Id DESC LIMIT 20");

            return array('allReports'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows);
        }




    }

    public static function getSpeakerDataBySpeakerId(){

      global $wpdb;

       $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';
       $table_name_users = $wpdb->base_prefix.'users';



        $postdata = file_get_contents("php://input");
        $speakerData = json_decode($postdata);
        $speakerId = sanitize_text_field($speakerData->SpeakerId);

            $wpdb->query("SELECT * FROM {$table_name_speaker} as s
                         inner join {$table_name_users} as u on u.ID=s.Id
                     where s.Id=$speakerId");
            return   rest_ensure_response(array('getSpeaker'=>$wpdb->last_result,'totalSpeaker'=>$wpdb->num_rows));

    }
    public static function getScheduleRickGreenTable(){

      global $wpdb;

       $table_name_request = $wpdb->base_prefix.'SCH_Request';
       $table_name_event = $wpdb->base_prefix.'SCH_Event';
       $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';

        $postdata = file_get_contents("php://input");
        $eventData = json_decode($postdata);
        $serarchValue = sanitize_text_field($eventData->searchData);
        $filterSpeaker = sanitize_text_field($eventData->SpeakerId);
        $perPage = sanitize_text_field($eventData->perPage);
        if(!is_array($serarchValue) && !empty($serarchValue)  && !empty($perPage)) {

            $wpdb->query("SELECT e.Id,e.Event,e.EventEndDate,e.EventType,r.Organization,r.City,r.State,r.FirstName,r.LastName,r.Phone1,s.FullName 
                                FROM {$table_name_request} as r 
                                inner join {$table_name_event} as e  on r.EventId=e.RequestId 
                                inner join  {$table_name_speaker} as s  on s.Id=r.SpeakerRequestId
                                  where e.Event like '%$serarchValue%' 
                                  or e.EventStartDate like '%$serarchValue%'
                                  or e.EventType like '%$serarchValue%' 
                                  or r.Organization like '%$serarchValue%'
                                  or r.City like '%$serarchValue%'
                                  or r.State like '%$serarchValue%'
                                  or r.FirstName like '%$serarchValue%'
                                  or r.Phone1 like '%$serarchValue%'
                                  or s.FirstName like '%$serarchValue%'
                                 
                                  ");
            return   rest_ensure_response(array('speakerGreen'=>$wpdb->last_result,'totalReports'=>$wpdb->num_rows));

        }elseif(isset($filterSpeaker) && !empty($filterSpeaker)){
            $wpdb->query("SELECT e.Id,e.Event,e.EventEndDate,e.EventType,r.Organization,r.City,r.State,r.FirstName,r.LastName,r.Phone1,s.FullName 
                                FROM {$table_name_request} as r 
                                inner join {$table_name_event} as e  on r.EventId=e.RequestId 
                                inner join  {$table_name_speaker} as s  on s.Id=r.SpeakerRequestId
                                  where 
                                 s.Id =$filterSpeaker
                                 
                                  ");
            return   rest_ensure_response(array('speakerGreen'=>$wpdb->last_result,'totalReports'=>$wpdb->num_rows));

        }else{
            $wpdb->query("SELECT e.Id,e.Event,e.EventEndDate,e.EventType,r.Organization,r.City,r.State,r.FirstName,r.LastName,r.Phone1,s.FullName 
                                FROM {$table_name_request} as r 
                                inner join {$table_name_event} as e  on r.EventId=e.RequestId 
                                inner join  {$table_name_speaker} as s  on s.Id=r.SpeakerRequestId
                                 ");
            return   rest_ensure_response(array('speakerGreen'=>$wpdb->last_result,'totalReports'=>$wpdb->num_rows));

        }




    }
    public static function get_media_request_report(){
        global $wpdb;
        $postdata = file_get_contents("php://input");
        $eventData = json_decode($postdata);
        if(isset($eventData->searchData)) {
            $serarchValue = sanitize_text_field($eventData->searchData);
            $perPage = sanitize_text_field($eventData->perPage);
            $results=self::get_media_request_all_data($serarchValue,$perPage);
        }
        elseif(isset($eventData->filterData)){
            $serarchValue=$eventData->filterData;
            $results=self::get_media_request_all_data($serarchValue);

        }else{
            $results=self::get_media_request_all_data();
        }


        return rest_ensure_response($results);



    }
    public static function get_media_request_all_data($serarchValue='',$perPage=''){

        global $wpdb;

        $table_name_MediaRequest = $wpdb->base_prefix.'SCH_MediaRequest';
        $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';
        if(!is_array($serarchValue) && !empty($serarchValue)  && !empty($perPage)){
            $wpdb->query("SELECT m.Id,m.InterviewDateTime,s.Nickname,m.ShowName,m.HostName,m.Topic,m.ShowType,m.MediaType,m.ContactName,m.Phone,m.Email,m.RedFlagId 
                                FROM {$table_name_MediaRequest} as m
                                inner join {$table_name_speaker} as s on m.SpeakerId=s.Id
                                where m.ShowName like '%$serarchValue%' 
                                or m.HostName like '%$serarchValue%' 
                                or m.ContactName like '%$serarchValue%' 
                                or m.Email like '%$serarchValue%' 
                                or m.Address like '%$serarchValue%' 
                                or m.InterviewDateTime like '%$serarchValue%' 
                                or s.Nickname like '%$serarchValue%' 
                                or m.Topic like '%$serarchValue%' 
                                or m.ShowType like '%$serarchValue%' 
                                or m.Phone like '%$serarchValue%' 
                                ORDER BY m.Id DESC ");

            return array('allReports'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows);

        }elseif(is_array($serarchValue) && !empty($serarchValue)){

            $ShowName=$serarchValue[0]->ShowName;
            $ShowType=$serarchValue[1]->ShowType;
            $HostName=$serarchValue[2]->HostName;
            $Topic=$serarchValue[3]->Topic;
            $MediaType=$serarchValue[4]->MediaType;
            $ContactName=$serarchValue[5]->ContactName;
            $Email=$serarchValue[6]->Email;
            $SpeakerId=$serarchValue[7]->SpeakerId;
            $InterviewDateTime=$serarchValue[8]->InterviewDateTime;


            $wpdb->query("SELECT m.Id,m.InterviewDateTime,s.Nickname,m.ShowName,m.HostName,m.Topic,m.ShowType,m.MediaType,m.ContactName,m.Phone,m.Email,m.RedFlagId 
                                FROM {$table_name_MediaRequest} as m
                                inner join {$table_name_speaker} as s on m.SpeakerId=s.Id
                                where 
                                 m.HostName like '%$HostName%'
                              or m.Topic like '%$Topic%'
                              or m.MediaType like '%$MediaType%'
                              or m.InterviewDateTime like '%$InterviewDateTime%'
                              or m.SpeakerId like '%$SpeakerId%'
                              or m.Email like '%$Email%'
                              or m.ContactName like '%$ContactName%'
                              or m.ShowType like '%$ShowType%'
                              or m.ShowName like '%$ShowName%'
                             
                                ORDER BY m.Id DESC ");

            return array('allReports'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows);

        }else{
            $wpdb->query("SELECT m.Id,m.InterviewDateTime,s.Nickname,m.ShowName,m.HostName,m.Topic,m.ShowType,m.MediaType,m.ContactName,m.Phone,m.Email,m.RedFlagId  FROM {$table_name_MediaRequest} as m inner join {$table_name_speaker} as s on s.Id=m.SpeakerId  ORDER BY m.Id DESC LIMIT 20");

            return array('allReports'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows);
        }




    }
    public static function get_media_request_data_by_request_id($requestID){

        global $wpdb;

        $table_name_MediaRequest = $wpdb->base_prefix.'SCH_MediaRequest';
       // $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';

            $wpdb->query("SELECT *  FROM {$table_name_MediaRequest}  where  Id=$requestID ORDER BY Id DESC ");

            return array('resutls'=>$wpdb->last_result,'totalEvent'=>$wpdb->num_rows,'status'=>'success');





    }



    /*******************Schedule Section***********************/


    public static function saveMajorDonorSubmitButtonForm(){
        if(self::authorized()){



            $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
            $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
            $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


            $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,true);


            $postdata = file_get_contents("php://input");
            $eventData = json_decode($postdata);

           // $majorDonor=$eventData->majorDonor;







            $FirstName=$eventData->FirstName;
            $LastName=$eventData->LastName;
            $Company=$eventData->Company;
            $Address1=$eventData->Address1;
            $Address2=$eventData->Address2;
            $City=$eventData->City;
            $State=$eventData->State;
            $Zip=$eventData->Zip;
            $Email=$eventData->Email;
            $Phone=$eventData->Phone;


            $id=$eventData->Id;
            $NopID=$eventData->NopID;
            $emailOption=$eventData->emailOption;
            $activeStatus=$eventData->activeStatus;
            $totalDonationsCount=$eventData->totalDonationsCount;
            $totalDonationAmount=$eventData->totalDonationAmount;
            $currentLevel=$eventData->currentLevel;
            $totalDonationCtLY=$eventData->totalDonationCtLY;
            $totalDonationAmtLY=$eventData->totalDonationAmtLY;
            $arrayMajorDonorData=array(
                'Id'=>$id,
                'NopID'=>$NopID,
                'FirstName'=>$FirstName,
                'LastName'=>$LastName,
                'Company'=>$Company,
                'Address1'=>$Address1,
                'Address2'=>$Address2,
                'City'=>$City,
                'State'=>$State,
                'Zip'=>$Zip,
                'Email'=>$Email,
                'emailOption'=>$emailOption,
                'activeStatus'=>$activeStatus,
                'totalDonationsCount'=>$totalDonationsCount,
                'totalDonationAmount' =>$totalDonationAmount,
                'currentLevel'=>$currentLevel,
                'totalDonationCtLY'=>$totalDonationCtLY,
                'totalDonationAmtLY'=>$totalDonationAmtLY,
                'Phone'=>$Phone
            );

            $customer_details = [
                'customer' => [
                    'email' => $Email,
                    'first_name' => $FirstName,
                    'last_name' => $LastName,
                    'username' => $FirstName.'.'.$LastName,
                    'billing_address' => [
                        'first_name' => $FirstName,
                        'last_name' => $LastName,
                        'company' => $Company,
                        'address_1' => $Address1,
                        'address_2' => $Address2,
                        'city' => $City,
                        'state' => $State,
                        'postcode' => $Zip,
                        'country' => 'US',
                        'email' => $Email,
                        'phone' =>$Phone
                    ]
                ]
            ];


            $results=self::save_MajorDonor_data($arrayMajorDonorData);
           $a_customer = $wc_api->create_customer($customer_details);
           $user_id= $a_customer->customere->id;
            if($user_id){
           update_user_meta($user_id,'_order_id',$NopID);
           update_user_meta($user_id,'_order_count',$totalDonationsCount);
           update_user_meta($user_id,'_money_spent',$totalDonationAmount);
           update_user_meta($user_id,'emailOption',$emailOption);
           update_user_meta($user_id,'activeStatus',$activeStatus);
           update_user_meta($user_id,'currentLevel',$currentLevel);
           update_user_meta($user_id,'totalDonationCtLY',$totalDonationCtLY);
           update_user_meta($user_id,'totalDonationAmtLY',$totalDonationAmtLY);
            }









            $data = "<p style='color:#e00d3f'>Unsuccessfuly Save Error</p>";
            if($a_customer){
                if($a_customer->customer->email){
                $data ="<p style='color:#0AB152'>Successfuly Save Data</p>";

                }
                if($a_customer->errors[0]->message){
                    $data ="Already Existing User Enrolled";

                }

                return   rest_ensure_response(array('status'=>$data));
            }

        }

    }

    public static function saveMajorDoner(){

        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


           // $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,false);


            $postdata = file_get_contents("php://input");
            $mejorDonorData = json_decode($postdata);

        $arrayMajorDonorData=array(
            'Id'=>0,
            'WooId'=>$mejorDonorData->WooId,
            'ConnectId'=>$mejorDonorData->ConnectId,
            'NopID'=>$mejorDonorData->NopID,
            'FirstName'=>$mejorDonorData->FirstName,
            'LastName'=>$mejorDonorData->LastName,
            'Company'=>$mejorDonorData->Company,
            'Address1'=>$mejorDonorData->Address1,
            'Address2'=>$mejorDonorData->Address2,
            'City'=>$mejorDonorData->City,
            'State'=>$mejorDonorData->State,
            'Zip'=>$mejorDonorData->Zip,
            'Email'=>$mejorDonorData->Email,
            'emailOption'=>$mejorDonorData->emailOption,
            'activeStatus'=>$mejorDonorData->activeStatus,
            'totalDonationsCount'=>$mejorDonorData->totalDonationsCount,
            'totalDonationAmount' =>$mejorDonorData->totalDonationAmount,
            'currentLevel'=>$mejorDonorData->currentLevel,
            'totalDonationCtLY'=>$mejorDonorData->totalDonationCtLY,
            'totalDonationAmtLY'=>$mejorDonorData->totalDonationAmtLY,
            'Phone'=>$mejorDonorData->Phone
        );

        $results=self::save_MajorDonor_data($arrayMajorDonorData);

        return   rest_ensure_response(array('status'=>$results));

    }
    public static function saveMajorDonationHistory(){


        $consumer_key =  get_option('SAMADHAN_STORE_CONSUMER_KEY');
        $consumer_secret =  get_option('SAMADHAN_STORE_CONSUMER_SECRET');
        $store_url = get_option('SAMADHAN_STORE_API_ENDPOINT');


           // $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url ,false);


            $postdata = file_get_contents("php://input");
            $mejorDonorData = json_decode($postdata);

            $arrayMajorDonorData=array(
                'Id'=>0,
                'WooId' =>$mejorDonorData->WooId,
                'ConnectId'=>$mejorDonorData->ConnectId,
                'MajorDonorID'=>$mejorDonorData->MajorDonorID,
                'Source'=>$mejorDonorData->Source,
                'OrderNumber'=>$mejorDonorData->OrderNumber,
                'OriginalCreateDate'=>$mejorDonorData->OriginalCreateDate,
                'ProductName'=>$mejorDonorData->ProductName,
                'Quantity'=>$mejorDonorData->Quantity,
                'UnitPrice'=>$mejorDonorData->UnitPrice,
                'TotalAmount'=>$mejorDonorData->TotalAmount,
                'FirstName'=>$mejorDonorData->FirstName,
                'LastName'=>$mejorDonorData->LastName,
                'Company'=>$mejorDonorData->Company,
                'Address1'=>$mejorDonorData->Address1,
                'Address2'=>$mejorDonorData->Address2,
                'City' =>$mejorDonorData->City,
                'State'=>$mejorDonorData->State,
                'Zip'=>$mejorDonorData->Zip,
                'Email'=>$mejorDonorData->Email,
                'Phone'=>$mejorDonorData->Phone,
                'OriginalClientId'=>$mejorDonorData->OriginalClientId
            );

            $results=self::save_MajorDonor_history_data($arrayMajorDonorData);
             return   rest_ensure_response(array('status'=>$results));
            //return   rest_ensure_response(array('status'=>$arrayMajorDonorData));

    }
    public static function saveMajorDonorNotes(){


            $postdata = file_get_contents("php://input");
            $mejorDonorNoteData = json_decode($postdata);

            $arrayMajorDonorData=array(
                'Id'=>0,
                'WooId' =>$mejorDonorNoteData->WooId,
                'MajorDonorId'=>$mejorDonorNoteData->MajorDonorId,
                'NopId'=>$mejorDonorNoteData->NopId,
                'CreateDate'=>$mejorDonorNoteData->CreateDate,
                'CreatedBy'=>$mejorDonorNoteData->CreatedBy,
                'ModifyDate'=>$mejorDonorNoteData->ModifyDate,
                'ModifiedBy'=>$mejorDonorNoteData->ModifiedBy,
                'Note'=>$mejorDonorNoteData->Note,

            );

             $results=self::save_MajorDonor_note_data($arrayMajorDonorData);
             return   rest_ensure_response(array('status'=>$results));


    }

    //************** Set Function**************//




    public static function save_MajorDonor_note_data($data=array()){

        global $wpdb;
        //$wpdb->show_errors = true;
        $table_name = $wpdb->base_prefix.'MajorDonorNotes';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;


    }




    public static function save_MajorDonor_history_data($data){


        global $wpdb;
        $table_name = $wpdb->base_prefix.'MajorDonorHistory';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }



    //**********All Customer User Delete**********//

    public static function all_customer_user_delete(){

        global $wpdb;
        $table_name = $wpdb->prefix.'wc_customer_lookup';
        $table_name_users = $wpdb->base_prefix.'users';
        $table_name_usersmeta = $wpdb->base_prefix.'usermeta';
        $results = $wpdb->get_results( "SELECT * FROM $table_name " );

        foreach ($results as $result){

            $user_meta = get_userdata($result->user_id );
            $user_roles = $user_meta->roles;

            if ( !in_array( 'administrator', $user_roles, true ) && in_array( 'customer', $user_roles, true )) {

                $wpdb->query( "delete FROM $table_name_users where id=".$result->user_id );
                $wpdb->query( "delete  FROM $table_name_usersmeta where user_id=".$result->user_id );
            }
           // $wpdb->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_wc_report_customers_%')" );
            $wpdb->query( "delete FROM $table_name where customer_id=".$result->customer_id );

      }
    }


    // ******************* Contact Form Paster ***********************//

    public  static function save_contact_paster_form_data($userdata,$userMetaData,$userRoll=''){

        if (!email_exists($userdata['user_email'])) {

               $register_user_id = wp_insert_user($userdata);
                $get_user_role = new WP_User( $register_user_id );
                $get_user_role->add_role($userRoll);
                wp_set_password( $userdata['user_email'], $register_user_id );

                foreach ($userMetaData as $metaKey=>$metaValue){
                    add_user_meta($register_user_id,$metaKey,$metaValue);
                }
                if($register_user_id){
                    $message="Successfully Added";
                }

        }else{
                $message="Already User Email Exists ";
        }

        return $message;
    }




    public static function update_contact_paster_form_data($user_id,$userMetaData){

        global $wpdb;

        if (email_exists($user_id['user_email'])) {

            $register_user_id = wp_update_user($user_id);
            $get_user_role = new WP_User( $register_user_id );

            wp_set_password( $user_id['user_email'], $register_user_id );

            foreach ($userMetaData as $metaKey=>$metaValue){
                update_user_meta($register_user_id,$metaKey,$metaValue);
            }
            if($register_user_id){
                $message="Updated Successfully";
            }

        }else{
            $message="Already User Updated ";
        }

        return $message;



    }







}

