<?php

Class AIOGDPR_OverviewAction extends AIOGDPR_AjaxAction{

	public $title = 'Overview';
	public $slug  = 'overview'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'aiogdpr-overview-submit';

    protected function run(){
        $this->requireAdmin();
       
    	$this->returnBack();
    }
}

AIOGDPR_OverviewAction::listen();
