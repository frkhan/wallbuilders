<?php

/**
 * Unsubscribe Process:
 * 1) new
 * 2) sent - Confirmination link sent
 * 3) complete - User unsubscribed
 * 
 * Forget Me Process:
 * 1) new
 * 2) sent - Confirmination link sent
 * 3) complete - User deleted
 * 
 * SAR Process:
 * 1) new
 * 2) responded - reponded
 * 
 * Contact DPO:
 * 1) new
 */

Class AIOGDPR_Request extends AIOGDPR_Model {

	public $postType = 'aiogdpr_request';
	public $attributes = array(
		'first_name',
		'last_name',
		'email',
		'token',
		'type',
		'status',
		'v2_id',

		// V5
		'message',
		'assigned_to',
		'is_forgotten',
		'is_unsubscribed',
		'is_sar_sent',
		'sar_has_collected',
		'sar_total_found',
		'sar_user_data',
		'confirmation_link_sent'
	);
	public $default = array(
		'status' => 'new',
		'confirmation_link_sent' => '0',
		'is_forgotten' => '0',
		'is_unsubscribed' => '0',
		'is_sar_sent' => '0',
		'sar_has_collected' => '0',
	);
	public $virtual = array(
		'ago',
		'human_status',
		'humanType',
		'assignedToName',
	);


	
	//======================================================================
	// Virtual
	//======================================================================
	public function _getHuman_status(){
		switch($this->status){
			case 'sent':
				return 'Confirmation Email Sent';
				break;
			
			default:
				return ucfirst($this->status);
				break;
		}
	}
	public function _getHumanType(){
		switch($this->type){
			case 'forget-me':
				return 'Forget Me';
				break;
				
			case 'sar':
				return 'Subject Access Request';
				break;
				
			case 'unsubscribe':
				return 'Unsubscribe';
				break;

			case 'contact-dpo':
				return 'Contact';
				break;
			
			default:
				return ucfirst($this->type);
				break;
		}
	}

	public function _getAssignedToName(){
		$user = get_user_by('ID', $this->assigned_to);
		if(is_a($user, 'WP_User')){
			return get_user_meta($user->ID, 'first_name', TRUE) .' '.
				get_user_meta($user->ID, 'last_name', TRUE);
		}
	}

	public function _getAgo(){
		return human_time_diff(strtotime($this->post()->post_date), current_time('timestamp')) .' ago';
	}


	//======================================================================
	// Misc
	//======================================================================
	public function getTimeline(){
		return AIOGDPR_TimelineItem::finder('request', array(
			'request_id' => $this->ID,
		));
	}

	public function isLinkSent(){
		return ($this->confirmation_link_sent == '1');
	}
	

	//======================================================================
	// Hooks
	//======================================================================
	public function inserting(){
		$this->token = wp_generate_password(20, FALSE, FALSE);
		$this->title = $this->first_name .' '. $this->last_name;
	}

	public function inserted(){
		AIOGDPR_TimelineItem::insert(array(
			'request_id' => $this->ID,
			'title' => 'Request created',
		));

		switch($this->type){
			case 'forget-me':
				if(function_exists('wp_create_user_request')){
					$requestID = wp_create_user_request($this->email, 'remove_personal_data');
					$this->v2_id = $requestID;
					$this->save();
				}
				$this->sendDPOForgetMeNotification();
				$this->sendForgetMeConfirmationLink();
				break;

			case 'sar':
				if(function_exists('wp_create_user_request')){
					$requestID = wp_create_user_request($this->email, 'export_personal_data');
					$this->v2_id = $requestID;
					$this->save();
				}
				$this->sendDPOSARNotification();
				if(AIOGDPR_Settings::get('sar_auto_respond') == '1'){
					$this->perfromSubjectAccessRequest();
				}
				break;

			case 'unsubscribe':
				$this->sendDPOUnsubscribeNotification();
				$this->sendConfirmUnsubscribeLink();	
				break;
		}
	}

	//======================================================================
	// Forget Me
	//======================================================================
	public function sendDPOForgetMeNotification(){
		if(AIOGDPR_Settings::get('dpo_notification_forget_me') == '1'){
			$email = AIOGDPR_Mail::init()
				->to(AIOGDPR_Settings::get('dpo_email'))
				->subject('New Forget Me Request')
				->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
				->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
				->template(AIOGDPR::pluginDir('core/emails/ForgetMeNotification.php'), array(
					'dpoName'       => AIOGDPR_Settings::get('dpo_first_name'),
					'first_name'    => $this->first_name,
					'last_name'     => $this->last_name,
					'email'         => $this->email,
				))
				->send();
		}
	}
	
	public function sendForgetMeConfirmationLink(){
		$email = AIOGDPR_Mail::init()
			->to($this->email)
			->subject('Confirm Forget Me Request - '. parse_url(home_url(), PHP_URL_HOST))
			->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
			->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
			->template(AIOGDPR::pluginDir('core/emails/ConfirmForgetMe.php'), array(
				'website' 		=> parse_url(home_url(), PHP_URL_HOST),
				'name' 			=> $this->first_name,
				'confirmLink'  	=> AIOGDPR_ConfirmForgetMeAction::url(array(
					'token'		=> $this->token
				)),
			))
			->send();

		$this->status = 'sent';
		$this->confirmation_link_sent = '1';
		$this->save();


		AIOGDPR_TimelineItem::insert(array(
			'request_id' => $this->ID,
			'title' => 'Confrimation link sent',
			'content' => "A confirmation link has been emailed to <strong>{$this->email}</strong>. <br> When the link is clicked all of this individual's data will be removed from this WordPress site.",
		));
	}

	public function forgetSubject(){
		$dataCollecter = new AIOGDPR_DataCollecter($this->email, $this->first_name, $this->last_name);
		$dataCollecter->forget();

		AIOGDPR::callOnAllIntegrations('onForgetMe', array($this->email, $this->first_name, $this->last_name));

		$this->status = 'complete';
		$this->is_forgotten = '1';
		$this->save();

		AIOGDPR_TimelineItem::insert(array(
			'request_id' => $this->ID,
			'title' 	 => 'User removed',
			'content' 	 => 'All of the data relating to this individual that All-in-One GDPR could find has been deleted.',
		));
	}

	public function isForgotten(){
		return ($this->is_forgotten == '1');
	}

	//======================================================================
	// Unsubscribe
	//======================================================================
	public function sendDPOUnsubscribeNotification(){
		if(AIOGDPR_Settings::get('dpo_notification_unsubscribe') == '1'){
			$email = AIOGDPR_Mail::init()
				->to(AIOGDPR_Settings::get('dpo_email'))
				->subject('New Unsubscribe Request')
				->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
				->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
				->template(AIOGDPR::pluginDir('core/emails/UnsubscribeNotification.php'), array(
					'dpoName'       => AIOGDPR_Settings::get('dpo_first_name'),
					'first_name'    => $this->first_name,
					'last_name'     => $this->last_name,
					'email'         => $this->email,
				))
				->send();
		}
	}

	public function sendConfirmUnsubscribeLink(){
		$email = AIOGDPR_Mail::init()
			->to($this->email)
			->subject('Confirm Unsubscribe Request')
			->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
			->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
			->template(AIOGDPR::pluginDir('core/emails/ConfirmUnsubscribe.php'), array(
				'name' 			=> $this->first_name,
				'confirmLink'  	=> AIOGDPR_ConfirmUnsubscribeAction::url(array(
					'token'		=> $this->token
				)),
			))
			->send();
		
		$this->status = 'sent';
		$this->save();
	}
	
	public function unsubscribeSubject(){
		AIOGDPR::callOnAllIntegrations('onUnsubscribe', array($this->email, $this->first_name, $this->last_name));
		$this->update(array(
			'status' => 'complete',
			'is_unsubscribed' => '1'
		));

		AIOGDPR_TimelineItem::insert(array(
			'request_id' => $this->ID,
			'title' 	 => 'User unsubscribed',
			'content' 	 => 'This user has been successfully unsubscribed from your integrations.',
		));
	}

	public function isUnsubscribed(){
		return ($this->is_unsubscribed == '1');
	}

	//======================================================================
	// Subject Access Request
	//======================================================================
	public function sendDPOSARNotification(){
		if(AIOGDPR_Settings::get('dpo_notification_sar') == '1'){
			$email = AIOGDPR_Mail::init()
				->to(AIOGDPR_Settings::get('dpo_email'))
				->subject('New Subject Access Request')
				->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
				->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
				->template(AIOGDPR::pluginDir('core/emails/SARNotification.php'), array(
					'dpoName'       => AIOGDPR_Settings::get('dpo_first_name'),
					'first_name'    => $this->first_name,
					'last_name'     => $this->last_name,
					'email'         => $this->email,
				))
				->send();
		}
	}

	public function collectData(){
		AIOGDPR_TimelineItem::insert(array(
			'request_id' => $this->ID,
			'title' 	 => 'Search for user data',
			'content' 	 => sarDataTable($this->sar_user_data),
		));

		// SAR - AB: AIOGDPR::callOnAllIntegrations(... done in data collecter
		$dataCollecter = new AIOGDPR_DataCollecter($this->email, $this->first_name, $this->last_name);
		$dataCollecter->sar();
		$this->sar_total_found = $dataCollecter->totalFound;
		$this->sar_user_data = $dataCollecter->getDataByType();
		$this->sar_has_collected = '1';
		$this->save();
	}

	public function perfromSubjectAccessRequest(){
		$this->status = 'in-progress';
		$this->save();

		if($this->sar_has_collected != '1'){	
			$this->collectData();
		}

		// Send Email
		$email = AIOGDPR_Mail::init()
		    ->to($this->email)
		    ->subject('Subject Access Request - '. parse_url(home_url(), PHP_URL_HOST))
		    ->templateHeader(AIOGDPR::pluginDir('core/emails/templates/header.php'))
		    ->templateFooter(AIOGDPR::pluginDir('core/emails/templates/footer.php'))
		    ->template(AIOGDPR::pluginDir('core/emails/SubjectAccessRequest.php'), array(
				'name'			=> $this->first_name,
				'count'			=> $this->sar_total_found,
				'data'			=> $this->sar_user_data,
            ));

		if(AIOGDPR_Settings::get('bcc_dpo')){
			$email->bcc(AIOGDPR_Settings::get('dpo_email'));
		}

		$email->send();
		$this->status = 'complete';
		$this->is_sar_sent = '1';
		$this->save();

		AIOGDPR_TimelineItem::insert(array(
			'request_id' => $this->ID,
			'title' 	 => 'SAR Sent',
			'content' 	 => 'The user was sent the following archive of data: <br><br>'. sarDataTable($this->sar_user_data),
		));
	}

	public function isSARSent(){
		return ($this->is_sar_sent == '1');
	}


	//======================================================================
	// Finders
	//======================================================================
	public function _finderToken($args){
		 return array(
            'meta_query' => array(
                array(
                    'key'	=> 'token',
                    'value' => $args['token']
               	)
            )
        );
	}
	
	public function _postFinderToken($results, $args){
		return @$results[0]; 
	}

	public function _finderEmail($args){
		 return array(
            'meta_query' => array(
                array(
                    'key'	=> 'email',
                    'value' => $args['email']
               	)
            )
        );
	}

	public function _finderStatus($args){
		 return array(
            'meta_query' => array(
                array(
                    'key'	=> 'status',
                    'value' => $args['status']
               	)
            )
        );
	}

	public function _finderType($args){
		$query = array(
            'meta_query' => array(
                array(
                    'key'	=> 'type',
                    'value' => $args['type']
				)
            )
		);
		
		if(isset($args['status'])){
			$query['meta_query'][] = array(
				'key'	=> 'status',
				'value' => $args['status']
			);
		}

		return $query;
	}
}


