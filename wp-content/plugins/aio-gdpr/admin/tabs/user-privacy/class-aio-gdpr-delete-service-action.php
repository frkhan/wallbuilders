<?php

Class AIOGDPR_DeleteServiceAction extends AIOGDPR_AjaxAction{

    protected $action = 'delete-service';

    protected function run(){
        $this->requireAdmin();
        
        $service = AIOGDPR_Service::find($this->get('service'));
		if(!is_null($service)){
            $service->delete();
        }

        $this->returnBack();
    }
}

AIOGDPR_DeleteServiceAction::listen();