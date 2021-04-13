<?php

Class AIOGDPR_PrivacyCenterAction extends AIOGDPR_AjaxAction{

    public $title = 'Privacy Center';
    public $slug  = 'privacy-center'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'aiogdpr-privacy-center';

    protected function run(){
    	$this->requireAdmin();

        if($this->has('privacy_center_intro')){
            AIOGDPR_Settings::set('privacy_center_intro', $this->get('privacy_center_intro'));
        } 

        if($this->has('privacy_center_page')){
            AIOGDPR_Settings::set('privacy_center_page', $this->get('privacy_center_page'));
        } 
        
        $this->returnBack();
    }
}

AIOGDPR_PrivacyCenterAction::listen();


