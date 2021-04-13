<?php

class AIOGDPR_UnsubscribeTool extends AIOGDPR_Tool{

    public $slug = 'unsubscribe';

    public function getMenuItem(){
		return array(
            'icon'          => 'octicon-mail',
			'title' 		=> 'Unsubscribe',
			'description' 	=> 'If you would like to stop receiving all marketing emails from us, please fill out this form.',
			'view'			=> 'unsubscribe',
		);
	}

	public function view(){
		include 'view.php';
	}
}

AIOGDPR_UnsubscribeTool::register();