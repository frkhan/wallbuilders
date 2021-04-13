<?php

class AIOGDPR_PrivacySettingsTool extends AIOGDPR_Tool{

    public $slug = 'privacy-settings';

    public static function getMenuItem(){
        return array(
            'icon'          => 'octicon-shield',
			'title' 		=> 'Privacy Settings',
			'description' 	=> 'Use this tool to control the services and third-parties we share your data with.',
			'view'			=> 'privacy-settings',
		);
    }

    public function view(){
        include 'view.php';
    }
}

AIOGDPR_PrivacySettingsTool::register();