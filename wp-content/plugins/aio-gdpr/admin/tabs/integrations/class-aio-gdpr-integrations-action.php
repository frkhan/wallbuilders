<?php

Class AIOGDPR_IntegrationsAction extends AIOGDPR_AjaxAction{

    public $title    = 'Integrations';
    public $slug     = 'integrations';
    public $isHidden = TRUE;
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }
    

    protected $action = 'aiogdpr-integrations-submit';

    protected function run(){
        $this->requireAdmin();

        $time = time();
        AIOGDPR_Settings::set('integration_enable_key', $time);

        foreach($this->get('integrations') as $integrationSlug => $value){
        	AIOGDPR_Settings::set('is_enabled_'. $integrationSlug, $time);
        }
       
    	$this->returnBack();
    }
}

AIOGDPR_IntegrationsAction::listen();
