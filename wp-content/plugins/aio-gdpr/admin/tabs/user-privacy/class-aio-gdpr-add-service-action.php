<?php

Class AIOGDPR_AddServiceAction extends AIOGDPR_AjaxAction{

    protected $action = 'admin-add-service';

    protected function run(){
        $this->requireAdmin();

        AIOGDPR_Service::insert(array(
            'name'      => $this->get('new_name'),
            'reason'    => $this->get('new_reason'),
            'tc_link'   => $this->get('new_tc_link'),
            'type'      => $this->get('new_type'),
        ));

        $this->returnBack();
    }
}

AIOGDPR_AddServiceAction::listen();