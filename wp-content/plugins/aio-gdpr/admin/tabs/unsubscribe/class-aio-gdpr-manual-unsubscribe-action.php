<?php

Class AIOGDPR_ManualUnsubscribeAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-manual-unsubscribe';

    protected function run(){
        $this->requireAdmin();

        $request = AIOGDPR_Request::insert(array(
            'type'          => 'unsubscribe',
            'email'         => $this->get('email'),
            'first_name'    => '-',
            'last_name'     => '-',
        ));

        if($this->has('skip_confirmation')){
            $request->unsubscribeSubject();
        }

    	$this->returnBack();
    }
}

AIOGDPR_ManualUnsubscribeAction::listen();
