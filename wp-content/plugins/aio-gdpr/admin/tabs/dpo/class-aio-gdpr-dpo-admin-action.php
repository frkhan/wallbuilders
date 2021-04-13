<?php

Class AIOGDPR_DPOAdminAction extends AIOGDPR_AjaxAction{

    public $title = 'DPO';
    public $slug  = 'dpo';
    public $isHidden = TRUE; 
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'dpo-admin';

    protected function run(){
    	$this->requireAdmin();

        AIOGDPR_Settings::set('dpo_email', $this->get('email', ''));
        AIOGDPR_Settings::set('dpo_first_name', $this->get('first_name', ''));
        AIOGDPR_Settings::set('dpo_last_name', $this->get('last_name', ''));
        AIOGDPR_Settings::set('dpo_user_id', $this->get('dpo_user_id', ''));

        
        $this->returnBack();
    }
}

AIOGDPR_DPOAdminAction::listen();


