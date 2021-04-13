<?php

/*-----------------------------------------------------------------------------------*/
/*	Constants
/*-----------------------------------------------------------------------------------*/

define('MP_SHORTNAME', 'mp'); // this is used to prefix the individual field id
define('MP_PAGE_BASENAME', 'mp-settings'); // settings page slug
define('MPC_RFBWP_GOOGLE_FONTS_API_ID', 'AIzaSyDp98WtnL2USah3Kgzum2puDPF_CrKBxLY'); // Flipbook Key from mpc.apis

/*-----------------------------------------------------------------------------------*/
/*	Variables
/*-----------------------------------------------------------------------------------*/

global $rfbwp_shortname;
$rfbwp_shortname = "rfbwp";

/*-----------------------------------------------------------------------------------*/
/*	Specify Hooks
/*-----------------------------------------------------------------------------------*/

add_action('init', 'massivepanel_rolescheck' );

function massivepanel_rolescheck () {
	$role = get_role('editor');
	$role->add_cap('rfbwp_plugin_cap');
	$role = get_role('administrator');
	$role->add_cap('rfbwp_plugin_cap');

	if ( current_user_can('rfbwp_plugin_cap') ) {
		// If the user can edit theme options, let the fun begin!
		add_action('admin_menu', 'mp_add_menu');
		add_action('admin_init', 'mp_register_settings');
		add_action('admin_init', 'mp_mlu_init');
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Scripts (JS & CSS)
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_enqueue_scripts', 'mp_notices_style' );
function mp_notices_style() {
	wp_enqueue_style( 'mp_notices', MPC_PLUGIN_ROOT . '/massive-panel/css/notices.css' );
	wp_enqueue_script( 'mp_noties', MPC_PLUGIN_ROOT . '/massive-panel/js/notices.js', array( 'jquery' ), false, true );
}

function mp_settings_scripts(){
	global $rfbwp_shortname;

	wp_enqueue_media();
	wp_enqueue_style('mp_theme_fontawesome', MPC_PLUGIN_ROOT.'/assets/fonts/font-awesome.css');
	wp_enqueue_style('mp_theme_et_icons', MPC_PLUGIN_ROOT.'/assets/fonts/et-icons.css');
	wp_enqueue_style('mp_theme_et_line', MPC_PLUGIN_ROOT.'/assets/fonts/et-line.css');
	wp_enqueue_style('mp_theme_settings_css', MPC_PLUGIN_ROOT.'/massive-panel/css/mp-styles.css');
	wp_enqueue_style('mp_theme_select2_css', MPC_PLUGIN_ROOT.'/massive-panel/select2/select2.css');
	wp_enqueue_style('flipbook_styles', MPC_PLUGIN_ROOT.'/assets/css/style.min.css');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_style('wp-jquery-ui-dialog');

	wp_enqueue_script('wp-color-picker');
	wp_enqueue_script('mp_theme_ace_js', MPC_PLUGIN_ROOT.'/massive-panel/ace/ace.js', array('jquery'), false, true);
	wp_enqueue_script('mp_theme_ace_css_mode', MPC_PLUGIN_ROOT.'/massive-panel/ace/mode-css.js', array('jquery', 'mp_theme_ace_js'), false, true);
	wp_enqueue_script('mp_theme_tinymce_js', MPC_PLUGIN_ROOT.'/massive-panel/tinymce/tinymce.min.js', array('jquery'), false, true);
	wp_enqueue_script('mp_theme_select2_js', MPC_PLUGIN_ROOT.'/massive-panel/select2/select2.min.js', array('jquery'), false, true);

	wp_enqueue_script('mp_theme_icon_select_js', MPC_PLUGIN_ROOT.'/massive-panel/mpc_icon/icon_select/field_icon_select.js', array('jquery', 'jquery-ui-dialog'), false, true );
	wp_enqueue_script('mp_theme_toc_generator_js', MPC_PLUGIN_ROOT.'/massive-panel/mpc_toc/field_toc.js', array('jquery', 'jquery-ui-dialog'), false, true );
	wp_enqueue_script('mp_theme_settings_js', MPC_PLUGIN_ROOT.'/massive-panel/js/mp-scripts.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), false, true);

	wp_enqueue_script('webfonts', '//ajax.googleapis.com/ajax/libs/webfont/1.1.2/webfont.js');

	/* Localize */
	$google_webfonts = get_transient('mpcth_google_webfonts');

	wp_localize_script('mp_theme_settings_js', 'mpcthLocalize', array(
		'optionsName'		=> $rfbwp_shortname,
		'googleAPIErrorMsg' => __('There is problem with access to Google Webfonts. Please try again later. If this message keeps appearing please contact our support at <a href="http://mpc.ticksy.com/">mpc.ticksy.com</a>.', 'mpcth'),
		'googleAPIKey'		=> MPC_RFBWP_GOOGLE_FONTS_API_ID,
		'googleFonts'		=> stripslashes( $google_webfonts ),
		'addNewPage'		=> __('Add New Page', 'rfbwp' ),
		'editPage'			=> __('Edit Page', 'rfbwp' ),
		'previewPage'		=> __('Preview Page', 'rfbwp' ),
		'deletePage'		=> __('Delete Page', 'rfbwp' ),
		'cancelNewPage'		=> __('Cancel', 'rfbwp'),
		'presetsURL'		=> plugin_dir_url( __FILE__ ) . 'presets/',
		'messages'			=> array(
			'errors' => array(
				'lastPage'		=> __( 'last page must be single', 'rfbwp' ),
				'firstPage'		=> __( 'first page must be single', 'rfbwp' ),
				'minPages'		=> __( 'book needs at least 4 pages', 'rfbwp' ),
				'evenPages'		=> __( 'number of pages must be even', 'rfbwp' ),
				'error'			=> __( '<span>ERROR: </span>', 'rfbwp' ),
			),
			'dialogs' => array(
				'maxInputVars'	=> __( 'We are sorry but your changes weren\'t saved. Please increase "max_input_vars" value in your "php.ini" file.', 'rfbwp' ),
				'bottomPage'	=> __( 'Oops! It looks like this page is already at the bottom.', 'rfbwp' ),
				'topPage'		=> __( 'Oops! It looks like this page is already at the top.', 'rfbwp' ),
				'bookSaved'		=> __( 'Book settings saved successfully.', 'rfbwp' ),
				'importFinished'=> __( 'Import has been successfully finished.', 'rfbwp' ),
				'normalLarge'	=> __( "Number of normal and large images don't match.", 'rfbwp' ),
				'noImages'		=> __( 'No images selected to upload.', 'rfbwp' ),
				'bookID'		=> __( 'Wrong book ID.', 'rfbwp' ),
				'selectImage'	=> __( 'Select Image', 'rfbwp' ),
				'insertImage'	=> __( 'Insert Image', 'rfbwp' ),
				'selectImages'	=> __( 'Select Images', 'rfbwp' ),
				'insertImages'	=> __( 'Insert Images', 'rfbwp' ),
				'presetLoaded'	=> __( 'Preset has been loaded.', 'rfbwp' ),
			),
		),
	) );
	
	do_action( 'rfbwp/scripts' );
}


/* ---------------------------------------------------------------- */
/* Cache Google Webfonts
/* ---------------------------------------------------------------- */
if( !function_exists( 'mpcth_cache_google_webfonts' )) {
    add_action('wp_ajax_mpcth_cache_google_webfonts', 'mpcth_cache_google_webfonts');
    function mpc_cache_google_webfonts() {
		$google_webfonts = isset($_POST['google_webfonts']) ? $_POST['google_webfonts'] : '';

		if(!empty($google_webfonts)) {
			set_transient('mpcth_google_webfonts', $google_webfonts, DAY_IN_SECONDS);
		}

		die();
    }
}
/*-----------------------------------------------------------------------------------*/
/*	Admin Menu Page
/*-----------------------------------------------------------------------------------*/

function mp_add_menu(){
	$settings_output = mp_get_settings();

	// This code displays the link to Admin Menu
	$mp_settings_page = add_menu_page( __('Massive Panel Options', 'rfbwp'), __('Flip Books', 'rfbwp'), 'rfbwp_plugin_cap', MP_PAGE_BASENAME, 'mp_settings_page_fn', 'dashicons-book', '100.1' ) ;

	// Hook for Flipbooks submenu
	do_action( 'rfbwp/panelSubmenu' );
	
	// js & css
	add_action( 'load-'.$mp_settings_page, 'mp_settings_scripts' );
	add_action( 'admin_footer', 'mpc_display_icon_select_grid' );
	add_action( 'admin_footer', 'mpc_display_toc_generator_grid' );
}

/* Add icons grid to menu panel */
function mpc_display_icon_select_grid() {
	include_once( plugin_dir_path( __FILE__ ) . 'mpc_icon/icon_select/icon_grid.php' );
}

/* Add toc grid to menu panel */
function mpc_display_toc_generator_grid() {
	include_once( plugin_dir_path( __FILE__ ) . 'mpc_toc/toc_popup.php' );
}

/*-----------------------------------------------------------------------------------*/
/*	Helper function for defininf variables
/*-----------------------------------------------------------------------------------*/

function mp_get_settings() {
	global $rfbwp_shortname;
	//delete_option($rfbwp_shortname.'_options');
	$output = array();
	$output[$rfbwp_shortname.'_option_name'] = $rfbwp_shortname.'_options'; // option name as used in the get_option() call
	$output['mp_page_title'] = ''; // the settings page title

	return $output;
}

// Backup settings from 1.x
function rfbwp_backup_settings() {
	global $rfbwp_shortname;

	$plugin_data = get_plugin_data( MPC_PLUGIN_FILE );
	$old_version = get_option( $rfbwp_shortname . '_version' );
	
	if( !empty( $old_version ) && (int)$old_version >= (int)$plugin_data['Version'] )
		return;
	
	$rfbwp_options = get_option( $rfbwp_shortname . '_options' );
	update_option( $rfbwp_shortname . '_options_backup', $rfbwp_options );
}

// Disable update Warning
add_action('wp_ajax_disable_warning', 'rfbwp_disable_warning');
function rfbwp_disable_warning() {
	global $rfbwp_shortname;
	
	rfbwp_backup_settings();
	
	$plugin_data = get_plugin_data( MPC_PLUGIN_FILE );
	update_option( $rfbwp_shortname . '_version', $plugin_data['Version'] );

	die();
}

function mp_settings_page_fn(){
	global $rfbwp_shortname;
	$settings_output = mp_get_settings();
	$content = mp_display_content();

	$plugin_data = get_plugin_data( MPC_PLUGIN_FILE );
	$disable_warning = get_option( $rfbwp_shortname . '_version' ) == $plugin_data['Version'];

	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2><?php echo $settings_output['mp_page_title']; ?></h2>

		<?php if(!$disable_warning) { ?>
		<div id="update_warning">
			<div class="update_message">
				<h2><?php _e('Welcome to Flipbook ', 'rfbwp'); echo $plugin_data['Version']; ?></h2>
				<p><?php _e('We have rewritten a lot of things here to improve your experience with our plugin. We\'re trying our best to make it issues free but some bugs might still be there waiting to appear. Please backup your Flipbooks before using this new update.', 'rfbwp'); ?></p>
				<p><?php _e('If you found any issues please feel free to send us a support ticket and we will fix it as soon as possible.', 'rfbwp'); ?></p>
				<a id="confirm_update" class="mpc-button" href="#"><?php _e('I understand, dismiss', 'rfbwp'); ?></a>
				<a id="request_backup" class="mpc-button" href="#"><i class="dashicons dashicons-migrate"></i><?php _e('Download Backup', 'rfbwp'); ?></a>
			</div>
		</div>
		<?php } ?>

		<div id="top">
			<div id="top-nav">
				<div class="mpc-logo"></div>
				<?php echo $content[3]; ?>
				<?php echo $content[2]; ?>
			</div><!-- end topnav -->
		</div> <!-- end top -->
		<div id="bg-content">
			<div id="sidebar"><?php echo $content[1]; ?></div>
			<form action="/" id="options-form" name="options-form" method="post">
				<?php
					settings_fields($settings_output[$rfbwp_shortname.'_option_name']);
					echo $content[0];
				?>
				<div class="bottom-nav">
					<div class="mp-line">
						<div class="mp-line-around">
							<input type="hidden" name="action" value="rfbwp_save_settings" />
							<input type="hidden" name="security" value="<?php echo wp_create_nonce('rfbwp-theme-data'); ?>" />

							<input name="mp-submit" class="save-button" type="submit" value="<?php esc_attr_e('Save Settings', 'rfbwp'); ?>" />

							<a class="edit-button" href="#"><?php _e('Save Settings', 'rfbwp'); ?></a>
						</div>
					</div>
				</div>
			</form>
			<div id="rfbwp_tools">
				<header id="rfbwp_tools_toggle_header">
					<a id="rfbwp_tools_toggle_title" href="#"><?php _e('Tools', 'rfbwp'); ?><span class="toggle-arrow"></span></a>
				</header>
				<div id="rfbwp_tools_toggle_content">
					<div class="field">
						<form action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data" method="post">
							<div class="description-top"><?php _e('Import Flipbook settings:', 'rfbwp'); ?> </div>
							<input type="hidden" name="action" value="import_flipbooks">
							<input type="hidden" name="back_url" value="" id="rfbwp_import_back_url">
							<input type="hidden" name="mp-settings" value="Save Page">
							<input type="hidden" name="book_id" id="rfbwp_import_id">
							<input type="file" name="import_flipbooks_file" id="rfbwp_import_file">
							<input type="submit" id="rfbwp_import">
							<a id="rfbwp_flipbook_import" class="mpc-button revert" href="#">
								<i class="dashicons dashicons-upload"></i>
								<?php _e('Import', 'rfbwp'); ?>
							</a>
							<div class="help-icon">
								<span class="mp-tooltip top">
									<?php _e('Use this field to import all Flipbooks and pages settings from previously created backup. <br /><br /> (NOTE: File must have .rfb extension).', 'rfbwp'); ?>
								</span>
							</div>
						</form>
					</div>
					<div class="field">
						<div class="description-top"><?php _e('Export Flipbook settings:', 'rfbwp'); ?> </div>
						<a id="rfbwp_flipbook_export" class="mpc-button revert" href="#">
							<i class="dashicons dashicons-migrate"></i>
							<?php _e('Export', 'rfbwp'); ?>
						</a>
						<div class="help-icon">
							<span class="mp-tooltip top">
								<?php _e('Use this field to export all Flipbooks and pages settings to a file. <br /><br /> (NOTE: This exports only settings. To export used images you will have to use other plugin).', 'rfbwp'); ?>
							</span>
						</div>
					</div>
					<div class="field">
						<div class="description-top"><?php _e('Batch Images upload:', 'rfbwp'); ?> </div>
						<div class="help-icon">
							<span class="mp-tooltip top">
								<?php _e('Use this feature to import multiple images at once. Please notice that the order and amount of Normal and Large images must be exactly the same. <br /><br />If you do not wish to use zoomed (large) images you can left this field empty.', 'rfbwp'); ?>
							</span>
						</div>
						<div class="select-section">
							<input type="hidden" id="rfbwp_flipbook_batch_ids" name="rfbwp_flipbook_batch_ids" value="">
							<a id="rfbwp_flipbook_batch_select" class="mpc-button revert" href="#">
								<i class="dashicons dashicons-format-gallery"></i>
								<?php _e('Select Normal', 'rfbwp'); ?>
							</a>
							<a id="rfbwp_flipbook_batch_clear" class="mpc-button delete-page" href="#0">
								<i class="dashicons dashicons-trash"></i>
								<?php _e('Delete', 'rfbwp'); ?>
							</a>
							<div id="rfbwp_flipbook_batch_images_wrap"></div>
						</div>
						<div class="select-section">
							<input type="hidden" id="rfbwp_flipbook_batch_ids_large" name="rfbwp_flipbook_batch_ids_large" value="">
							<a id="rfbwp_flipbook_batch_select_large" class="mpc-button revert" href="#">
								<i class="dashicons dashicons-format-gallery"></i>
								<?php _e('Select Large', 'rfbwp'); ?>
							</a>
							<a id="rfbwp_flipbook_batch_clear_large" class="mpc-button delete-page" href="#0">
								<i class="dashicons dashicons-trash"></i>
								<?php _e('Delete', 'rfbwp'); ?>
							</a>
							<div id="rfbwp_flipbook_batch_images_wrap_large"></div>
						</div>

						<a id="rfbwp_flipbook_batch_import" class="mpc-button revert" href="#">
							<i class="dashicons dashicons-upload"></i>
							<?php _e('Upload', 'rfbwp'); ?>
						</a>
						<div class="description-top batch-double"><?php _e('Double Pages:', 'rfbwp'); ?> </div>
						<input id="rfbwp_flipbook_batch_double" class="checkbox of-input" type="checkbox" name="rfbwp_flipbook_batch_double" checked="checked"/>

					</div>
				</div>
			</div>
		</div> <!-- end bg-content -->

		<div id="rfbwp_page_preview">
			<div id="rfbwp_page_preview_wrap"></div>
			<div id="rfbwp_page_preview_close"><?php _e('Click anywhere to close.', 'rfbwp') ?></div>
		</div>

	</div><!-- end wrap -->
<?php

}

/*-----------------------------------------------------------------------------------*/
/*	Register settings
/*	This function registers wordpress settings
/*-----------------------------------------------------------------------------------*/

function mp_register_settings() {
	global $rfbwp_shortname;
	require_once('theme-options.php');
	require_once('theme-interface.php');
	require_once('theme-tools.php');
	require_once('mpc-uploader.php');

	$settings_output	= mp_get_settings();
	$mp_option_name		= $settings_output[$rfbwp_shortname.'_option_name'];

	// register panel settings
	register_setting($mp_option_name, $mp_option_name, 'mp_validate_options');
}

/*-----------------------------------------------------------------------------------*/
/*	Validate Input
/*-----------------------------------------------------------------------------------*/

function mp_validate_options($input) {
	// variable
	global $reset;
	global $book_id;
	global $settings;
	// for enphaced security, create new array
	global $valid_input;
	$valid_input = array();
	$type = '';
	$reset = 'false';
	$settings = rfbwp_get_settings();

	if(isset($_POST['mp-settings']) && ($_POST['mp-settings'] == "Edit Settings" || $_POST['mp-settings'] == "Save Page" || $_POST['mp-settings'] == "Save Changes" || $_POST['mp-settings'] == "Delete Page")) {
		$addNewBook = 'false';
		$addNewPage = 'false';

		if($_POST['mp-settings'] == "Save Page")
			$addNewPage = 'true';
		elseif($_POST['mp-settings'] == "Save Changes")
			$addNewPage = 'false';
		elseif($_POST['mp-settings'] == "Delete Page")
			$addNewPage = 'delete';
	} else {
		$addNewBook = 'true';
		$addNewPage = 'false';
	}

	if(isset($_POST['mp-settings']) && $_POST['mp-settings'] == "delete")
		$addNewBook = 'delete';

	// get the settings section array
	$options = mpcrf_options();

	$active_book = isset($_POST['active-book']) ? $_POST['active-book'] : '';
	// if there is a book add another one
	if(isset($settings['books']) && count($settings['books']) > 0)
		$options = mp_duplicate_options($options, $addNewBook, $addNewPage, $active_book);

	$book_id = -1;
	$page_id = 0;
	$path_prefix = '';
	$input_path_prefix = '';

	if(isset($_POST['delete']))
		$dbook_id = $_POST['delete'];
	else
		$dbook_id = '';

	if(isset($_POST['delete-page'])) {
		$dpage_id = $_POST['delete-page'];
		$dbook_id = $_POST['active-book'];
	} else {
		$dpage_id = '';
	}

	// run a foreach and switch on option type
	foreach($options as $option) {
		if( isset($option['sub']) && $option['sub'] == 'settings' )
			$type = 'books';
		elseif( $option['type'] == 'pages' )
			$type = 'pages';
		elseif( $option['type'] == 'section' )
			$type = '';

		// delete if there is only one book
		if(count($input['books']) < 2 && $dbook_id != '' && $dpage_id == '') {
			continue;
		}

		if($type == 'books' && $option['type'] == 'heading' && isset($option['sub']) && $option['sub'] == 'settings') {
			$book_id ++;
			$page_id = -1;
		}

		if($type == 'pages' && $option['type'] == 'separator')
			$page_id++;

		// delete book id
		if($dbook_id != '' && $book_id >= $dbook_id && $dpage_id == ''){
			$input_book_id = $book_id + 1;
		} else {
			$input_book_id = $book_id;
		}

		// delete page id
		if($dpage_id != '' && $page_id >= $dpage_id && $dbook_id == $book_id) {
			$input_page_id = $page_id + 1;
		} else {
			$input_page_id = $page_id;
		}

		if(isset($option['id']) && $option['type'] != 'separator' && $option['type'] != 'heading' && $option['type'] != 'info' && $option['type'] != 'export-button' && $type != 'pages' && $input_book_id > -1) {
			$input_value = $input['books'][$input_book_id][$option['id']];
		} elseif(isset($option['id']) && $option['type'] != 'separator' && $option['type'] != 'heading' && $option['type'] != 'pages' && $type == 'pages' && $input_book_id > -1) {
			if(isset($input['books'][$input_book_id]['pages'][$input_page_id][$option['id']]))
				$input_value = $input['books'][$input_book_id]['pages'][$input_page_id][$option['id']];
			else
				continue;
		}

		switch($option['type']) {
			case 'text-big':
			case 'text-medium':
			case 'text-small':
				//switch validation based on the class
				switch($option['validation']){
					//for numeric
					case 'numeric':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}

						$input_value = trim($input_value); // this trims the whitespace
						$valid_input_value = $input_value;
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					// multi-numeric values separated by comma
					case 'multinumeric':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}
						//accept the input only when the numeric values are comma separated
						$input_value = trim($input_value); // trim whitespace
						if($input_value != '') {
							// /^-?\d+(?:,\s?-?\d+)*$/ matches: -1 | 1 | -12,-23 | 12,23 | -123, -234 | 123, 234  | etc.
							$valid_input_value = $valid_input_value = (preg_match('/^-?\d+(?:,\s?-?\d+)*$/', $input_value) == 1) ? $input_value : __('Expecting comma separated numeric values','rfbwp');
						} else {
							$valid_input_value = $input_value;
						}

						// register error
						if($input_value !='' && preg_match('/^-?\d+(?:,\s?-?\d+)*$/', $input_value) != 1) {
							add_settings_error(
								$option['id'], // setting title
								MP_SHORTNAME . '_txt_multinumeric_error', // error ID
								__('Expecting comma separated numeric values!','rfbwp'), // error message
								'error' // type of message
							);
						}
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					//no html
					case 'nohtml':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}
						//accept the input only after stripping out all html, extra white space etc!
						$input_value = sanitize_text_field($input_value); // need to add slashes still before sending to the database
						// FIX
						$input_value = stripslashes($input_value);
						$valid_input_value = addslashes($input_value);
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					//for url
					case 'url':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}
						//accept the input only when the url has been sanited for database usage with esc_url_raw()
						$input_value 		= trim($input_value); // trim whitespace
						$valid_input_value = esc_url_raw($input_value);
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					//for email
					case 'email':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}
						//accept the input only after the email has been validated
						$input_value = trim($input_value); // trim whitespace
						if($input_value != ''){
							if(is_email($input_value)!== FALSE) {
								$valid_input_value = $input_value;
							} else {
								$valid_input_value = __('Invalid email! Please re-enter!','rfbwp');
							}
						} elseif($input_value == '') {
							$valid_input_value = __('This setting field cannot be empty! Please enter a valid email address.','rfbwp');
						}

						// register error
						if(is_email($input_value)== FALSE || $input_value == '') {
							add_settings_error(
								$option['id'], // setting title
								MP_SHORTNAME . '_txt_email_error', // error ID
								__('Please enter a valid email address.','rfbwp'), // error message
								'error' // type of message
							);
						}
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					// a "cover-all" fall-back when the class argument is not set
					default:
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}
						// accept only a few inline html elements
						$allowed_html = array(
							'a' => array('href' => array (),'title' => array ()),
							'b' => array(),
							'em' => array (),
							'i' => array (),
							'strong' => array()
						);

						$input_value 		= trim($input_value); // trim whitespace
						$input_value 		= force_balance_tags($input_value); // find incorrectly nested or missing closing tags and fix markup
						$input_value 		= wp_kses( $input_value, $allowed_html); // need to add slashes still before sending to the database
						// FIX
						$input_value = stripslashes($input_value);
						$valid_input_value = addslashes($input_value);
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;
				}
			break;

			case 'textarea-big':
			case 'textarea':
				//switch validation based on the class!
				switch ( $option['validation'] ) {
					//for only inline html
					case 'inlinehtml':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
							break;
						}
						// accept only inline html
						$input_value 		= trim($input_value); // trim whitespace
						$input_value 		= force_balance_tags($input_value); // find incorrectly nested or missing closing tags and fix markup
						// FIX
						$input_value = stripslashes($input_value);
						$input_value 		= addslashes($input_value); //wp_filter_kses expects content to be escaped!
						$valid_input_value = wp_filter_kses($input_value); //calls stripslashes then addslashes
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					//for no html
					case 'nohtml':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							break;
						}
						//accept the input only after stripping out all html, extra white space etc!
						$input_value 		= sanitize_text_field($input_value); // need to add slashes still before sending to the database
						// FIX
						$input_value = stripslashes($input_value);
						$valid_input_value = addslashes($input_value);
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					//for allowlinebreaks
					case 'allowlinebreaks':
						if($reset == "true"){
							$valid_input_value = $option['std'];
							break;
						}
						//accept the input only after stripping out all html, extra white space etc!
						$input_value 		= wp_strip_all_tags($input_value); // need to add slashes still before sending to the database
						// FIX
						$input_value = stripslashes($input_value);
						$valid_input_value = addslashes($input_value);
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;

					// a "cover-all" fall-back when the class argument is not set
					default:
						// accept only limited html
						if($reset == "true"){
							$valid_input_value = $option['std'];
							break;
						}

						//my allowed html
						$allowed_html = array(
							'a' 			=> array('href' => array (),'title' => array ()),
							'b' 			=> array(),
							'blockquote' 	=> array('cite' => array ()),
							'br' 			=> array(),
							'dd' 			=> array(),
							'dl' 			=> array(),
							'dt' 			=> array(),
							'em' 			=> array(),
							'i' 			=> array(),
							'li' 			=> array(),
							'ol' 			=> array(),
							'p' 			=> array(),
							'q' 			=> array('cite' => array ()),
							'strong' 		=> array(),
							'ul' 			=> array(),
							'h1' 			=> array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()),
							'h2' 			=> array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()),
							'h3' 			=> array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()),
							'h4' 			=> array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()),
							'h5' 			=> array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()),
							'h6' 			=> array('align' => array (),'class' => array (),'id' => array (), 'style' => array ())
						);

						$input_value 		= trim($input_value); // trim whitespace
						// FIX
						$input_value = stripslashes($input_value);
						$valid_input_value = addslashes($input_value);
						set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;
				}
			break;

			// settings that doesn't really require validation
			case 'upload':
			case 'upload-file':
			case 'info':
			case 'multi-checkbox':
			case 'select':
			case 'font_select':
			case 'choose-portfolio':
			case 'choose-sidebar':
			case 'choose-sidebar-small':
			case 'choose-image':
			case "radio" :
				if($reset == "false"){
					if(isset($option['id']) && isset($input_value))
						$valid_input_value = $input_value;
				} elseif (isset($option['id']) && isset($option['std'])) {
					$valid_input_value = $option['std'];
				} else {
					$valid_input_value = '';
				}
				set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
			break;

			case 'multi-upload':
				if(isset( $input_value)){
					$valid_input_value = $input_value;
				} else {
					$valid_input_value = '';
				}
				set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
			break;

			// color picker validation
			case 'color':
				if($reset == "false"){
					//if(validate_hex($input_value)) {
						$valid_input_value = $input_value;
					/*} else {
						$valid_input_value = $option['std'];
						add_settings_error(
						$option['id'], // setting title
							MP_SHORTNAME . '_color_hex_error', // error ID
						__('Please insert HEX value.','rfbwp'), // error message
							'error' // type of message
						);
					}*/
				} else {
					$valid_input_value = $option['std'];
				}
				set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
			break;

			// checkbox validation
			case 'checkbox-ios':
			case 'checkbox':
				// if it's not set, default to null!
				if($reset == "true"){
					if(isset($option['std'])) {
						$valid_input_value = $option['std'];
					} else {
						$valid_input_value = "";
					}
					set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
					break;
				}
				if (!isset($input_value) || $input_value === 0) {
					$input_value = null;
				}
				// Our checkbox value is either 0 or 1
				if($input_value == 1 || $input_value == 'on'){
					$valid_input_value = "1";
				} else {
					$valid_input_value = "0";
				}

				set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
			break;

                        case 'icon':
                                $valid_input_value = $input_value;
				set_valid_input($type, $valid_input_value, $book_id, $option['id'], $page_id);
                        break;
		}
	}

	$_POST['delete'] = '';

	return $valid_input; // returns the valid input;
}

