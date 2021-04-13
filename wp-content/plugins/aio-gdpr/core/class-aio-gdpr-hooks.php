<?php

class AIOGDPR_Hooks{

	//======================================================================
	// Admin Hooks
	//======================================================================

	public function enqueueAdminStyles(){
		wp_enqueue_style(AIOGDPR_VERSION, plugin_dir_url(dirname(__FILE__)). 'admin/assets/css/aio-gdpr-admin.css', array(), AIOGDPR_VERSION, 'all');
	}

	public function enqueueAdminScripts(){
		wp_enqueue_script(AIOGDPR_VERSION, plugin_dir_url(dirname(__FILE__)). 'admin/assets/js/aio-gdpr-admin.js', array('jquery'), AIOGDPR_VERSION, FALSE);
	}

    public static function adminMenuItem(){
        $svg = 'data:image/svg+xml;base64,'. base64_encode(file_get_contents(AIOGDPR::pluginDir('admin/assets/images/logo.svg')));
		add_menu_page('All-in-One GDPR', 'All-in-One GDPR',  'manage_options', 'aio-gdpr', array('AIOGDPR_Hooks', 'view'), $svg);
		add_submenu_page('aio-gdpr', 'Terms & Conditions',  'Terms & Conditions', 'manage_options', 'admin.php?page=aio-gdpr&tab=terms-conditions');
		add_submenu_page('aio-gdpr', 'Privacy Policy',  'Privacy Policy', 'manage_options', 'admin.php?page=aio-gdpr&tab=privacy-policy');
		add_submenu_page('aio-gdpr', 'Integrations',  'Integrations', 'manage_options', 'admin.php?page=aio-gdpr&tab=integrations');
		add_submenu_page('aio-gdpr', 'DPO',  'DPO', 'manage_options', 'admin.php?page=aio-gdpr&tab=dpo');
		add_submenu_page('aio-gdpr', 'About',  'About', 'manage_options', 'admin.php?page=aio-gdpr&tab=about');
    }
    public static function view(){
		$tabs = array_merge(array(
			'overview' 					=> new AIOGDPR_OverviewAction,
			'single-request'			=> new AIOGDPR_SingleRequestAction,
			'privacy-center' 			=> new AIOGDPR_PrivacyCenterAction,
			'dpo' 					    => new AIOGDPR_DPOAdminAction,
			'log' 					    => new AIOGDPR_LogAction,
			'cookie-notice'     		=> new AIOGDPR_CookieNoticeAction,
			'user-privacy' 				=> new AIOGDPR_ServicesAction,
			'subject-access-request' 	=> new AIOGDPR_AdminSubjectAccessRequestAction,
			'unsubscribe' 				=> new AIOGDPR_UnsubscribeAdminAction,
			'forget-me' 				=> new AIOGDPR_ForgetMeAdminAction,
			'terms-conditions'  		=> new AIOGDPR_TermsConditionsAction,
			'privacy-policy'    		=> new AIOGDPR_PrivacyPolicyAction,
			'integrations'				=> new AIOGDPR_IntegrationsAction,
			'setup' 					=> new AIOGDPR_SetUpAction,
		),
		AIOGDPR::integrationsTabs(),
		array(
			'about'				=> new AIOGDPR_AboutAction,
		));

		include AIOGDPR::pluginDir('admin/base.php');
	}

	public function addCustomPostStates($states, $post){
		$pages = array(
			AIOGDPR_Settings::get('privacy_center_page') 	 => 'Privacy Center',
		);

	    if(in_array($post->ID, array_keys($pages))){
			$states[] =  $pages[$post->ID]; 
	    } 

    	return $states;
	} 
	
