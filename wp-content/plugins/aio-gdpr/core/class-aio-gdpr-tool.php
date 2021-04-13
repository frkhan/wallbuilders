<?php

class AIOGDPR_Tool{
    public static function register(){
        $class = get_called_class();
        $self = new $class;
        AIOGDPR_PrivacyCenter::registerTool($self);
    }
}