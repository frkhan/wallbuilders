<?php

Class AIOGDPR_AdminSubjectAccessRequestAction extends AIOGDPR_AjaxAction{

    public $title = 'Subject Access Request';
    public $slug  = 'subject-access-request'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }
    

    protected $action = 'admin-subject-access-request';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_archive_form_description')){
            AIOGDPR_Settings::set('request_archive_form_description', $this->get('request_archive_form_description'));
        }

        AIOGDPR_Settings::set('sar_auto_respond', $this->get('sar_auto_respond', '0'));
        AIOGDPR_Settings::set('bcc_dpo', $this->get('bcc_dpo', '0'));
        AIOGDPR_Settings::set('dpo_notification_sar', $this->get('dpo_notification_sar', '0'));

    	$this->returnBack();
    }
}

AIOGDPR_AdminSubjectAccessRequestAction::listen();
