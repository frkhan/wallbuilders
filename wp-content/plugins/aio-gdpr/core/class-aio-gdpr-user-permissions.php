<?php


Class AIOGDPR_UserPermissions{

	public static function consent($permissions = TRUE){
		if($permissions === TRUE){
			$all = array_values(AIOGDPR_Service::asList('slug'));
			self::updateCurrentUserPermisisons($all);
		}

		setcookie('wordpress_AIO_GDPR_consent', current_time('timestamp'), (time()+(365*24*60*60)), '/');
		setcookie('wordpress_AIO_GDPR_has_seen_cookie_notice', AIOGDPR_Settings::get('cookie_notice_token'), (time()+(365*24*60*60)), '/');
		
		if(is_user_logged_in()){
			update_user_meta(get_current_user_id(), 'AIO_GDPR_consent', current_time('timestamp'));
			update_user_meta(get_current_user_id(), 'AIO_GDPR_has_seen_cookie_notice', current_time('timestamp'));
		}
	}

	public static function hasConsent($userID = NULL){

		if(is_null($userID)){
			$cookie = @$_COOKIE['wordpress_AIO_GDPR_consent'];
			if(isset($cookie) && (intval($cookie) < current_time('timestamp'))){
				return $cookie;
			}

			if(is_user_logged_in()){
				$meta = get_user_meta(get_current_user_id(), 'AIO_GDPR_consent', TRUE);
				if(!empty($meta) && (intval($meta) < current_time('timestamp'))){
					return $meta;
				}
			}
		}else{

			$meta = get_user_meta($userID, 'AIO_GDPR_consent', TRUE);
			if(!empty($meta) && (intval($meta) < current_time('timestamp'))){
				return $meta;
			}

		}

		return FALSE;
	}

	public static function withdrawConsent($userID = NULL){
	
		if(is_null($userID)){

			self::updateCurrentUserPermisisons();
			setcookie('wordpress_AIO_GDPR_consent', current_time('timestamp'), (time()-(365*24*60*60)), '/');
			setcookie('wordpress_AIO_GDPR_has_seen_cookie_notice', current_time('timestamp'), (time()-(365*24*60*60)), '/');

			if(is_user_logged_in()){
				delete_user_meta(get_current_user_id(), 'AIO_GDPR_consent');
				delete_user_meta(get_current_user_id(), 'AIO_GDPR_has_seen_cookie_notice');
			}
		}else{

			self::updateUserPermisisons($userID, array());

			if(is_user_logged_in()){
				delete_user_meta($userID, 'AIO_GDPR_consent');
				delete_user_meta($userID, 'AIO_GDPR_has_seen_cookie_notice');
			}

		}
	}

	public static function hasUserGivenPermissionFor($slug){
		return in_array($slug, self::getCurrentUserPermisisons());
	}

	public static function hasSeenCookieNotice(){
		$cookie = @$_COOKIE['wordpress_AIO_GDPR_has_seen_cookie_notice'];
		if(isset($cookie) && ($cookie == AIOGDPR_Settings::get('cookie_notice_token'))){
			return TRUE;
		}

		if(is_user_logged_in()){
			$meta = get_user_meta(get_current_user_id(), 'AIO_GDPR_has_seen_cookie_notice', TRUE);
			if(!empty($meta) && ($meta == AIOGDPR_Settings::get('cookie_notice_token'))){
				return TRUE;
			}
		}

		return FALSE;
	}

	public static function getCurrentUserPermisisons(){
		$permissions = array();
		
		if(isset($_COOKIE['wordpress_AIO_GDPR_user_permissions'])){
			$cookie = $_COOKIE['wordpress_AIO_GDPR_user_permissions'];
			$permissions = unserialize(stripslashes($cookie));
		}
		
		if(!isset($permissions) && is_user_logged_in()){
			$permissions = get_user_meta(get_current_user_id(), 'AIO_GDPR_user_permissions', TRUE);
		}

		if(is_array($permissions)){
			return $permissions;
		}	

		return array();
	}
	
	public static function updateCurrentUserPermisisons($permissions = array()){
		if(!is_array($permissions)){
			return;
		}

		if(is_user_logged_in()){
            update_user_meta(get_current_user_id(), 'AIO_GDPR_user_permissions', $permissions);
        }

        setcookie('wordpress_AIO_GDPR_user_permissions', serialize($permissions), (time()+(365*24*60*60)), '/');
	}

	
	public static function updateUserPermisisons($userID, $permissions){
		if(!is_array($permissions)){
			return;
		}

		update_user_meta($userID, 'AIO_GDPR_user_permissions', $permissions);
	}
}