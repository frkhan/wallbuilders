<?php

Class AIOGDPR_WithdrawConsentAction extends AIOGDPR_AjaxAction{

    protected $action = 'withdraw-concent';

    protected function run(){

        if($this->has('user_id')){
            $this->requireAdmin();
            AIOGDPR_UserPermissions::withdrawConsent($this->get('user_id'));
        }else{
            AIOGDPR_UserPermissions::withdrawConsent();
        }
         
        if($this->has('is_ajax') || strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            echo json_encode(array(
                'permissions' => $userPermissions
            ));
            die;
        }

        $this->returnBack();
    }
}

AIOGDPR_WithdrawConsentAction::listen();