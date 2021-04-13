<?php

Class AIOGDPR_ConfirmUnsubscribeAction extends AIOGDPR_AjaxAction{

    protected $action = 'confirm-unsubscribe';

    public function run(){
        if(!$this->has('token') || empty($this->get('token'))){
            $this->error('No token provided.');
        }
        
        $unsubscriber = AIOGDPR_Request::finder('token', array(
            'token' => $this->get('token')
        ));

        if(is_null($unsubscriber)){
            $this->error('Bad token provided.');
        }

        $unsubscriber->unsubscribeSubject();
        $unsubscriber->status = 'complete';
        $unsubscriber->save();
        
        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Unsubscribe Successful',
            'message_body'  => 'We will remove you from all of our email marketing lists.',
        ));
    }
}

AIOGDPR_ConfirmUnsubscribeAction::listen();