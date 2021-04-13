<?php

class AIOGDPR_Integration{

	public static function isEnabled($slug){
		return AIOGDPR_Settings::get('is_enabled_'.$slug) === AIOGDPR_Settings::get('integration_enable_key');
	}

	public static function register(){
		$class = get_called_class();
		$app = AIOGDPR::instance();
		$app->registerIntegration(new $class);
	}
}