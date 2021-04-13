<?php

Class AIOGDPR_ConfirmForgetMeAction extends AIOGDPR_AjaxAction{

    protected $action = 'confirm-forget-me';

    public function run(){
        if(!$this->has('token') || empty($this->get('token'))){
            $this->error('No token provided.');
        }
        
        $forgetee = AIOGDPR_Request::finder('token', array(
            'token' => $this->get('token')
        ));

        if(is_null($forgetee)){
            $this->error('Bad token provided.');
        }
        
        
        $forgetee->status = 'confirmed';
        $forgetee->forgetSubject();
        $forgetee->save();
        

        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Success',
            'message_body'  => 'All of your data has been deleted.',
        ));
    }
}

AIOGDPR_ConfirmForgetMeAction::listen();