	public function showSuperUnsubscribeButton($user){
		if( $user->first_name == '' ){
			$first_name = $user->display_name;
			$last_name = '';
		} else {
			$first_name = $user->first_name;
			$last_name = $user->last_name;
		}

		// check if already sent
        $unsubscriber = AIOGDPR_Request::finder('email', array(
            'email' => $user->user_email
        ));

		?>
			<table class="form-table">
				<tbody>
					<tr class="user-profile-picture">
						<th>Forget User</th>
						<td>
							<a class="button" href="<?= SuperUnsubscribeAction::url(array('email' => $user->user_email, 'first_name' => $first_name, 'last_name' => $last_name, 'is_admin' => true )) ?>">
								Forget
							</a>
							<p class="description">This will detele the user and delete all of his/her data stored on this site.</p>
						</td>
					</tr>

					<?php if(count($unsubscriber) > 0){ ?>
						<tr class="user-description-wrap">
							<th><label for="description">Email Sent Count:</label></th>
							<td>
								<?php echo count($unsubscriber);?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php
	}

	public function showPermissonStatus($user){
		?>
			<h1>All-in-One GDPR</h1>
			<table class="form-table">
				<tbody>
					<tr class="user-description-wrap">
						<th><label for="description">Consent</label></th>
						<td>
							<?php if(AIOGDPR_UserPermissions::hasConsent($user->ID) !== FALSE): ?>
								<span class="dashicons dashicons-yes"></span>
							<?php else: ?>
								<span class="dashicons dashicons-no"></span>
							<?php endif; ?>
						</td>
					</tr>

					<tr class="user-profile-picture">
						<th>Service Consent</th>
						<td>
							<ul>
								<?php foreach(AIOGDPR_Service::all() as $service): ?>
									<li>
										<strong><?= $service->name ?>:</strong> 
										<?php if(AIOGDPR_UserPermissions::hasUserGivenPermissionFor($service->slug)): ?>
											<span class="dashicons dashicons-yes"></span>
										<?php else: ?>
											<span class="dashicons dashicons-no"></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</td>
					</tr>

					<tr class="user-profile-picture">
						<th>Withdraw Concent</th>
						<td>
							<a href="<?= AIOGDPR_WithdrawConsentAction::url(array('user' => $user->ID)) ?>" class="button">Withdraw</a>
							<p class="description">This will show show the cookie notice to this user again.</p>
						</td>
					</tr>

				</tbody>
			</table>
		<?php
	}


	//======================================================================
	// Public Hooks
	//======================================================================
	public function enqueuePublicStyles(){
		wp_enqueue_style(AIOGDPR_NAME.'frmwrk', plugin_dir_url(dirname(__FILE__)) .'public/assets/css/aio-gdpr.css', array(), AIOGDPR_VERSION, 'all');
	}

	public function enqueuePublicScripts(){
		wp_enqueue_script(AIOGDPR_NAME, plugin_dir_url(dirname(__FILE__)) .'public/assets/js/aio-gdpr-public.js', array('jquery'), AIOGDPR_VERSION, FALSE);
	}
	
	public function cookieNotice(){
		if(AIOGDPR_Settings::get('display_cookie_notice') === '1'):
			if(!AIOGDPR_UserPermissions::hasSeenCookieNotice()):
				?>

				
					<div class="aio-gdpr">
						<div class="cookie-notice" id="aiogdpr-cookie-notice"  style="display: none;" ajaxurl="<?= AIOGDPR_ConsentAction::url(array('consent' => '1', 'is_ajax' => '1')) ?>">
							<div class="cookie-notice-message">
								<p><?= AIOGDPR_Settings::get('cookie_notice') ?></p>
							</div>

							<div class="gdpr-btn btn-privacy-center">
								<a href="<?= get_permalink(AIOGDPR_Settings::get('privacy_center_page')) ?>">Privacy Center</a>
							</div>
							
							<div class="gdpr-btn btn-accept">
								<a href="<?= AIOGDPR_ConsentAction::url(array('consent' => '1', 'is_cookie_notice' => '1')) ?>">Accept</a>
							</div>
						</div>
					</div>


				<?php
			else:
				?>
					<!-- Cookie notice seen -->
				<?php
			endif;
		else:
			?>
				<!-- Cookie notice disabled -->
			<?php
		endif;
	}

	public function remoteScripts(){
		if(!AIOGDPR_UserPermissions::hasConsent()){
			foreach(AIOGDPR_Service::all() as $service){
				if($service->default_setting == '1'){
					echo $service->script;
				}
			}
		}else{
			foreach(AIOGDPR_Service::all() as $service){
				if(AIOGDPR_UserPermissions::hasUserGivenPermissionFor($service->slug) || $service->is_required == '1'){
					echo $service->script;
				}else{
					echo sprintf('<!-- %s blocked by All-in-One GDPR -->', $service->name);
				}
			}
		}
	}

	public function allowJSON($mime_types){
		$mime_types['json'] = 'application/json';
		return $mime_types;
	}



	//======================================================================
	// Request CPT
	//======================================================================
	public static function getFullName($userID){
		$user = get_user_by('ID', $userID);
		if(is_a($user, 'WP_User')){
			return get_user_meta($user->ID, 'first_name', TRUE) .' '.
				get_user_meta($user->ID, 'last_name', TRUE);
		}
	}

	public function setCustomEditRequestColumns($columns){
		unset($columns['author']);
		unset($columns['date']);
		$columns['email'] = __('Email');
		$columns['status'] = __('Status');
		$columns['request_type'] = __('Request Type');
		$columns['ago'] = __('Date');
		$columns['assigned_to'] = __('Assigned To');
		return $columns;
	}

	public function customRequestColumn($column, $post_id){
		$request = AIOGDPR_Request::find($post_id);
		if(is_null($request)){
			return;
		}

		switch($column){
			case 'assigned_to':
				if(!$request->assigned_to){
					?>
						<a href="<?= AIOGDPR_AssignToMeAction::url(array('request_id' => $request->ID)) ?>">Assign To Me</a>
					<?php
				}else{
					echo $request->assignedToName;
				}
				break;
				
			case 'request_type':
				echo $request->humanType;
				break;
				
			case 'email':
				echo $request->email;
				break;
				
			case 'status':
				echo $request->human_status;
				break;
				
			case 'ago':
				echo $request->ago;
				break;

		}
	}

	public function removePublishMetabox(){
		remove_meta_box('submitdiv', 'aiogdpr_request', 'side');
	}
	
	public function addRequestsCustomMetabox(){
		add_meta_box(
			'request_overview_meta_box',
			'Overview',
			array($this, 'requestOverviewMetaBox'),
			'aiogdpr_request',
			'side'
		);

		add_meta_box(
			'request_sar_data_meta_box',
			'User Data',
			array($this, 'requestSARDataMetaBox'),
			'aiogdpr_request',
			'side'
		);		
		
		add_meta_box(
			'request_info_meta_box',
			'Request Details',
			array($this, 'requestDetailsMetaBox'),
			'aiogdpr_request'
		);	
		
		add_meta_box(
			'request_timeline',
			'Request Timeline',
			array($this, 'requestTimelineMetaBox'),
			'aiogdpr_request'
		);	
	}

	public function requestOverviewMetaBox($post){
		$request = AIOGDPR_Request::find($post->ID);
		include AIOGDPR::pluginDir('core/templates/request-overview.php');
	}

	public function requestSARDataMetaBox($post){
		$request = AIOGDPR_Request::find($post->ID);
		include AIOGDPR::pluginDir('core/templates/request-sar-data.php');
	}

	public function requestDetailsMetaBox($post){
		$request = AIOGDPR_Request::find($post->ID);
		include AIOGDPR::pluginDir('core/templates/request-details.php');
	}	

	public function requestTimelineMetaBox($post){
		$request = AIOGDPR_Request::find($post->ID);
		include AIOGDPR::pluginDir('core/templates/request-timeline.php');
	}

	public function requestsCustomMetaBox(){
		add_meta_box("reservation_date_metabox", "Reservation Date", array($this, "show_reservation_meta"), "post", "normal", "high");
	}

	public function show_reservation_meta(){
		global $post;
		$reservation_date = get_post_meta($post->ID, '_reservation_date', TRUE);
		?>
			<input id="reservation_date" type="text" value="<?php echo $reservation_date; ?>" name="reservation_date">
		<?php
	}

	public function requestSave(){
		$action = new AIOGDPR_AjaxAction;
		if($action->has('post_ID')){
			$request = AIOGDPR_Request::find($action->get('post_ID'));
			$request->assigned_to = $action->get('assigned_to');
			$request->saveMeta();


			if($action->has('add_note')){
				AIOGDPR_TimelineItem::insert(array(
					'request_id' => $request->ID,
					'title' 	 => userFullName(get_current_user_id()),
					'content' 	 => $action->get('add_note'),
				));
			}	
		}

		
	}
}


function userFullName($userID){
	$user = get_user_by('ID', $userID);
	if(is_a($user, 'WP_User')){
		return get_user_meta($user->ID, 'first_name', TRUE) .' '.
			get_user_meta($user->ID, 'last_name', TRUE);
	}
}

function sarDataTable($data){
	if(is_array($data)){
		$header = array(
			'<table style="width:100%">',
				'<tr>',
					'<td><strong>Type</strong></td>',
					'<td><strong>Data</strong></td>',
				'</tr>',
		);

		$content = array();
		foreach($data as $type => $rows){
			$line = '<tr>'.
				'<td>'. AIOGDPR_DataCollecter::formatDataType($type) .'</td> '.
				'<td>';

			$l = array();
			foreach($rows as $row){
				$l[] = $row->data;
			}
			$line .= implode(', ', $l);
					
			$line .= '</td>'.
			'</tr>';

			$content[] = $line;
		}
		
		$footer = array('</table>');
		return implode('', $header) . implode('', $content) . implode('', $footer);
	}
}