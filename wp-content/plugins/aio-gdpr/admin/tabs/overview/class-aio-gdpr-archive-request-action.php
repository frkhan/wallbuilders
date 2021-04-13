<?php

Class AIOGDPR_ArchiveRequestAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-archive-request';

    protected function run(){
        $this->requireAdmin();

        if($this->has('id')){
            $request = AIOGDPR_Request::find($this->get('id'));
            if(!is_null($request)){
                $request->delete();
            }
        }
       
    	$this->returnBack();
    }
}

AIOGDPR_ArchiveRequestAction::listen();
