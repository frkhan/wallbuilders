<?php

class AIOGDPR_Settings{

	public static $defaults = array(
		/////////////////////////////////////
		// Setup
		/////////////////////////////////////
		'show_setup' 					=> '1',
		'setup_stage'					=> '1',
		'has_rurn_init'					=> '0',


		'license_key_error' 			=> '0',
		'api_key' 						=> '',
		'admin_email' 					=> '',
		'installs' 						=> '0',

		'privacy_center_page'			=> '0',



		/////////////////////////////////////
		// DPO
		/////////////////////////////////////
		'dpo_email',
		'dpo_first_name',
		'dpo_last_name',
		'dpo_user_id',
		


		/////////////////////////////////////
		// Privacy Center
		/////////////////////////////////////
		'privacy_center_intro'	=> 'Welcome to our privacy center, on this page you have tools to control how we use your personal data. If you have any questions or specific requests please contact our Data Protection Officer directly using the form below.',
		
		
		/////////////////////////////////////
		// SAR
		/////////////////////////////////////
		'request_archive_form_description' => 'If you submit this form we will send you a report outlining all of the data we currently possess on you.',
		'sar_cron'						=> '1',
		'skip_unsubscribe_confirmation' => '1',
		'sar_auto_respond' => '1',


		/////////////////////////////////////
		// Forget Me
		/////////////////////////////////////
		'dpo_notification_forget_me' => '1',
		'forget_me_form_description' => 'If you submit a forget-me request, also known as an erasure request, we will delete all of the data we currently possess on you. Caution, this can not be undone.',


		/////////////////////////////////////
		// Unsubscribe
		/////////////////////////////////////
		'dpo_notification_unsubscribe'			=> '1',
		'auto_process_unsubscribe_requests' 	=> '1',
		'unsubscribe_form_description' 			=> 'Please use this form if you would like to be unsubscribed from all of our email marketing lists.',


		/////////////////////////////////////
		// Third-party Services
		/////////////////////////////////////
		'user_permissions_page' 		=> '0',


		/////////////////////////////////////
		// Cookie Notice
		/////////////////////////////////////
		'display_cookie_notice' 		=> '1',
		'cookie_notice_token'			=> '00000',
		'cookie_notice' 				=> "We use cookies to personalise site content, for social media features and to analyse our traffic. We also share information about your use of this site with our advertising and social media partners.",


		/////////////////////////////////////
		// Terms Conditions
		/////////////////////////////////////
		'terms_conditions' 				=> '',
		'terms_conditions_page' 		=> '0',
		'terms_conditions_version' 		=> '1',
		'terms_conditions_hash' 		=> '',


		/////////////////////////////////////
		// Privacy Policy
		/////////////////////////////////////
		'privacy_policy' 		 		=> '',
		'privacy_policy_page' 	 		=> '0',
		'privacy_policy_version' 		=> '1',
		'privacy_policy_hash' 	 		=> '',
	);


	public static function init(){
		$users = get_users(array('role' => 'administrator'));
		$admin = (isset($users[0]))? $users[0] : FALSE;
		if(!self::get('admin_email')){
			if($admin){
				self::set('admin_email', $admin->user_email);
			}
		}
		
		self::set('privacy_policy', 'Add your privacy policy here!');
		self::set('terms_conditions', 'Add your terms conditions here!');

		if(class_exists('AIOGDPR_Service')){
			AIOGDPR_Service::insert(array(
				'name'		=> 'Google Analytics',
				'reason'	=> 'We use Google Analytics to monitor our website traffic.',
				'script'	=> "<script>\n
					window.dataLayer = window.dataLayer || [];\n
					function gtag(){dataLayer.push(arguments);}\n
					gtag('js', new Date());\n
					\n
					gtag('config', 'UA-XXXXXXXXXX-1');\n
				</script>",
				'tc_link'	=> 'https://www.google.com/analytics/terms/us.html',
				'type' 		=> 'optional',
			));
		}


		AIOGDPR_Settings::set('cookie_notice_token', wp_generate_password(20, FALSE, FALSE));


		foreach(self::$defaults as $setting => $value){
			if(!self::get($setting)){
				self::set($setting, $value);
			}
		}
	}

	public static function set($property, $value){
		update_option('AIO_GDPR_'.$property, $value);
	}

	public static function get($property){
		$value = get_option('AIO_GDPR_'.$property);

		if($value !== '0'){
			if(!$value || empty($value)){
				$value = @self::$defaults[$property];
			}
		}

		return $value;
	}

	public static function delete($property){
		delete_option('AIO_GDPR_'.$property);
	}
}
