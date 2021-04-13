<?php

Class AIOGDPR_SubjectAccessRequestAction extends AIOGDPR_AjaxAction{

    protected $action = 'subject-access-request';

    public function run(){
        if(!$this->has('email')){
            $this->error('No email address provided.');
        }

        $request = AIOGDPR_Request::insert(array(
            'type'       => 'sar',
            'first_name' => $this->get('first_name'),
            'last_name'  => $this->get('last_name'),
            'email'      => $this->get('email'),
        ));


        if($this->has('process_now')){
            $displayEmail = ($this->get('display_email', '0') == '1');
            $request->perfromSubjectAccessRequest($displayEmail);
        }

        if($this->has('is_admin')){
            $this->returnBack();
        }

        if($this->has('is_ajax')){
            echo json_encode(array(
                'success'   => '1',
                'zip_link'  => AIOGDPR_DownloadArchiveAction::url(array(
                    'token'     => $request->token,
                    'file'      => 'zip',
                )),
            ));
        }

        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Success',
            'message_body'  => 'You will be sent an email in the next 24hrs outlining what personal data we currently possess on you.',
        ));

        $this->returnBack();
    }
}

AIOGDPR_SubjectAccessRequestAction::listen();
