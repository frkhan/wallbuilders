<?php

Class AIOGDPR_AssignToMeAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-assign-to-me';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            update_post_meta($this->get('request_id'), 'assigned_to', get_current_user_id());
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_AssignToMeAction::listen();
