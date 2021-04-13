<?php

Class AIOGDPR_ConsentAction extends AIOGDPR_AjaxAction{

    protected $action = 'aiogdpr-consent';

    protected function run(){

        if($this->has('consent') && $this->get('consent') == '1'){
            AIOGDPR_UserPermissions::consent();

            if($this->has('is_ajax')){
                $this->returnJSON(array(
                    'status' => 'success'
                ));
            }

            if($this->has('is_cookie_notice')){
                $this->returnBack();
            }

            $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
            $this->returnRedirect($url, array(
                'message_type'  => 'success',
                'message_title' => 'Consent Provided',
                'message_body'  => 'You have successfully provided explicit consent to our privacy policy',
            ));
        }else{
            AIOGDPR_UserPermissions::withdrawConsent();

            $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
            $this->returnRedirect($url, array(
                'message_type'  => 'success',
                'message_title' => 'Consent Withdrawn',
                'message_body'  => 'You have successfully withdrawn your consent to our privacy policy',
            ));
        }


        $this->returnBack();
    }
}

AIOGDPR_ConsentAction::listen();