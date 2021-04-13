<?php

Class AIOGDPR_CookieNoticeAction extends AIOGDPR_AjaxAction{

    public $title = 'Cookie Notice';
    public $slug  = 'cookie-notice'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'admin-cookie-notice';

    protected function run(){
        $this->requireAdmin();
        
        if($this->has('reset_cookie_token')){
            AIOGDPR_Settings::set('cookie_notice_token',     wp_generate_password(20, FALSE, FALSE));
            $this->returnBack();
        }

        AIOGDPR_Settings::set('display_cookie_notice', $this->get('display_cookie_notice', '0'));
        
        // Update Cookie Notice Text
        if($this->has('cookie_notice')){
            AIOGDPR_Settings::set('cookie_notice', $this->get('cookie_notice'));
        }


        $this->returnBack();
    }
}

AIOGDPR_CookieNoticeAction::listen();


