<?php


class AIOGDPR_DataCollecter{

	public $email;
	public $firstName;
	public $lastName;

	public $user 		= NULL;
	public $totalFound 	= 0;
	public $data 		= array();
	public $identifiers = array();

	public $sensativeKeys = array(
		// Generic
		'first_name', 'last_name', 'email', 'phone',
		'age', 'company', 'gender', 'nickname',
 
		// IP
		'ip', 'ip_address',

		// Address
		'zip', 'zip_code', 'post_code', 'postcode',
		'county', 'city', 'state', 'address',
		'country',

		// WooCommerce
		'billing_first_name', 'billing_last_name',
		'billing_company', 'billing_address_1',
		'billing_city', 'billing_postcode',
		'billing_country', 'billing_email',
		'billing_phone',
		'shipping_first_name', 'shipping_last_name',
		'shipping_company', 'shipping_address_1',
		'shipping_city', 'shipping_postcode',
		'shipping_country', 'shipping_email',
		'shipping_phone',
	);


	public function __construct($email, $firstName = '', $lastName = ''){
		$this->email = $email;

		if(!empty($firstName)){
			$this->addIdentifier($firstName, 'name');
			$this->firstName = $firstName;
		}

		if(!empty($lastName)){
			$this->addIdentifier($lastName, 'name');
			$this->lastName = $lastName;
		}

		$this->user = get_user_by('email', $this->email);
		if(is_a($this->user, 'WP_User')){
			$this->addIdentifier($this->user->user_login, 		'login');
			$this->addIdentifier($this->user->user_nicename, 	'name');
			$this->addIdentifier($this->user->user_email, 		'email');
			$this->addIdentifier($this->user->user_url, 		'url');
			$this->addIdentifier($this->user->display_name, 	'name');
			$this->addIdentifier(str_replace('www.', '', @parse_url($this->user->user_url)['host']), 'url');
		}
	}


	//======================================================================
	// Subject Access Request
	//======================================================================
	public function sar(){
		do_action('AIOGDPR_before_sar', $this->email, $this->firstName, $this->lastName, $this->user);
		$this->localSAR();
		$this->integrationsSAR();
		do_action('AIOGDPR_after_sar', $this->email, $this->firstName, $this->lastName, $this->user);
	}

	public function localSAR(){
		global $wpdb;

		if(!is_a($this->user, 'WP_User')){
			return;
		}

		//====================================================
		// WP_User
		//====================================================
		$this->addData($this->user->user_login, 	'username');
		$this->addData($this->user->user_nicename, 	'name');
		$this->addData($this->user->user_url, 		'url');
		$this->addData($this->user->display_name, 	'name');

		//====================================================
		// WP_Usermeta
		//====================================================
		foreach(get_user_meta($this->user->ID) as $metaKey => $meta){
			if($this->isSensativeKey($metaKey)){
				foreach($meta as $metaValue){
					switch($metaKey){
						case 'age':
							$this->addData($metaValue, 'age');
							break;

						case 'gender':
							$this->addData($metaValue, 'gender');
							break;

						case 'address':
							$this->addData($metaValue, 'address');
							break;
						
						default:
							$this->addData($metaValue);
							break;
					}
				}
			}
		}
		

		//====================================================
		// WP_Comments
		//====================================================
		$comments = get_comments(array('user_id' => $this->user->ID));
		foreach($comments as $comment){
			$this->addData($comment->comment_author, 		'name');
			$this->addData($comment->comment_author_email, 	'email');
			$this->addData($comment->comment_author_url, 	'url');
			$this->addData($comment->comment_author_IP, 	'ip');
		}
    }

	public function integrationsSAR(){
        foreach(AIOGDPR::getEnabledIntegrations() as $integration){
            if(method_exists($integration, 'onSubjectAccessRequest')){
                try{
                    $data = $integration->onSubjectAccessRequest($this->email, $this->firstName, $this->lastName, $this->user);
                    if(is_array($data)){
                    	foreach($data as $value){
                    		$this->addData($value, 'misc', $integration->name);
                    	}
                    }
                }catch(Exception $e){

                }
            }
        }
	}


	//======================================================================
	// Forget Me
	//======================================================================
	public function forget(){
		if(!is_a($this->user, 'WP_User')){
			return;
		}

		global $wpdb;

		$userID = wp_update_user(array(
			'ID' 					=> $this->user->ID,
			'user_nicename' 		=> 'Deleted User',
			'user_url' 				=> '',
			'display_name' 			=> 'Deleted User',
			'nickname' 				=> 'Deleted User',
			'first_name' 			=> 'Deleted',
			'last_name' 			=> 'User',
			'description' 			=> '',
			'rich_editing' 			=> '',
			'user_registered' 		=> '',
			'role' 					=> '',
			'jabber' 				=> '',
			'aim' 					=> '',
			'yim' 					=> '',
			'show_admin_bar_front' 	=> '',
		));

		$wpdb->get_results(
			$wpdb->prepare("
				UPDATE $wpdb->users
				SET 
					user_login = %s,
					user_email = %s,
					user_pass  = %s
				WHERE ID = {$this->user->ID}",
				'deleted_user_'.wp_generate_password(10, FALSE, FALSE),
				'deleted_user_'.wp_generate_password(10, FALSE, FALSE).'@example.com',
				wp_generate_password(20)
			)
		);

		
		//======================================================================
		// WP_Comments
		//======================================================================
		$wpdb->get_results(
			$wpdb->prepare("
				UPDATE $wpdb->comments
				SET 
					comment_author 			= 'Deleted User',
					comment_author_email 	= %s,
					comment_author_url 		= '',
					comment_author_IP 		= '000.000.000.00'
				WHERE user_id = %d",
				$this->user->ID,
				'deleted_user_'.wp_generate_password(10, FALSE, FALSE).'@example.com'
			)
		);


		//======================================================================
		// WP_UserMeta
		//======================================================================
		$meta = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE user_id = %d", $this->user->ID)
		);

