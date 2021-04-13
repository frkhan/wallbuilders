<?php

Class AIOGDPR_PrivacySettingsFormAction extends AIOGDPR_AjaxAction{

    protected $action = 'user-permissions';

    protected function run(){

        $permissions = array();
        $services = $this->get('services', NULL, FALSE);
        foreach(AIOGDPR_Service::all() as $s){
            if(isset($services[$s->slug])){
                if($services[$s->slug] == '1'){
                    $permissions[] = $s->slug;
                }
            }   
        }
        AIOGDPR_UserPermissions::updateCurrentUserPermisisons($permissions);


        if($this->has('consent')){ 
            AIOGDPR_UserPermissions::consent();
        }

        $this->returnBack();
    }
}

AIOGDPR_PrivacySettingsFormAction::listen();