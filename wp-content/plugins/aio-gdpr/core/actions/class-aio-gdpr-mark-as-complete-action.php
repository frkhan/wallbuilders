<?php

Class AIOGDPR_MarkAsCompleteAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-mark-as-complete';

    protected function run(){
        $this->requireAdmin();

        if($this->has('request_id')){
            $request = AIOGDPR_Request::find($this->get('request_id'));
            $request->status = 'complete';
            $request->save();

            AIOGDPR_TimelineItem::insert(array(
                'request_id' => $request->ID,
                'user'       => get_current_user_id(),
                'title' 	 => 'Marked as complete',
            ));
        }

        $this->returnRedirect(admin_url('edit.php'), array('post_type' => 'aiogdpr_request'));
    }
}

AIOGDPR_MarkAsCompleteAction::listen();
