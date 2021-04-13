<?php

Class AIOGDPR_PrivacyPolicyAction extends AIOGDPR_AjaxAction{

    public $title = 'Privacy Policy';
    public $slug  = 'privacy-policy';
    public $isHidden = TRUE;
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'privacy-policy';

    protected function run(){
    	$this->requireAdmin();

    	if($this->has('privacy_policy_overview')){
            $version = AIOGDPR_Settings::set('privacy_policy_overview', $this->get('privacy_policy_overview'));
        }

        // Update privacy policy
    	if($this->has('privacy_policy')){
    		$version = AIOGDPR_Settings::get('privacy_policy_version');
    		$version = intval($version);
    		$version++;
    		$version = AIOGDPR_Settings::set('privacy_policy_version', $version);
            AIOGDPR_Settings::set('privacy_policy_hash', wp_hash($this->get('privacy_policy')));
    		AIOGDPR_Settings::set('privacy_policy', $this->get('privacy_policy'));
    	}

        
        $this->returnBack();
    }
}

AIOGDPR_PrivacyPolicyAction::listen();
