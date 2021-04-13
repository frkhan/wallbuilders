<?php

Class AIOGDPR_FindDataAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-find-data-action';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            $request = AIOGDPR_Request::find($this->get('request_id'));
            $request->collectData();
            $this->returnBack();
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_FindDataAction::listen(FALSE);