AIOGDPR_Request::register(array(
	'labels' => array(
		'name'          => __('Requests'),
		'singular_name' => __('Request'),
		'add_new' => __( 'Add New Request' ),
		'add_new_item' => __( 'Add New Request' ),
		'edit' => __( 'Edit Request' ),             
		'edit_item' => __( 'View Request' ),                
		'new_item' => __( 'Add New Request' ),              
		'view' => __( 'View Request' ),         
		'view_item' => __( 'View Request' ),                    
		'search_items' => __( 'Search Requests' ),  
		'not_found' => __( 'No Requests Found' ),
		'not_found_in_trash' => __( 'No Requests found in Trash' ),                                         
	),
	'capabilities' => array(
		'edit_post'          => 'update_core',
		'read_post'          => 'update_core',
		'delete_post'        => 'update_core',
		'edit_posts'         => 'update_core',
		'edit_others_posts'  => 'update_core',
		'delete_posts'       => 'update_core',
		'publish_posts'      => 'update_core',
		'read_private_posts' => 'update_core'
	),
	'description' => __('Websites to be shown in Resources section.'),
	'public' => false,
	'show_ui' => true,
	'publicly_queryable' => true,
	'exclude_from_search' => true,
	'menu_position' => 20,
	'supports' => array('title'),
	'can_export' => true,
	'menu_icon' => 'dashicons-groups',
	'rewrite'     => array( 'slug' => 'requests'),
));


 