function set_valid_input($type, $value, $book_id, $id, $page_id){
	global $valid_input;

	if($type == 'books'){
		$valid_input['books'][$book_id][$id]  = $value;
	} elseif($type == 'pages') {
		$valid_input['books'][$book_id]['pages'][$page_id][$id]  = $value;
	}
}


/* Helper function for HEX validation */
function validate_hex($hex) {
	//echo $hex;
	$hex = trim($hex);
	// Strip recognized prefixes.
	if (0 === strpos( $hex, '#')) {
		$hex = substr( $hex, 1 );
	} elseif ( 0 === strpos( $hex, '%23')) {
		$hex = substr($hex, 3);
	}
	//echo $hex;
	// Regex match.
	if (0 === preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
		return false;
	} else {
		return true;
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Callback function for displaying admin messages
/*	@return calls mp_show_msg()
/*-----------------------------------------------------------------------------------*/

function mp_admin_msgs(){
	// check for settings page
	if(isset($_GET['page']))
		$mp_settings_pg = strpos($_GET['page'], MP_PAGE_BASENAME);
	else
		$mp_settings_pg = FALSE;

	// collect setting error/notices
	$set_errors = get_settings_errors();

	// display admin message only for the admin to see, only on our settings page and only when setting errors/notices ocur
	if(current_user_can('manage_options') && $mp_settings_pg !== FALSE && !empty($set_errors)){
		// have the settings been updates succesfully
		if($set_errors[0]['code'] == 'settings_updated' && isset($_GET['settings-updated'])) {
			mp_show_msg("<p>".$set_errors[0]['message']."</p>", 'updated');
		} else { // have errors been found?
			// loop through the errors
			foreach($set_errors as $set_error) {
				// set the title attribute to match the error "setting title" - need this in js file
				mp_show_msg("<p class='setting-error-message' title='".$set_error['setting']."'>".$set_error['message']."</p>", "error");

			}
		}
	}
}

// admin hook for notices
add_action('admin_notices', 'mp_admin_msgs');


/*-----------------------------------------------------------------------------------*/
/*	This is Helper function which displayes admin messages
/*	@param (string) $message The echoed message
/*	@param (string) $msgclass The message class: info, error, succes ect.
/*	@return echoes the message
/*-----------------------------------------------------------------------------------*/

function mp_show_msg($message, $msgclass = 'info') {
	echo "<div id='message' class='$msgclass'>$message</div>";
}

// save settings
add_action('wp_ajax_save_settings', 'rfbwp_save_settings');
function rfbwp_save_settings() {
	global $rfbwp_shortname;
	$option_name = 'rfbwp_options';
	$options_new = array();
	$array_names = array();
	$options = get_option($option_name);

	if( isset($_POST['activeID']) ) $_POST['active-book'] = $_POST['activeID'];
	if( isset($_POST['pageID']) ) $_POST['delete-page'] = $_POST['pageID'];
	$data = $_POST['data'];
	$type = $_POST['updating']; // book, edit_page, new_page
	$pageID = isset($_POST['curPageID']) ? $_POST['curPageID'] : 0;
	$bookID = $_POST['activeID'];

	if( isset($_POST['moveDir']) ) $move_dir = $_POST['moveDir'];

	if($_POST['value'] != "")
		$_POST['mp-settings'] = $_POST['value'];

	for($i = 0; $i < count($data); $i++) {
		// get the array names
		$array_names = array();
		$name = preg_split('/rfbwp_options/', $data[$i]['name']);
		if(isset($name[1]))
			$name = preg_split('/[\[\]]+/', $name[1]);

		// remove empty strings
		foreach($name as $na) {
			if($na != '') {
				array_push($array_names, $na);
			}
		}

		if(count($array_names) > 1 && !isset($options_new[$array_names[0]]))
			$options_new[$array_names[0]] = array();

		if(count($array_names) > 2 && !isset($options_new[$array_names[0]][$array_names[1]]))
			$options_new[$array_names[0]][$array_names[1]] = array();

		if(count($array_names) > 3 && !isset($options_new[$array_names[0]][$array_names[1]][$array_names[2]]))
			$options_new[$array_names[0]][$array_names[1]][$array_names[2]] = array();
		elseif(count($array_names) == 3)
			$options_new[$array_names[0]][$array_names[1]][$array_names[2]] = $data[$i]['value'];

		if(count($array_names) > 4 && !isset($options_new[$array_names[0]][$array_names[1]][$array_names[2]][$array_names[3]]))
			$options_new[$array_names[0]][$array_names[1]][$array_names[2]][$array_names[3]] = array();

		if(count($array_names) == 5)
			$options_new[$array_names[0]][$array_names[1]][$array_names[2]][$array_names[3]][$array_names[4]] = $data[$i]['value'];
	}

	if($type == 'edit_page') {
		$options['books'][$bookID]['pages'][$pageID] = $options_new['books'][$bookID]['pages'][$pageID];
	} elseif($type == 'new_page' || $type == 'delete_page') {
		if($type == 'new_page')
			$multi = 1;
		else
			$multi = -1;

		$page_type = $options_new['books'][$bookID]['pages'][$pageID]['rfbwp_fb_page_type'];
		$inc_val = $page_type == 'Double Page' ? 2 : 1;
		$new_index = 0;

		foreach ($options['books'][$bookID]['pages'] as $single_page) {
			if($new_index >= $pageID)
				$options['books'][$bookID]['pages'][$new_index]['rfbwp_fb_page_index'] = (int)$single_page['rfbwp_fb_page_index'] + $inc_val * $multi;

			$new_index++;
		}

		$original = $options['books'][$bookID]['pages'];

		if($type == 'new_page') {
			$inserted = $options_new['books'][$bookID]['pages'];
			array_splice( $options['books'][$bookID]['pages'], $pageID, 0, $options_new['books'][$bookID]['pages'] );
		} else {
			$options['books'][$bookID]['pages'][$pageID] = array();
		}

	} elseif($type == 'first_page') {
		if(!isset($options['books'][$bookID]['pages']))
			$options['books'][$bookID]['pages'] = array();

		$options['books'][$bookID]['pages'][$pageID] = $options_new['books'][$bookID]['pages'][$pageID];
	} elseif($type == 'book') {
		if(isset($options['books'][$bookID])) {
			foreach($options['books'][$bookID] as $key => $value) {
				if($key != 'pages') {
					if(isset($options_new['books'][$bookID][$key]))
						$options['books'][$bookID][$key] = $options_new['books'][$bookID][$key];
					else
						unset($options['books'][$bookID][$key]);
				}
			}
		} else {
			$options['books'][$bookID] = $options_new['books'][$bookID];
		}
	} else if( $type == 'edit_pages' ) {
		unregister_setting($option_name, $option_name, 'mp_validate_options');

		$options['books'][$bookID]['pages'] = $options_new['books'][$bookID]['pages'];
		update_option($option_name, $options);

		register_setting($option_name, $option_name, 'mp_validate_options');

		die();
	} elseif($type == 'move_page') {
		if($move_dir == 'move_up')
			$multi = 1;
		elseif($move_dir == 'move_down')
			$multi = -1;
		else
			return;

		$page_type = $options_new['books'][$bookID]['pages'][$pageID]['rfbwp_fb_page_type'];
		$inc_val = $page_type == 'Double Page' ? 2 : 1;
		$moved_page = $options['books'][$bookID]['pages'][$pageID];
		$moving_page = $options['books'][$bookID]['pages'][$pageID + $multi];

		$moved_page['rfbwp_fb_page_index'] = (int)$moved_page['rfbwp_fb_page_index'] + $inc_val * $multi;
		$moving_page['rfbwp_fb_page_index'] = $options_new['books'][$bookID]['pages'][$pageID]['rfbwp_fb_page_index'];

		$options['books'][$bookID]['pages'][$pageID] = $moving_page;
		$options['books'][$bookID]['pages'][$pageID + $multi] = $moved_page;
	} elseif($type == 'delete_page') {
		$page_type = $options_new['books'][$bookID]['pages'][$pageID]['rfbwp_fb_page_type'];
		$inc_val = $page_type == 'Double Page' ? 2 : 1;
		$new_index = 0;

		foreach ($options['books'][$bookID]['pages'] as $single_page) {
			if($new_index >= $pageID)
				$options['books'][$bookID]['pages'][$new_index]['rfbwp_fb_page_index'] = (int)$single_page['rfbwp_fb_page_index'] - $inc_val;

			$new_index++;
		}

		$original = $options['books'][$bookID]['pages'];
		array_splice( $options['books'][$bookID]['pages'], $pageID, 1 );
	}

	echo $option_name;

	update_option($option_name, $options);

	die();
}

// delete book
add_action( 'wp_ajax_delete_book', 'rfbwp_delete_book' );
function rfbwp_delete_book() {
	global $rfbwp_shortname;
	$option_name = 'rfbwp_options';
	$options = get_option($option_name);

	$_POST['mp-settings'] = 'delete';
	$bookID = $_POST['id'];
	$_POST['delete'] = $bookID;

	$options['books'][$bookID] = array();

	update_option($option_name, $options);

	die();
}

add_action('wp_ajax_set_active_book', 'rfbwp_set_active_book');
function rfbwp_set_active_book() {
	$_POST['active-book'] = $_POST['activeID'];

	die();
}


/*-----------------------------------------------------------------------------------*/
/*	Flipbook Extensions
/*-----------------------------------------------------------------------------------*/
require_once( 'extensions.php' );

/*-----------------------------------------------------------------------------------*/
/*	Flipbook Notices
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_rfbwp_notice_dismiss', 'rfbwp_notice_dismiss' );
function rfbwp_notice_dismiss() {
	$notice_id = addslashes( $_POST[ 'notice' ] );
	
	if( !empty( $notice_id ) && !get_option( $notice_id ) )
		add_option( $notice_id, true );
	
	echo true;
	die();
}
