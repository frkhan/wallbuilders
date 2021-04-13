<?php

class AIOGDPR_ConsentTool extends AIOGDPR_Tool{

    public $slug = 'consent';

    public static function getMenuItem(){
        return array(
            'icon'          => 'octicon-verified',
			'title' 		=> 'Consent',
			'description' 	=> 'In our privacy policy we outline how we use your personal data, who we expose your data to and how long we keep it.',
			'view'			=> 'consent',
		);
    }

    public function view(){
        include 'view.php';
    }
}

AIOGDPR_ConsentTool::register();