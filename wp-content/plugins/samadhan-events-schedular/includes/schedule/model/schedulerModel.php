<?php
namespace Samadhan;

class SchedulerFunctions {




    public static function updateEventDataById($event_id,$updatData){

        global $wpdb;

        $table_name = $wpdb->base_prefix.'SCH_Event';


        $where=array('Id'=>$event_id, );

        $where= $wpdb->update($table_name, $updatData, $where);
        $requst_id = $wpdb->insert_id;
        return $where;

    }

    public static function getEventDataById( $event_id){

        global $wpdb;
        if(isset($event_id) && $event_id>0){

            $table_name_event = $wpdb->base_prefix.'SCH_Event';
            $table_name_request = $wpdb->base_prefix.'SCH_Request';
            $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';

            $wpdb->query("SELECT *
                                FROM {$table_name_event} as e
                                inner join {$table_name_request} as r on e.RequestId=r.Id
                                inner join {$table_name_speaker} as s on  s.Id=r.SpeakerRequestId
                                where e.Id=$event_id   ");
            return   $wpdb->last_result;


        }

    }

    public static function get_speaker_all_data(){

        global $wpdb;

        $table_name_speaker = $wpdb->base_prefix.'SCH_Speaker';



        $postdata = file_get_contents("php://input");
        $eventData = json_decode($postdata);
        $serarchValue = sanitize_text_field($eventData->searchData);
        $perPage = sanitize_text_field($eventData->perPage);
        if(!empty($eventData)){
            if(!is_array($serarchValue) && !empty($serarchValue)  && !empty($perPage)) {

                $wpdb->query("SELECT * FROM {$table_name_speaker} 
                                  where Id like '%$serarchValue%' 
                                  or Nickname like '%$serarchValue%'
                                  or FirstName like '%$serarchValue%' 
                                  or lastName like '%$serarchValue%'
                                  or FullName like '%$serarchValue%'
                                  ");
                return   rest_ensure_response(array('speaker'=>$wpdb->last_result,'totalSpeaker'=>$wpdb->num_rows));

            }
            elseif(is_array($serarchValue) && !empty($serarchValue)){

                $wpdb->query("SELECT * FROM {$table_name_speaker} 
                                   where Id like '%$serarchValue%' 
                                  or Nickname like '%$serarchValue%'
                                  or FirstName like '%$serarchValue%' 
                                  or lastName like '%$serarchValue%',
                                  or FullName like '%$serarchValue%'
                                  ");
                return   rest_ensure_response(array('speaker'=>$wpdb->last_result,'totalSpeaker'=>$wpdb->num_rows));

            }else{
                $wpdb->query("SELECT * FROM {$table_name_speaker} ");
                return   rest_ensure_response(array('speaker'=>$wpdb->last_result,'totalSpeaker'=>$wpdb->num_rows));

            }
        }else{
            $wpdb->query("SELECT * FROM {$table_name_speaker} ");
            return  array('speaker'=>$wpdb->last_result,'totalSpeaker'=>$wpdb->num_rows);
        }




    }

    public static function get_topic_data($searchName='',$page,$perPage){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_Topic';
        if(!empty($searchName)){
            $wpdb->query( "SELECT * FROM {$table_name}  where Topic like '%$searchName%'  or Description like '%$searchName%' LIMIT {$page},{$perPage}");

        }else{
            $wpdb->query( "SELECT * FROM {$table_name}  LIMIT {$page},{$perPage}");

        }
        $results = $wpdb->get_results("SELECT * FROM $table_name ");
        return array('allTopics'=>$wpdb->last_result,'totalTopics'=>count($results));

    }

    public static function save_topic_data($data=array()){

        global $wpdb;
        $table_name = $wpdb->base_prefix.'SCH_Topic';
        $wpdb->insert( $table_name, $data );
        return $wpdb->insert_id;

    }






}

new SchedulerFunctions();
