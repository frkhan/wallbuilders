<?php

Class AIOGDPR{

    public $integrations = array();
    public static $instance = null;
    
    protected function __clone(){}

    protected function __construct(){
        $this->loadDependencies();
        $this->registerHooks();
    }
    
    public static function instance(){
        if(!isset(static::$instance)){
            static::$instance = new static;
            do_action('AIOGDPR_booted');
        }
        return static::$instance;
    }


    //======================================================================
    // Application Methods
    //======================================================================
    public static function pluginDir($append = ''){
        return plugin_dir_path(dirname(__FILE__)) . $append;
    }
    
    public static function pluginURI($append = ''){
        return plugin_dir_url(dirname(__FILE__)) . $append;
    }

    public static function adminURL($params = array()){
        $params = http_build_query(array_merge(array(
            'page' => 'aio-gdpr',
        ), $params));

        return admin_url('admin.php') .'?'. $params;
    }

    public function slugify($text){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if(empty($text)){
            return 'n-a';
        }

        return $text;
    }

    //======================================================================
    // Integrations
    //======================================================================
    public static function integrationsTabs(){
        $tabs = array();
        foreach(AIOGDPR::instance()->integrations as $integration){
            if(AIOGDPR_Integration::isEnabled($integration->slug)){
                if(method_exists($integration, 'adminView')){
                    $tabs[$integration->slug] = $integration->adminView();
                }
            }
        }
        return $tabs;
    }

    public static function getIntegrations(){
        return AIOGDPR::instance()->integrations;
    }

    public static function getEnabledIntegrations(){
        $return = array();
        foreach(AIOGDPR::getIntegrations() as $integration){
            if(AIOGDPR_Integration::isEnabled($integration->slug)){
                $return[$integration->slug] = $integration;
            }
        }
        return $return;
    }

    public static function callOnAllIntegrations($method, $args = array()){
        foreach(AIOGDPR::getEnabledIntegrations() as $integration){
            if(method_exists($integration, $method)){
                try{
                    call_user_func_array(array($integration, $method), $args);
                }catch(Exception $e){
                    AIOGDPR_Log::insert($e->getMessage());
                }
            }
        }
    }

    public function registerIntegration($integration){
        $integration->boot();
        $this->integrations[$integration->slug] = $integration;
    }

    
    //======================================================================
    // Boot Cycle
    //======================================================================
    public static function loadDependencies(){
        if(file_exists(AIOGDPR::pluginDir('vendor/autoload.php'))){
            require_once AIOGDPR::pluginDir('vendor/autoload.php');
        }
        
        //======================================================================
        // Libraries
        //======================================================================
        require AIOGDPR::pluginDir('core/class-aio-gdpr-ajax-action.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-settings.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-mail.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-data-collecter.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-integration.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-slim-model.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-cron.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-hooks.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-user-permissions.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-privacy-center.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-tool.php');
        require AIOGDPR::pluginDir('core/class-aio-gdpr-log.php');

        // Actions
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-assign-to-me-action.php');
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-send-forget-me-link-action.php');
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-forget-user-action.php');
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-unsubscribe-user-action.php');
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-send-sar-action.php');
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-find-data-action.php');
        require AIOGDPR::pluginDir('core/actions/class-aio-gdpr-mark-as-complete-action.php');

        // Models    
        require AIOGDPR::pluginDir('core/models/class-aio-gdpr-request.php');
        require AIOGDPR::pluginDir('core/models/class-aio-gdpr-service.php');
        require AIOGDPR::pluginDir('core/models/class-aio-gdpr-timeline-item.php');

        // Cron
        require AIOGDPR::pluginDir('core/cron/aio-gdpr-cron-event.php');


        //======================================================================
        // Public
        //======================================================================
        require AIOGDPR::pluginDir('public/actions/class-aio-gdpr-widthdraw-consent-action.php');
        require AIOGDPR::pluginDir('public/actions/class-aio-gdpr-consent-action.php');
        require AIOGDPR::pluginDir('public/shortcodes/privacy-center.php');
        require AIOGDPR::pluginDir('public/shortcodes/consent.php');
        require AIOGDPR::pluginDir('public/shortcodes/privacy-policy.php');
        require AIOGDPR::pluginDir('public/shortcodes/terms-conditions.php');

        
        //======================================================================
        // Admin Tabs
        //======================================================================
        // Set-up
        require AIOGDPR::pluginDir('admin/tabs/setup/class-aio-gdpr-set-up-action.php');

        // Overview
        require AIOGDPR::pluginDir('admin/tabs/overview/class-aio-gdpr-overview-action.php');
        require AIOGDPR::pluginDir('admin/tabs/overview/class-aio-gdpr-archive-request-action.php');
        require AIOGDPR::pluginDir('admin/tabs/overview/class-aio-gdpr-confirm-request-action.php');

        // Single Request
        require AIOGDPR::pluginDir('admin/tabs/single-request/class-aio-gdpr-single-request-action.php');

        // Privacy Center
        require AIOGDPR::pluginDir('admin/tabs/privacy-center/class-aio-gdpr-privacy-center-action.php');

        // DPO
        require AIOGDPR::pluginDir('admin/tabs/dpo/class-aio-gdpr-dpo-admin-action.php');

        // Subject Access Request
        require AIOGDPR::pluginDir('admin/tabs/subject-access-request/class-aio-gdpr-subject-access-request-action.php');
        require AIOGDPR::pluginDir('admin/tabs/subject-access-request/class-aio-gdpr-process-subject-access-request-action.php');

        // Unsubscribe
        require AIOGDPR::pluginDir('admin/tabs/unsubscribe/class-aio-gdpr-unsubscribe-admin-action.php');
        require AIOGDPR::pluginDir('admin/tabs/unsubscribe/class-aio-gdpr-manual-unsubscribe-action.php');
        require AIOGDPR::pluginDir('admin/tabs/unsubscribe/class-aio-gdpr-force-unsubscribe-action.php');

        // Forget Me
        require AIOGDPR::pluginDir('admin/tabs/forget-me/class-aio-gdpr-forget-me-admin-action.php');
        require AIOGDPR::pluginDir('admin/tabs/forget-me/class-aio-gdpr-force-forget-me-action.php');

        // User Permissions
        require AIOGDPR::pluginDir('admin/tabs/user-privacy/class-aio-gdpr-services-action.php');
        require AIOGDPR::pluginDir('admin/tabs/user-privacy/class-aio-gdpr-delete-service-action.php');
        require AIOGDPR::pluginDir('admin/tabs/user-privacy/class-aio-gdpr-add-service-action.php');

        // Privacy Policy
        require AIOGDPR::pluginDir('admin/tabs/privacy-policy/class-aio-gdpr-privacy-policy-action.php');

        // Terms Conditions
        require AIOGDPR::pluginDir('admin/tabs/terms-conditions/class-aio-gdpr-terms-conditions-action.php');

        // Cookie Notice
        require AIOGDPR::pluginDir('admin/tabs/cookie-notice/class-aio-gdpr-cookie-notice-action.php');

        // Integrations
        require AIOGDPR::pluginDir('admin/tabs/integrations/class-aio-gdpr-integrations-action.php');

        // About
        require AIOGDPR::pluginDir('admin/tabs/about/class-aio-gdpr-about-action.php');
        
        // Log
        require AIOGDPR::pluginDir('admin/tabs/log/class-aio-gdpr-log-action.php');
        

        //======================================================================
        // Tools
        //======================================================================
        // Consent
        require AIOGDPR::pluginDir('public/tools/consent/class-aio-gdpr-consent-tool.php');

        // Contact DPO
        require AIOGDPR::pluginDir('public/tools/contact-dpo/class-aio-gdpr-contact-dpo-action.php');
        require AIOGDPR::pluginDir('public/tools/contact-dpo/class-aio-gdpr-contact-dpo-tool.php');

        // Privacy Settings
        require AIOGDPR::pluginDir('public/tools/privacy-settings/class-aio-gdpr-privacy-settings-tool.php');
        require AIOGDPR::pluginDir('public/tools/privacy-settings/class-aio-gdpr-privacy-settings-form-action.php');

        // Request Archive
        require AIOGDPR::pluginDir('public/tools/request-archive/class-aio-gdpr-request-archive-tool.php');
        require AIOGDPR::pluginDir('public/tools/request-archive/class-aio-gdpr-subject-access-request-action.php');
        require AIOGDPR::pluginDir('public/tools/request-archive/class-aio-gdpr-confirm-request-archive-action.php');

        // Unsubscribe
        require AIOGDPR::pluginDir('public/tools/unsubscribe/class-aio-gdpr-confirm-unsubscribe-action.php');
        require AIOGDPR::pluginDir('public/tools/unsubscribe/class-aio-gdpr-unsubscribe-action.php');
        require AIOGDPR::pluginDir('public/tools/unsubscribe/class-aio-gdpr-unsubscribe-tool.php');

        // Forget Me
        require AIOGDPR::pluginDir('public/tools/forget-me/class-aio-gdpr-forget-me-action.php');
        require AIOGDPR::pluginDir('public/tools/forget-me/class-aio-gdpr-confirm-forget-me-action.php');
        require AIOGDPR::pluginDir('public/tools/forget-me/class-aio-gdpr-forget-me-tool.php');
    }	


    public function registerHooks(){

        $hooks = new AIOGDPR_Hooks;

        // Admin Hooks
        add_action('admin_enqueue_scripts', 	array($hooks, 'enqueueAdminStyles'));
        add_action('admin_enqueue_scripts', 	array($hooks, 'enqueueAdminScripts'));
        add_action('admin_menu',                array($hooks, 'adminMenuItem'));
        add_action('show_user_profile', 		array($hooks, 'showPermissonStatus'));		
        add_action('display_post_states',		array($hooks, 'addCustomPostStates'), 10, 2);
        
        add_filter('manage_'. AIOGDPR_Request::getPostType() .'_posts_columns',       array($hooks, 'setCustomEditRequestColumns'));
        add_action('manage_'. AIOGDPR_Request::getPostType() .'_posts_custom_column', array($hooks, 'customRequestColumn'), 10, 2);
        add_action('save_post_'. AIOGDPR_Request::getPostType(), array($hooks, 'requestSave'));
        add_action('admin_init', array($hooks, 'requestsCustomMetaBox'));
        add_action('admin_menu', array($hooks, 'removePublishMetabox'));
        add_action('add_meta_boxes',  array($hooks, 'addRequestsCustomMetabox'));

        

        // Public Hooks
        add_action('wp_enqueue_scripts', 		 array($hooks, 'enqueuePublicStyles'));
        add_action('wp_enqueue_scripts', 		 array($hooks, 'enqueuePublicScripts'));
        add_action('upload_mimes', 				 array($hooks, 'allowJSON'));
        add_action('init', 						 array($hooks, 'autoDeleteUnsubscribers'));
        add_action('AIO_GDPR_collect_user_data', array($hooks, 'collectUserData'));
        add_action('wp_head', 					 array($hooks, 'remoteScripts'));
        add_action('wp_footer', 				 array($hooks, 'cookieNotice'));
        add_filter('AIOGDPR_userPermissions',    array('AIOGDPR_UserPermissions', 'getCurrentUserPermisisons'));
    }
}