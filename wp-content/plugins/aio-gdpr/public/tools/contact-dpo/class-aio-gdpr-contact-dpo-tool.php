<?php

class AIOGDPR_ContactDPOTool extends AIOGDPR_Tool{

    public $slug = 'contact-dpo';

    public static function getMenuItem(){
        return array(
            'icon'          => 'octicon-comment-discussion',
			'title' 		=> 'Contact DPO',
			'description' 	=> 'If you have a special request concerning your personal data, please use this to contact our Data Protection Officer directly.',
			'view'			=> 'contact-dpo',
		);
    }

    public function view(){
        $firstName = '';
        $lastName  = '';
        $email     = '';
        
        if(is_user_logged_in()){
            $firstName = wp_get_current_user()->user_firstname;
            $lastName  = wp_get_current_user()->user_lastname;
            $email     = wp_get_current_user()->user_email;
        }  
    
        include 'view.php';
    }
}

AIOGDPR_ContactDPOTool::register();