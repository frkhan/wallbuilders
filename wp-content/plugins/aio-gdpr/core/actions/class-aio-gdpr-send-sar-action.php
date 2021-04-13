<?php

Class AIOGDPR_SendSARAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-send-sar-action';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            $request = AIOGDPR_Request::find($this->get('request_id'));
            $request->perfromSubjectAccessRequest();
            $this->returnBack();
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_SendSARAction::listen(FALSE);
