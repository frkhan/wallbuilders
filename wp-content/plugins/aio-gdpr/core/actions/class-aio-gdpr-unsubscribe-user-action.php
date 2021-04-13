<?php

Class AIOGDPR_UnsubscribeUserAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-unsubscribe-user-action';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            $request = AIOGDPR_Request::find($this->get('request_id'));
            $request->unsubscribeSubject();
            $this->returnBack();
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_UnsubscribeUserAction::listen(FALSE);
