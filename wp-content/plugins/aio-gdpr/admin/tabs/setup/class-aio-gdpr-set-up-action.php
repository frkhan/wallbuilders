<?php

Class AIOGDPR_SetUpAction extends AIOGDPR_AjaxAction{

    public $title = 'Setup';
    public $slug  = 'setup'; 
    public $isHidden = TRUE; 
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'admin-create-page';

    protected function run(){
        $this->requireAdmin();

        if(!$this->has('stage')){
            $this->error('No stage');
        }
        

        if($this->get('stage') === '1'){
            $this->stage1();
        }else if($this->get('stage') === '2'){
           $this->stage2();
        }else if($this->get('stage') === '3'){
            $this->stage3();
        }else if($this->get('stage') === '4'){
            $this->stage4();
        }
    }

    public function stage1(){
        AIOGDPR_Settings::set('setup_stage', '2');
        if(AIOGDPR_Settings::get('has_run_init') == '0'){
            AIOGDPR_Settings::init();
            AIOGDPR_Settings::set('has_run_init', '1');
            AIOGDPR_Settings::set('logging_enabled', '0');
        }

        $this->returnRedirect(AIOGDPR::adminURL(array('tab' => 'setup')));
    }

    public function stage2(){
        if($this->has('license_key')){
            AIOGDPR_Settings::set('setup_stage', '3');
            AIOGDPR_Settings::set('license_key', $this->get('license_key'));

            if($this->has('create_privacy_center_page')){
                $ID = wp_insert_post(array(
                    'post_title' 	=> 'Privacy Center',
                    'post_name'     => 'privacy',
                    'post_content' 	=> '[privacy_center]',
                    'post_type' 	=> 'page',
                    'post_status'	=> 'publish'
                ));
                AIOGDPR_Settings::set('privacy_center_page', $ID);
            }
        }

        $this->returnRedirect(AIOGDPR::adminURL(array('tab' => 'setup')));
    }

    public function stage3(){
        AIOGDPR_Settings::set('setup_stage', '4');
        AIOGDPR_Settings::set('show_setup', '0');

        AIOGDPR_Settings::set('dpo_email', $this->get('dpo_email', ''));
        AIOGDPR_Settings::set('dpo_first_name', $this->get('dpo_first_name', ''));
        AIOGDPR_Settings::set('dpo_last_name', $this->get('dpo_last_name', ''));
        AIOGDPR_Settings::set('display_cookie_notice', '1');

        $this->returnRedirect(AIOGDPR::adminURL(array('tab' => 'setup')));
    }

    public function stage4(){
        AIOGDPR_Settings::set('setup_stage', '4');
        AIOGDPR_Settings::set('show_setup', '0');
        $this->returnRedirect(AIOGDPR::adminURL(array('tab' => 'overview')));
    }
}

AIOGDPR_SetUpAction::listen();


