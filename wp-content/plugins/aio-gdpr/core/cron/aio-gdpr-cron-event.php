<?php

Class AIOGDPR_CronEvent extends AIOGDPR_Cron{

    public $interval = array(
        'minutes'     => 2,
    );
    
    public function handle(){

        AIOGDPR_Settings::set('cron_last_run', time());

    }
}

AIOGDPR_CronEvent::register();