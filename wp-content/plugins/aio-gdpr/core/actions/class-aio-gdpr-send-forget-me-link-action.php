<?php

Class AIOGDPR_SendForgetMeLink extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-send-forget-me';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            $request = AIOGDPR_Request::find($this->get('request_id'));
            $request->sendForgetMeConfirmationLink();
            $this->returnBack();
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_SendForgetMeLink::listen(FALSE);
