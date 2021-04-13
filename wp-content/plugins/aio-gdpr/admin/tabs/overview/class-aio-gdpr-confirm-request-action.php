<?php

Class AIOGDPR_ConfirmRequestAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-confirm-request';

    protected function run(){
        $this->requireAdmin();

        if($this->has('id')){
            $request = AIOGDPR_Request::find($this->get('id'));
            if(!is_null($request)){
                $request->update(array(
                    'status' => 'confirmed'
                ));

                do_action('user_request_action_confirmed', $request->v2_id);
            }
        }
       
    	$this->returnBack();
    }
}

AIOGDPR_ConfirmRequestAction::listen();
