<?php

Class AIOGDPR_SingleRequestAction extends AIOGDPR_AjaxAction{

    public $title = 'Single Request';
    public $slug  = 'single-request';
    public $isHidden = TRUE; 
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'aiogdpr-single-request';

    protected function run(){
    	$this->requireAdmin();

    
        $this->returnBack();
    }
}

AIOGDPR_SingleRequestAction::listen();


