<?php

Class AIOGDPR_AboutAction extends AIOGDPR_AjaxAction{


    public $title = 'About';
    public $slug  = 'about';
    public $isHidden = TRUE; 
    public $hideMenu = TRUE;
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }


    protected $action = 'about-page';

    protected function run(){
    	$this->requireAdmin();


        if($this->has('feedback_message') && strlen($this->get('feedback_message')) !== 0){
            $email = AIOGDPR_Mail::init()
                ->to('support@gdprplug.in')
                ->subject('All-in-One GDPR Feedback')
		        ->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
		        ->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
                ->template(AIOGDPR::pluginDir('core/emails/Feedback.php'), array(
                    'home_url'  => home_url(),
                    'user'      => $this->user->user_email,
                    'reason'    => $this->get('feedback_reason'),
                    'message'   => $this->get('feedback_message')
                ))
                ->send();   
        }

        
        if($this->has('test_dpo_email')){

            $email = AIOGDPR_Mail::init()
                ->to(AIOGDPR_Settings::get('dpo_email'))
                ->subject('All-in-One GDPR: TEST EMAIL')
                ->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
                ->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
                ->template(AIOGDPR::pluginDir('core/emails/TestEmail.php'), array(
                    'dpoName'  => AIOGDPR_Settings::get('dpo_first_name') .' '. AIOGDPR_Settings::get('dpo_last_name'),
                ))
                ->send();

            if($email === FALSE){
                echo "Email Error.";
                die;
            }
        }

        
        // Show Set Up
        if($this->has('show_setup')){
            AIOGDPR_Settings::set('show_setup', '1');
            AIOGDPR_Settings::set('setup_stage', '1');
            $this->returnRedirect(AIOGDPR::adminURL(array('tab' => 'setup')));
        }
        

        // Logs
        if($this->get('logging', '') == 'disable'){
            AIOGDPR_Settings::set('logging_enabled', '0');
        }else if($this->get('logging', '') == 'enable'){
            AIOGDPR_Settings::set('logging_enabled', '1');
        }

        if($this->has('log_test')){
            AIOGDPR_Log::insert('LOG TEST!');
        }
        
        if($this->has('migrate_logs')){
            AIOGDPR_Settings::set('logging_enabled', '0');
            $result = AIOGDPR_Log::migrate();
            if($result === TRUE){
                AIOGDPR_Log::insert('Logs Table Created!');
                AIOGDPR_Settings::set('logging_enabled', '1');
            }else{
                $this->error('error');
            }
        }
        

        $this->returnBack();
    }
}

AIOGDPR_AboutAction::listen();


