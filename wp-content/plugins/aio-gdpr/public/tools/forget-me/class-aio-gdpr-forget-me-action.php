<?php

Class AIOGDPR_ForgetMeAction extends AIOGDPR_AjaxAction{

    protected $action = 'forget-me-action';

    public function run(){
        if(!$this->has('email') || empty($this->get('email'))){
            $this->error('No email address provided.');
        }

        $forgetee = AIOGDPR_Request::insert(array(
            'type'       => 'forget-me',
            'first_name' => $this->get('first_name'),
            'last_name'  => $this->get('last_name'),
            'email'      => $this->get('email'),
        ));

        if($this->has('process_now')){
            $forgetee->forgetSubject();
        }

        if($this->has('is_admin')){
            $this->returnBack();
        }

        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Check Your Email',
            'message_body'  => 'You have been emailed a confirmation link. When you click the link in the email, all of your personal data will be deleted from this site.',
        ));
    }
}

AIOGDPR_ForgetMeAction::listen();