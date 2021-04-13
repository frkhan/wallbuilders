<?php

Class AIOGDPR_ConfirmRequestArchiveAction extends AIOGDPR_AjaxAction{

    protected $action = 'aio-confirm-request-archive';

    public function run(){
        if(!$this->has('token') || empty($this->get('token'))){
            $this->error('No token provided.');
        }
        
        $sar = AIOGDPR_Request::finder('token', array(
            'token' => $this->get('token')
        ));

        if(is_null($sar)){
            $this->error('Bad token provided.');
        }

        $sar->status = 'confirmed';
        $sar->save();
        
        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Confirmed',
            'message_body'  => 'We will email you your data in the next 48 hours.',
        ));
    }
}

AIOGDPR_ConfirmRequestArchiveAction::listen();