		foreach($meta as $row){
			if($this->isSensativeKey($row->meta_key)){
				$wpdb->get_results(
					$wpdb->prepare("
						UPDATE $wpdb->usermeta
						SET 
							meta_value = 'Deleted'
						WHERE umeta_id  = %d",
						$row->umeta_id
					)
				);
			}
		}
	}

	//======================================================================
	// Helpers
	//======================================================================
	public function addIdentifier($identifier, $type){

		$identifier = strtolower($identifier);

		if(empty($identifier)){
			return;
		}

		if(!in_array($identifier, $this->identifiers)){
			$this->identifiers[] = (object) [
				'value' => $identifier,
				'type' 	=> $type,
			];
		}
	}

	public function addData($data, $type = 'misc', $source = 'database'){
		$data = strtolower($data);

		if(empty($data)){
			return;
		}
		
		if($data === '::1'){
			return;
		}

		foreach($this->data as $d){
			if($d->data === $data){
				return;
			}
		}

		if($type === 'misc'){
			$guessedType = $this->guessDataType($data);
			$type = ($guessedType !== FALSE)? $guessedType : 'misc';
		}

		$this->data[] = (object) array(
			'data' 		=> $data,
			'type' 		=> $type,
			'source'	=> $source,
		);

		$this->totalFound++;
	}

	public function guessDataType($data){
		$data = strtolower($data);

		// IP Addresses
		preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $data, $IPAddresses);
		foreach($IPAddresses[0] as $ip){
			return 'ip';
		}

		// Email
		preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $data, $emailAdresses);
		foreach($emailAdresses[0] as $emailAddress){
			return 'email';
		}

		// Post Code
		preg_match_all('/((GIR 0AA)|((([A-PR-UWYZ][0-9][0-9]?)|(([A-PR-UWYZ][A-HK-Y][0-9][0-9]?)|(([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][A-HK-Y][0-9][ABEHMNPRVWXY])))) [0-9][ABD-HJLNP-UW-Z]{2}))/i', strtoupper($data), $postCodes);
		foreach($postCodes[0] as $postCode){
			return 'post_code';
		}

		// Phone Number
		preg_match_all('/^[().+\d -]{5,15}$/', $data, $phoneNumbers);
		foreach($phoneNumbers[0] as $phoneNumber){
			if(strlen($phoneNumber) >= 7){
				return 'phone_number';
			}
		}

		// URLs
		preg_match_all('#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si', $data, $URLs);
		foreach($URLs[0] as $url){
			if(!empty($url)){
				if(strpos($url, @parse_url($url)['host']) === FALSE){
					if(filter_var($url, FILTER_VALIDATE_URL)){
						return 'url';
					}
				}
			}
		}

		// Address
		if(strpos($data, 'road')   !== FALSE ||
		   strpos($data, 'street') !== FALSE){
			return 'address';
		}

		foreach($this->identifiers as $key => $identifier){
			if($identifier->value == $data){
				return $identifier->type;
			}
		}

		return FALSE;
	}

	public function getData(){
		$done = array();
		$data = array();

		foreach($this->data as $d){
			if(!in_array($d->data, $done)){
				$done[] = $d->data;
				$data[] = $d;
			}
		}

		return $data;
	}

	public static function formatDataType($key){
		switch($key){
			case 'ip':
				return 'IP';
				break;

			case 'post_code':
				return 'Post Code';
				break;
			
			default:
				return ucwords(str_replace('_', ' ', $key));
				break;
		}

		return $key;
	}

	public function getDataByType(){
		$done = array();
		$data = array();

		foreach($this->data as $d){
			if(!in_array($d->data, $done)){
				$done[] = $d->data;

				if(!isset($data[$d->type])){
					$data[$d->type] = array();
				}

				$data[$d->type][] = $d;
			}
		}

		return $data;
	}

	public function isSensativeKey($key){
		return in_array($key, $this->sensativeKeys);
	}


	public function searchStringForKnownIdentifiers($string){

		$identifiers = array_unique($this->identifiers);

		foreach($identifiers as $identifier){
			if(strpos($string, $identifier) !== FALSE){
				$this->addData($string);
			}
		}
	}

}