<?php

class AIOGDPR_RequestArchiveTool extends AIOGDPR_Tool{

    public $slug = 'request-archive';

    public static function getMenuItem(){
        return array(
            'icon'          => 'octicon-file-directory',
			'title' 		=> 'Request Archive',
			'description' 	=> 'Use this to perform a <strong>Subject Access Request</strong>. We will send you a copy of all the personal data we currently possess on you.',
			'view'			=> 'request-archive',
		);
    }

    public function view(){
        include 'view.php';
    }
}

AIOGDPR_RequestArchiveTool::register();