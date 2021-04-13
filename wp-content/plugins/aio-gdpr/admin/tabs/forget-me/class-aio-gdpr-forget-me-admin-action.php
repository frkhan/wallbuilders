<?php

Class AIOGDPR_ForgetMeAdminAction extends AIOGDPR_AjaxAction{

    public $title = 'Forget Me';
    public $slug  = 'forget-me'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'forget-me-admin';

    protected function run(){
        $this->requireAdmin();

        
        if($this->has('forget_me_form_description')){
            AIOGDPR_Settings::set('forget_me_form_description', $this->get('forget_me_form_description'));
        }
        
        AIOGDPR_Settings::set('dpo_notification_forget_me', $this->get('dpo_notification_forget_me', '0'));   


    	$this->returnBack();
    }
}

AIOGDPR_ForgetMeAdminAction::listen();
