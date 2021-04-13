<?php

Class AIOGDPR_ForgetUserAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-forget-user';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            $request = AIOGDPR_Request::find($this->get('request_id'));
            $request->forgetSubject();
            $this->returnBack();
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_ForgetUserAction::listen(FALSE);
