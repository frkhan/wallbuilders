<?php

Class AIOGDPR_ForceForgetMeAction extends AIOGDPR_AjaxAction{

    protected $action = 'force-forget-me';

    protected function run(){
        $this->requireAdmin();

        // Forget single user
        if($this->has('id')){
            $forgetee = AIOGDPR_Request::find($this->get('id'));
            if(isset($forgetee)){
                $forgetee->forgetSubject();
            }
        }

        // Forget all
        if($this->has('all')){
            $requests = AIOGDPR_Request::finder('type', array('type' => 'forget-me'));
            foreach($requests as $key => $request){
                if($request->status != 'complete'){
                    $request->forgetSubject();
                }
            } 
        }

    	$this->returnBack();
    }
}

AIOGDPR_ForceForgetMeAction::listen();
