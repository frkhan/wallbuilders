<?php

Class AIOGDPR_UnsubscribeAdminAction extends AIOGDPR_AjaxAction{

    public $title = 'Unsubscribe';
    public $slug  = 'unsubscribe'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'aiogdpr-unsubscribe-admin';

    protected function run(){
        $this->requireAdmin();
       
        AIOGDPR_Settings::set('dpo_notification_unsubscribe', $this->get('dpo_notification_unsubscribe', '0'));

        if($this->has('unsubscribe_form_description')){
            AIOGDPR_Settings::set('unsubscribe_form_description', $this->get('unsubscribe_form_description'));
        }


    	$this->returnBack();
    }
}

AIOGDPR_UnsubscribeAdminAction::listen();
