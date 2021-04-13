<?php

class AIOGDPR_ForgetMeTool extends AIOGDPR_Tool{

    public $slug = 'forget-me';

    public static function getMenuItem(){
        return array(
            'icon'          => 'octicon-trashcan',
			'title' 		=> 'Forget Me',
			'description' 	=> 'Complete this form to submit an <strong>erasure request</strong>. Caution, this cannot be undone.',
			'view'			=> 'forget-me',
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

AIOGDPR_ForgetMeTool::register();