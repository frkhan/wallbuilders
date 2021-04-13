<?php

class AIOGDPR_PrivacyCenter{

    public $tools = array();
    protected static $instance = NULL;
    protected function __construct(){}
    protected function __clone(){}

    public static function instance(){
        if(!isset(static::$instance)){
            static::$instance = new static;
        }

        return static::$instance;
    }

    public static function registerTool($tool){
        $self = self::instance();
        $self->tools[$tool->slug] = $tool;
    }

    public static function getTools(){ 
		return AIOGDPR_PrivacyCenter::instance()->tools;
    }

    public static function url($params = array()){
        $url  = get_permalink(AIOGDPR_Settings::get('privacy_center_page'));
        if(!empty($params)){
            $url .= '?'. http_build_query($params);
        }
		return $url;
    }
}