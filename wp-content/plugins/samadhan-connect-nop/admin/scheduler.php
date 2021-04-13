<?php
define( 'SAMADHAN_SCHEDULER_NAME', 'auto_course_release' );
define( 'SAMADHAN_SCHEDULER_HOOK', 'samadhan_auto_course_release_scheduler' );

add_action ( SAMADHAN_SCHEDULER_HOOK, 'samadhan_scheduled_function' );
add_filter('cron_schedules','samadhan_cron_schedules');

/* This function create a custom schedule timer for this plugin */
function samadhan_cron_schedules($schedules){
    $schedulesTimes= get_option( 'smdn_scheduler' );
    if($schedulesTimes){

        if(!isset($schedules[SAMADHAN_SCHEDULER_NAME])){
            $schedules[SAMADHAN_SCHEDULER_NAME] = array(
                'interval' => (int)$schedulesTimes * 60 ,
                'display' => __('Schedule for Automatic Course Release'));
        }

        return $schedules;
    }
}



if (!wp_next_scheduled(SAMADHAN_SCHEDULER_HOOK)) {
    wp_schedule_event( time(), SAMADHAN_SCHEDULER_NAME, SAMADHAN_SCHEDULER_HOOK );
}


function samadhan_scheduled_function() {
    $status = 'started';
    try {

        $processing_details =  Smdn_Subscription_Processing::processed_subscription_service();
        if(false !== $processing_details) {
            $status = 'success';
            $message =  $status;
        }
        else{
            $status = 'failed';
            $message = 'zero record updated!';
        }
    }
    catch (Exception $e){
        $message = $e->getMessage();
        $status = 'failed';
    }


    $admin_email = get_option( 'admin_email' );
    $mail_message = 'Automatic Course Allocation Processing <br/> Job status = ' . $status . '<br/><h2>Details about the job</h2><br/><hr/><br/>' . $processing_details;
    wp_mail( $admin_email, 'Automatic Course Allocation Processing Job Update', $mail_message);

}
