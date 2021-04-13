<?php

Class AIOGDPR_LogAction extends AIOGDPR_AjaxAction{

    public $title = 'log';
    public $slug  = 'log';
    public $isHidden = TRUE; 
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'aiogdpr-log';

    protected function run(){
    	$this->requireAdmin();


        $this->returnBack();
    }
}

AIOGDPR_LogAction::listen();


