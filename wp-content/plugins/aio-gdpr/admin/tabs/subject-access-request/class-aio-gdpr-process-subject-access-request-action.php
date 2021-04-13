<?php

Class AIOGDPR_ProcessSubjectAccessRequestAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-process-subject-access-request';

    protected function run(){
        $this->requireAdmin();

        if($this->has('process')){
            $ID = $this->get('process');
            $request = AIOGDPR_Request::find($ID);
            if(!is_null($request)){
                $request->perfromSubjectAccessRequest();
            }
        }

        if($this->has('all')){
            foreach(AIOGDPR_Request::finder('pending') as $request){
                $request->perfromSubjectAccessRequest();
            }
        }


    	$this->returnBack();
    }
}

AIOGDPR_ProcessSubjectAccessRequestAction::listen();
