<?php

Class AIOGDPR_TermsConditionsAction extends AIOGDPR_AjaxAction{

    public $title = 'Terms Conditions';
    public $slug  = 'terms-conditions';
    public $isHidden = TRUE; 
    public $hideMenu = TRUE; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }

    
    protected $action = 'terms-conditions';

    protected function run(){
        $this->requireAdmin();

        // Set T&C's Page
        if($this->has('terms_conditions_page')){
            AIOGDPR_Settings::set('terms_conditions_page', $this->get('terms_conditions_page'));
        }

        // Update Terms & Conditions
    	if($this->has('terms_conditions')){
    		$version = AIOGDPR_Settings::get('terms_conditions_version');
    		$version = intval($version);
    		$version++;
    		$version = AIOGDPR_Settings::set('terms_conditions_version', $version);
    		AIOGDPR_Settings::set('terms_conditions_hash', wp_hash($this->get('terms_conditions')));
    		AIOGDPR_Settings::set('terms_conditions', $this->get('terms_conditions'));
    	}


        $this->returnBack();
    }
}

AIOGDPR_TermsConditionsAction::listen();
