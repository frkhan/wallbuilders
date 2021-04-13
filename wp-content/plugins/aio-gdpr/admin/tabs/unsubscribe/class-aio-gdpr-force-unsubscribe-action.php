<?php

Class AIOGDPR_ForceUnsubscribeAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-force-unsubscribe';

    protected function run(){
        $this->requireAdmin();

        if($this->has('id')){
            $request = AIOGDPR_Request::find($this->get('id'));
            $request->unsubscribeSubject();
        }

        if($this->has('all')){
            $requests = AIOGDPR_Request::finder('type', array('type' => 'unsubscribe'));
            foreach($requests as $key => $request){
                if($request->status != 'complete'){
                    $request->unsubscribeSubject();
                }
            } 
        }

    	$this->returnBack();
    }
}

AIOGDPR_ForceUnsubscribeAction::listen();
