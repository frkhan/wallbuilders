<?php

Class AIOGDPR_UnsubscribeAction extends AIOGDPR_AjaxAction{

    protected $action = 'unsubscribe';

    public function run(){
        if(!$this->has('email')){
            $this->returnBack();
        }

        AIOGDPR_Request::insert(array(
            'type'          => 'unsubscribe',
            'first_name'    => $this->get('first_name'),
            'last_name'     => $this->get('last_name'),
            'email'         => $this->get('email'),
        ));
        

        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Check Your Email',
            'message_body'  => 'You have been emailed a confirmation link. When you click the link in the email, will remove you from all of our email marketing lists.',
        ));
    }
}

AIOGDPR_UnsubscribeAction::listen();