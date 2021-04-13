<?php

Class AIOGDPR_ContactDPOAction extends AIOGDPR_AjaxAction{

    protected $action = 'contact-dpo-action';

    public function run(){

        $email = AIOGDPR_Mail::init()
            ->to(AIOGDPR_Settings::get('dpo_email'))
            ->subject('DPO Contact Request')
            ->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
            ->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
            ->template(AIOGDPR::pluginDir('core/emails/ContactDPO.php'), array(
                'dpoName'     => AIOGDPR_Settings::get('dpo_first_name'),
                
                'first_name'  => $this->get('first_name'),
                'last_name'   => $this->get('last_name'),
                'email'       => $this->get('email'),
                'message'     => $this->get('message'),
            ))
            ->send();


        AIOGDPR_Request::insert(array(
            'type'       => 'contact-dpo',
            'first_name' => $this->get('first_name'),
            'last_name'  => $this->get('last_name'),
            'email'      => $this->get('email'),
            'message'    => $this->get('message'),
        ));


        $url = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        $this->returnRedirect($url, array(
            'message_type'  => 'success',
            'message_title' => 'Message Recieved',
            'message_body'  => 'We have recieved your message. Our DPO will get back to you within 48 hours.',
        ));
    }
}

AIOGDPR_ContactDPOAction::listen();