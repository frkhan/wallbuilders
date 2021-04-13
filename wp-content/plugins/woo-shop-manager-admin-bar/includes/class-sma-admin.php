<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class sma_admin {
	
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	
	public function __construct() {
		$this->init();
	}

	/**
	 * Get the class instance
	 *
	 * @return smswoo_admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/*
	 * init function
	 * run hooks
	*/
	public function init(){
		
		//register admin menu
		add_action('admin_menu', array( $this, 'register_woocommerce_menu' ), 100 );
		
		//ajax save: Admin Bar Menu tab
		add_action( 'wp_ajax_sma_update_adminbar_menu', array( $this, 'update_adminbar_menu_callback' ) );	
		
		//ajax save: WordPress Dashboard Widget tab 
		add_action( 'wp_ajax_sma_update_dashboard_widget', array( $this, 'update_dashboard_widget_callback' ) );
		
		//ajax save: General Settings tab 
		add_action( 'wp_ajax_sma_general_settings_form_update', array( $this, 'update_general_settings_callback') );
		//Remove wordpress logo from admin bar 
		add_action( 'wp_before_admin_bar_render', array( $this,'admin_menu_admin_bar_remove_logo'), 0 );
		/*
		* add custom columns of Total Spend in user admin panel
		*/
		add_filter( 'user_contactmethods', array( $this, 'new_total_spend_column'), 10, 1 );
		add_filter( 'manage_users_columns', array( $this, 'new_modify_total_spend_column_table') );
		add_filter( 'manage_users_custom_column', array( $this,'new_modify_total_spend_row_table'), 10, 3 );
		/*
		* add custom columns of Order Count in user admin panel
		*/
		add_filter( 'user_contactmethods', array( $this,'new_order_count_column'), 10, 1 );
		add_filter( 'manage_users_columns', array( $this,'new_modify_order_count_column_table') );
		add_filter( 'manage_users_custom_column', array( $this,'new_modify_order_count_row_table'), 10, 3 );
		/*
		* add custom columns of Signup Date in user admin panel
		*/
		add_filter( 'user_contactmethods', array( $this,'new_signup_date_column'), 10, 1 );
		add_filter( 'manage_users_columns', array( $this,'new_modify_signup_date_column_table') );
		add_filter( 'manage_users_custom_column', array( $this,'new_modify_signup_date_row_table'), 10, 3 );
		add_filter( 'manage_users_sortable_columns', array( $this,'make_signup_date_column_sortable') );
		
		/*call for admin footer text*/
		add_filter('admin_footer_text', array( $this,'change_admin_footer'));
		
		/*call for login page footer text*/
		add_action( 'login_footer', array( $this,'sma_login_page_footer') );
		add_filter('login_headertext',array( $this,'logo_headertitle'), 10, 1); 
		/* change custom logo call */
		add_action( 'login_enqueue_scripts', array( $this,'change_login_page_logo') );
		
		/* change logo URL call */
		add_filter( 'login_headerurl', array( $this,'change_loginlogo_url' ) );
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this , 'my_plugin_action_links' ) );
		add_action( 'template_redirect', array( $this, 'preview_login_page') );	
		$page = isset( $_GET["page"] ) ? $_GET["page"] : "";
		if( $page == 'woocommerce_shop_manager_admin_option' || $page == 'sma' ){
			add_filter( 'admin_body_class', array( $this, 'sma_post_admin_body_class' ), 100 );
		}
		
		/*call for dashboard widgets*/
		add_action( 'wp_dashboard_setup', array( $this,'remove_dashboard_widgets' ), 20);
		add_action( 'wp_head', array( $this,'zorem_woocommerce_admin_bar_style') );
		add_action( 'admin_head', array( $this,'zorem_woocommerce_admin_bar_style') );
		
		/* Code for display admin bar in backend or not  */
		if( get_sma_adminbar('admin_bar_backend', 'yes') == 'yes' ){
			add_action( 'admin_bar_menu', 'sma_adminbar_array', 98 );		
		}
		add_action( 'init', array( $this, 'sma_update_install_callback' ) );
	}

	public function build_html( $template,$data = NULL ) {
		global $wpdb;
		$t = new \stdclass();
		$t->data = $data;
		ob_start();
		include(dirname(__FILE__)."/admin-html/".$template.".phtml");
		$s = ob_get_contents();
		ob_end_clean();
		return $s;
	}
	
	/*
	* Admin Menu add function
	* WC sub menu 
	*/
	public function register_woocommerce_menu() {
		add_submenu_page( 'woocommerce', 'Shop Manager Admin', 'Shop Manager Admin',  'manage_options', 'woocommerce_shop_manager_admin_option', array( $this, 'woocommerce_admin_options_page' ) );
	}
	
	/*
	* woocommerce_admin_options_page
	*/
	public function woocommerce_admin_options_page(){
		$tab = isset( $_GET["tab"] ) ? $_GET["tab"] : "";
		echo $this->build_html( "header" );?>
		<div class="woocommerce sma_admin_layout">
			<div class="sma_admin_content">
				<div class="zorem_sma_tab_name">
					<input id="tab1" type="radio" name="tabs" class="sma_tab_input" data-name="sma_content1" data-label="<?php _e('Settings', 'woocommerce'); ?>" data-tab="settings" checked>
					<label for="tab1" class="sma_tab_label first_label"><?php _e('Settings', 'woocommerce'); ?></label>
					
					<input id="tab2" type="radio" name="tabs" class="sma_tab_input" data-name="sma_content2" data-label="<?php _e('Wordpress Dashboard Widgets', 'woocommerce'); ?>" data-tab="dashboard" <?php if(isset($_GET['tab']) && ($_GET['tab'] == 'dashboard')){ echo 'checked'; } ?>>
					<label for="tab2" class="sma_tab_label"><?php _e('Wordpress Dashboard Widgets', 'woocommerce'); ?></label>
					
					<input id="tab3" type="radio" name="tabs" class="sma_tab_input" data-name="sma_content3" data-label="<?php _e('Admin Bar Menu', 'woocommerce-shop-manager-admin-bar'); ?>" data-tab="admin_menu" <?php if(isset($_GET['tab']) && ($_GET['tab'] == 'admin_menu' || $_GET['tab'] == 'wordpress' || $_GET['tab'] == 'woocommerce' || $_GET['tab'] == 'page-builder' )){ echo 'checked'; } ?>>
					<label for="tab3" class="sma_tab_label"><?php _e('Admin Bar Menu', 'woocommerce-shop-manager-admin-bar'); ?></label>
					
					<input id="tab6" type="radio" name="tabs" class="sma_tab_input" data-name="sma_content_addons" data-label="<?php _e('add-ons', 'woocommerce'); ?>" data-tab="add-ons" <?php if(isset($_GET['tab']) && ($_GET['tab'] == 'add-ons')){ echo 'checked'; } ?>>
					<label for="tab6" class="sma_tab_label"><?php _e('Add-ons', 'woocommerce'); ?></label>
					
				</div>
				<div class="zorem_sma_tab_wraper">	
					<?php echo $this->build_html( "settings_tab" );?>
					<?php echo $this->build_html( "wordpress_dashboard_widget_tab" );?>
					<?php echo $this->build_html( "admin_menu_tab" );?>
					<?php echo $this->build_html( "addons_tab" );?>
				</div>
			</div>
		</div>
		<?php
	}
	
	public function get_html( $sma_general_settings_tab ){
		foreach( (array)$sma_general_settings_tab as $key => $array ){ //echo '<pre>';print_r($array);echo '</pre>';?>
			<tr>
				<td colspan=2 scope="row">
						<label for="<?php echo $key?>">
						<input type="hidden" name="settings_menu[<?php echo $key?>]" value="no">
                        <input name="settings_menu[<?php echo $key?>]" type="checkbox" id="<?php echo $key?>" value="yes" <?php echo get_sma_general_settings( $key , $array['default'] ) == 'yes' ? 'checked' : '' ?> >
                        <?php echo $array['title'] ?>
						<span class="woocommerce-help-tip tipTip" title="<?php echo $array['desc'] ?>" ></span>
					</label>
				</td>
			</tr>
		<?php } 
	}
	
	/**
    * Get the settings tab array for General setting.
    *
    * @return array Array of settings sms_provider.
	*/
	function sma_general_settings_tab() {
		$sma_general_settings_tab = array(
			'display_total_spend'=> array(
				'title'					=> __( 'Display total spend column in user Admin', 'woocommerce-shop-manager-admin-bar' ),
				'default' 				=> 'yes',	
				'desc'					=> __('Enable this option to display total spend column in user Admin .', 'woocommerce-shop-manager-admin-bar')
			),
			'display_signup_date' => array(
				'title'					=> __( 'Display signup date column in user Admin', 'woocommerce-shop-manager-admin-bar' ),	
				'default' 				=> 'yes',	
				'id'					=> 'display_signup_date',
				'type'					=> 'checkbox',
				'desc'					=> __('Enable this option to display singup date column in users .', 'woocommerce-shop-manager-admin-bar')
			),
			'display_order_count' => array(
				'title'					=> __( 'Display order count column in user Admin', 'woocommerce-shop-manager-admin-bar' ),	
				'default' 				=> 'yes',	
				'id'					=> 'display_order_count',
				'type'					=> 'checkbox',
				'desc'					=> __('Enable this option to display order count in users .', 'woocommerce-shop-manager-admin-bar')
			),
			'horizontal_scroll_orders_admin'=> array(
				'title'					=> __( 'Enable horizontal scroll in WooCommerce Orders admin', 'woocommerce-shop-manager-admin-bar' ),	
				'default' 				=> 'yes',	
				'id'					=> 'horizontal_scroll_orders_admin',
				'type'					=> 'checkbox',
				'desc'					=> __('Enable this option to add a Horizontal scroll in orders admin.', 'woocommerce-shop-manager-admin-bar')
			),
			'remove_wordpress_logo' => array(
				'title'					=> __( 'Remove WordPress logo from admin menu & admin bar', 'woocommerce-shop-manager-admin-bar' ),		
				'default' 				=> 'no',	
				'id'					=> 'remove_wordpress_logo',
				'type'					=> 'checkbox',
				'desc'					=> __('Enable this option to remove WordPress logo from admin menu & admin bar.', 'woocommerce-shop-manager-admin-bar')
			),
		);
		return $sma_general_settings_tab;
	}
	
	public function get_html2( $get_dashboard_tab_data ){
		echo '<tbody>';
		foreach( (array)$get_dashboard_tab_data as $key => $array ){ //echo '<pre>';print_r($get_dashboard_tab_data);echo '</pre>';?>
			<tr class="<?php if(isset($array['sub_option'])){ echo 'has_sub_option'; }?>">
				<td><?php echo $array['title']?></td>
				<td class="toggle_td">	
					<?php sma_toggle( "dashboard_widget[{$key}]", $key, get_sma_dashboard_widget( $key ), '' );?>
				</td>
				<td class="toggle_td">
                	<?php sma_toggle( "dashboard_widget[{$key}_sm]", $key.'_sm', get_sma_dashboard_widget( $key.'_sm' ), '' );?>
				</td>					
			</tr>
			<?php if( isset( $array['sub_option'] ) ){
				
				foreach( (array)$array['sub_option'] as $sub_key => $sub_value ){ ?>
					<tr class="sub_option">
						<td><div class="hide_widgets wc-status-child" ><?php echo $sub_value['title']?></div></td>
						<td class="toggle_td sub_option_admin_checkbox">			
                        	<?php sma_toggle( "dashboard_widget[{$sub_key}]", $sub_key, get_sma_dashboard_widget( $sub_key ), '' );?>
						</td>
						<td class="toggle_td sub_option_shopmanager_checkbox">
                        	<?php sma_toggle( "dashboard_widget[{$sub_key}_sm]", $sub_key.'_sm', get_sma_dashboard_widget( $sub_key.'_sm' ), '' );?>
						</td>					
					</tr>	
				<?php }
			}
		}
		echo '</tbody>';
	}	
	
	/*
	* get settings tab array data
	* return array
	*/
	function get_dashboard_tab_data(){
		$dashboard_menu = get_option('sma_dashboard_widget_option', '1');
		$get_dashboard_tab_data = array(	
			'remove_welcome_panel' => array(
				'title'					=> __( 'WordPress Welcome', 'woocommerce-shop-manager-admin-bar' ),		
				'default' 				=> 'yes',				
			),
			'remove_wp_events' => array(
				'title'					=> __( 'WordPress Events and News', 'woocommerce-shop-manager-admin-bar' ),
				'default' 				=> 'yes',	
			),
			'remove_quick_draft' => array(
				'title'					=> __( 'Quick Draft', 'woocommerce-shop-manager-admin-bar' ),		
				'default' 				=> 'yes',	
			),
			'remove_dashboard_right_now' => array(
				'title'					=> __( 'At a Glance', 'woocommerce-shop-manager-admin-bar' ),		
				'default' 				=> 'yes',	
			),
			'remove_dashboard_activity' => array(
				'title'					=> __( 'Activity', 'woocommerce-shop-manager-admin-bar' ),
				'default' 				=> 'yes',	
			),
			'remove_woocommerce_dashboard_status' => array(
				'title'					=> __( 'WooCommerce status', 'woocommerce' ),		
				'default' 				=> 'yes',	
				'sub_option'			=> array(											
												'remove_woocommerce_status_processing' => array(
													'title'					=> __( 'Display processing', 'woocommerce' ),
													'default' 				=> 'yes',	
												),
												'remove_woocommerce_status_onhold' => array(
													'title'					=> __( 'Display on-hold', 'woocommerce' ),
													'default' 				=> 'yes',	
												),	
												'remove_woocommerce_status_stock_info' => array(
													'title'					=> __( 'Display Stock info', 'woocommerce' ),
													'default' 				=> 'yes',	
												),
											),
			),
			'remove_woocommerce_reviews' => array(
				'title'					=> __( 'WooCommerce recent reviews', 'woocommerce' ),
				'default' 				=> 'yes',	
			),
		);
		
		if ( is_plugin_active( 'wordfence/wordfence.php' ) ) {	
			$wordfence_data = array(	
				'remove_wordence_activity' => array(
					'title'					=> __( 'Wordfence activity in the past week', 'woocommerce-shop-manager-admin-bar' ),
					'default' 				=> 'yes',	
				),
			);	
			$get_dashboard_tab_data = array_merge($get_dashboard_tab_data,$wordfence_data);			
		}
		if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {	
			$yoast_seo = array(	
				'remove_yoast_seo_posts' => array(
					'title'					=> __( 'Yoast SEO Posts Overview', 'woocommerce' ),	
					'default' 				=> 'yes',	
				),
			);	
			$get_dashboard_tab_data = array_merge($get_dashboard_tab_data,$yoast_seo);			
		}
		return $get_dashboard_tab_data;
	}

	/*
	* ajax save: adminbar menu tab
	*/
	function update_adminbar_menu_callback(){
		
		check_ajax_referer( 'admin_menu_form_action', 'admin_menu_form_nonce_field' );
		
		if ( empty( $_POST ) ) return;
		
		foreach( $_POST['admin_menu'] as $key => $val ){
			update_sma_adminbar( $key, $val );
		}
		
		echo json_encode( array('success' => 'true') );die();
	}
	
	/*
	* ajax save: Dashboard widget tab
	*/
	function update_dashboard_widget_callback(){			

		check_admin_referer( 'dashboard_form_action', 'dashboard_form_nonce_field' ) ;
		
		if ( empty( $_POST ) ) return;

		foreach( $_POST['dashboard_widget'] as $key => $val ){	
			update_sma_dashboard_widget( $key, $val );
		}	
		
		echo json_encode( array('success' => 'true') );die();
	}
	/*
	* settings form save for General tab
	*/
	function update_general_settings_callback(){			
		
		check_admin_referer( 'general_form_action', 'general_form_nonce_field' ); 
		if ( empty( $_POST ) ) return;
		foreach( $_POST['settings_menu'] as $key => $val ){	
			update_general_settings_widget( $key, $val );
		}
		
		echo json_encode( array('success' => 'true') );die();
	
	}
	
	/* remove wordpress logo from admin bar */
	function admin_menu_admin_bar_remove_logo(){
		global $wp_admin_bar;
		if (get_sma_general_settings('remove_wordpress_logo','no') == 'yes'){
			 $wp_admin_bar->remove_menu( 'wp-logo' );
		}
	}
	
	/*
	* add custom columns of Total Spend in user admin panel
	*/
	function new_total_spend_column( $contactmethods ) {
		
		$contactmethods['sma_total_spend'] = 'Total Spend';
		return $contactmethods;
	}
	
	function new_modify_total_spend_column_table( $column ) {
	
		$display_total_spend = get_sma_general_settings('display_total_spend');
		if($display_total_spend == '' || $display_total_spend == 'no'){	return $column;	}
	
		$column['sma_total_spend'] = 'Total Spend';
		return $column;
	}
	
	function new_modify_total_spend_row_table( $val, $column_name, $user_id ) {
	
		$currency = get_woocommerce_currency();
		switch ($column_name) {
			case 'sma_total_spend' :
				return get_woocommerce_currency_symbol($currency) . wc_get_customer_total_spent( $user_id );
				break;
			default:
		}
		return $val;
	}
	
	/*
	* add custom columns of Order Count in user admin panel
	*/
	function new_order_count_column( $contactmethods ) {
	
		$contactmethods['sma_order_count'] = 'Order Count';
		return $contactmethods;
	}
	
	function new_modify_order_count_column_table( $column ) {
	
		if(get_sma_general_settings('display_order_count') == '' || get_sma_general_settings('display_order_count') == 'no')return $column;
		$column['sma_order_count'] = 'Order Count';
		return $column;
	}
	
	function new_modify_order_count_row_table( $val, $column_name, $user_id ) {
	
		switch ($column_name) {
			case 'sma_order_count' :
				return wc_get_customer_order_count( $user_id );
				break;
			default:
		}
		return $val;
	}
	
	/*
	* add custom columns of Signup Date in user admin panel
	*/
	function new_signup_date_column( $contactmethods ) {
		
		$contactmethods['sma_signup_date'] = 'Signup Date';
		return $contactmethods;
	}
	function new_modify_signup_date_column_table( $column ) {

		if(get_sma_general_settings('display_signup_date') == '' || get_sma_general_settings('display_signup_date') == 'no'){	return $column;	}
		$column['sma_signup_date'] = 'Signup Date';
		return $column;
	}
	function new_modify_signup_date_row_table( $val, $column_name, $user_id ) {
		$date_format = get_option( 'date_format' );
		if($date_format == 'F j, Y') { $date_format = 'M j, Y';}
		$time_format = get_option( 'time_format' );
		switch ($column_name) {
			case 'sma_signup_date' :
				return "<span title=''>".date( $date_format, strtotime( get_the_author_meta( 'registered', $user_id ) ) )."</span>";
				break;
			default:
		}
		return $val;
	}
	/*
	* Make our "Registration date" column sortable
	* @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param} 
	*/
	function make_signup_date_column_sortable( $columns ) {
		return wp_parse_args( array( 'sma_signup_date' => 'registered' ), $columns );
	}
	
	/* 
	* dashboard footer text function 
	*/
	function change_admin_footer(){
		$dashboard_footer_text= get_sma_general_settings('dashboard_footer_text','');
		if(empty( $dashboard_footer_text )){
			echo '<span id="footer-note">Shop Manager Admin by <a href="https://www.zorem.com/?utm_source=wpadmin&utm_medium=SMA&utm_campaign=footer_txt">zorem</a></span>';
		} else {
			echo '<span id="footer-note">'.stripslashes($dashboard_footer_text).'</span>';
		}
	}
	
	function sma_login_page_footer() {
		global $wp_meta_boxes;
		?>
		<div id="login_footer_note">
			<p class="footer-text"><?php echo get_sma_general_settings('login_footer_text',''); ?></p>
		</div>
		<style type="text/css">
			#login_footer_note .footer-text {
				text-align: center;
				margin: 30px auto;
			}
		</style>
		<?php 
	}
	
	function logo_headertitle($login_header_title) {
		$image_path = get_sma_general_settings('image_path','');
		if( !empty($image_path) ) {
			return '<img src="'. $image_path.'">';
		} else {
			return 'https://wordpress.org/';	
		}
	}
	function change_login_page_logo() { 
	
		global $wp_meta_boxes;
		$btn_color = get_sma_general_settings('btn_color');
		?>
		<style type="text/css">
			<?php if( !empty(get_sma_general_settings('bg_color')) ) { ?>
			body.login{
				background: <?php echo get_sma_general_settings('bg_color'); ?>;
			}
			<?php } ?>
			<?php if( !empty(get_sma_general_settings('form_bg_color')) ) { ?>
				body.wp-core-ui #loginform{
					background: <?php echo get_sma_general_settings('form_bg_color'); ?>;
				}
			<?php }  ?>
			<?php if( !empty(get_sma_general_settings('sma_border_radius')) ) { ?>
				 body.wp-core-ui #loginform{
					border-radius: <?php echo get_sma_general_settings('sma_border_radius'); ?>px;
				}
			<?php }  ?>
			
			<?php if( !empty(get_sma_general_settings('form_font_color')) ) { ?>
				body.wp-core-ui #loginform label, body.wp-core-ui #loginform strong, body.wp-core-ui #loginform b {
					color: <?php echo get_sma_general_settings('form_font_color'); ?>;
					font-weight: 400;
				}
			<?php } ?>
			<?php if( !empty(get_sma_general_settings('font_color')) ) { ?>
				body.wp-core-ui #backtoblog a, 
				body.wp-core-ui #nav a, 
				#login_footer_note .footer-text {
					color: <?php echo get_sma_general_settings('font_color'); ?>;	
				}
			<?php } ?> 
			<?php if( !empty(get_sma_general_settings('link_color')) ) { ?>
				body.wp-core-ui #backtoblog a, 
				body.wp-core-ui #nav a {
					color: <?php echo get_sma_general_settings('link_color'); ?>;	
				}
			<?php } ?>
			<?php if( !empty(get_sma_general_settings('image_path', '')) ) { ?>
				body.login div#login h1 a img {
					display: block;
					width: <?php echo get_sma_general_settings('logo_width' , '320' ); ?>px;
					margin: 0 auto;
					max-width:100%;
				}
				body.login div#login h1 a{
					height: auto !important;
					width: auto !important;	
					background: none;
				}
			<?php } ?>
			<?php if( !empty($btn_color) ) { ?>
				.wp-core-ui .button.button-large {
					background: <?php echo $btn_color; ?>;
					border-color: <?php echo $btn_color; ?>;
					box-shadow: 0 1px 0 <?php echo $btn_color; ?>;
					text-shadow: 0 -1px 1px <?php echo $btn_color; ?>, 1px 0 1px <?php echo $btn_color; ?>, 0 1px 1px <?php echo $btn_color; ?>, -1px 0 1px <?php echo $btn_color; ?>;
				}
				.wp-core-ui .button.button-large:hover,
				.wp-core-ui .button.button-large:focus,
				.wp-core-ui .button.button-large:active {
					background: <?php echo $btn_color; ?>;
					border-color: <?php echo $btn_color; ?>;
					
				}
			<?php } ?>
			<?php if( !empty(get_sma_general_settings('bottom_margin')) || get_sma_general_settings('bottom_margin') == '0' ) { ?>
				body.wp-core-ui #loginform{
					 margin-top: 0;
				}
				body.login div#login h1 a {
					margin-bottom: <?php echo get_sma_general_settings('bottom_margin'); ?>px;
				}
			<?php } ?>
		</style>
		
		<?php 
	}
	
	function change_loginlogo_url($url) {
		$image_path = get_sma_general_settings('image_path', '');
		if( !empty($image_path) ) {
			/* Get Home Url of main-site */
			$logo_url = home_url();
			return $logo_url;
		} else {
			return 'https://wordpress.org/';	
		}
	}
	
	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	function my_plugin_action_links( $links ) {
		$links = array_merge( array(
			'<a href="' . esc_url( admin_url( '/admin.php?page=woocommerce_shop_manager_admin_option' ) ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>'
		), $links );
		return $links;
	}
	
	public static function preview_login_page(){
		$action = (isset($_REQUEST["sma-login-page-customizer-preview"])?$_REQUEST["sma-login-page-customizer-preview"]:"");
		if($action != '1')return;		
		wp_head();
		include woo_shop_manager_admin()->get_plugin_path() . '/includes/customizer/preview/login_page_preview.php';		
		//wp_footer();				
		exit;
	}
	
	function sma_post_admin_body_class($body_class) {
		
		$body_class .= ' sma-shop-manager-admin-setting ';
		return $body_class;
	}
	
	/* 
	* Dashboard widgets customize function 
	*/
	function remove_dashboard_widgets() {
		
		global $wp_meta_boxes;

		$current_role = wp_get_current_user(); 

		foreach ($current_role->roles as $key=>$value){
			
			if( $value == 'administrator' ) {
				if( get_sma_dashboard_widget('remove_welcome_panel')!= 'yes' ){
				remove_action('welcome_panel', 'wp_welcome_panel');		
				}
				if(get_sma_dashboard_widget('remove_wp_events') != 'yes'){
					unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);	
				}
				if(get_sma_dashboard_widget('remove_quick_draft') != 'yes'){
					unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
				} 
				if(get_sma_dashboard_widget('remove_dashboard_right_now') != 'yes' ){			
					unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
				}
				if(get_sma_dashboard_widget('remove_dashboard_activity') != 'yes' ){			
					unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
				}
				if(get_sma_dashboard_widget('remove_woocommerce_dashboard_status') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_status']);
				}
				if(get_sma_dashboard_widget('remove_woocommerce_reviews') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_recent_reviews']);
				}
				if(get_sma_dashboard_widget('remove_yoast_seo_posts') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);
				}
				if(get_sma_dashboard_widget('remove_wordence_activity') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['wordfence_activity_report_widget']);
				}	
			}
			if( $value == 'shop_manager' ) {
				if( get_sma_dashboard_widget('remove_welcome_panel_sm')!= 'yes' ){
				remove_action('welcome_panel', 'wp_welcome_panel');		
				}
				if(get_sma_dashboard_widget('remove_wp_events_sm') != 'yes'){
					unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);	
				}
				if(get_sma_dashboard_widget('remove_quick_draft_sm') != 'yes'){
					unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
				} 
				if(get_sma_dashboard_widget('remove_dashboard_right_now_sm') != 'yes' ){			
					unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
				}
				if(get_sma_dashboard_widget('remove_dashboard_activity_sm') != 'yes' ){			
					unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
				}
				if(get_sma_dashboard_widget('remove_woocommerce_dashboard_status_sm') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_status']);
				}
				if(get_sma_dashboard_widget('remove_woocommerce_reviews_sm') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_recent_reviews']);
				}
				if(get_sma_dashboard_widget('remove_yoast_seo_posts_sm') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);
				}
				if(get_sma_dashboard_widget('remove_wordence_activity_sm') != 'yes' ){
					unset($wp_meta_boxes['dashboard']['normal']['core']['wordfence_activity_report_widget']);
				}
			}
		}
	}
	
	function zorem_woocommerce_admin_bar_style(){	
		if(!is_user_logged_in()) return;
		$wsmab_zorem_icon = woo_shop_manager_admin()->plugin_dir_url() .'assets/images/sma-icon.png';
		?>
		
		<style type="text/css">
			#wpadminbar .ab-top-menu > li.menupop.icon-woocommerce > .ab-sub-wrapper > .ab-submenu > .zorem_powered_by{background-image: url('<?php echo $wsmab_zorem_icon; ?>');background-repeat: no-repeat;background-position: 0.85em 50%;padding-left: 30px;background-size: 23px;}
			body.admin-bar #wpadminbar .ab-top-menu > li.menupop.icon-woocommerce > .ab-item{background-image: url('<?php echo $wsmab_zorem_icon; ?>') !important;background-repeat: no-repeat;background-position: 3% 50%;padding-left: 30px !important;background-size: 23px;height: 32px;}
			<?php if(  get_sma_general_settings('horizontal_scroll_orders_admin') == 'yes' ) { ?>
				body.post-type-shop_order #posts-filter {overflow: scroll !important;width: 100% !important;}
			<?php } 
			
			$current_role = wp_get_current_user(); 

			foreach ($current_role->roles as $key=>$value){
			
				if( $value == 'administrator' ) {					
					if( get_sma_dashboard_widget('remove_woocommerce_status_processing') != 'yes' ) { ?>
					#woocommerce_dashboard_status .wc_status_list li.processing-orders {
					display: none;
					}
					<?php } ?>
					<?php if( get_sma_dashboard_widget('remove_woocommerce_status_onhold') != 'yes' ) { ?>
					#woocommerce_dashboard_status .wc_status_list li.on-hold-orders {
					display: none;
					}
					<?php } ?>
					<?php if( get_sma_dashboard_widget('remove_woocommerce_status_stock_info') != 'yes' ) { ?>
					#woocommerce_dashboard_status .wc_status_list li.low-in-stock, #woocommerce_dashboard_status .wc_status_list li.out-of-stock {
					display: none;
					}
					<?php } 
				}
				
				
				if( $value == 'shop_manager' ) {
					if( get_sma_dashboard_widget('remove_woocommerce_status_processing_sm') != 'yes' ) { ?>
						#woocommerce_dashboard_status .wc_status_list li.processing-orders {
						display: none;
						}
					<?php } ?>
					<?php if( get_sma_dashboard_widget('remove_woocommerce_status_onhold_sm') != 'yes' ) { ?>
						#woocommerce_dashboard_status .wc_status_list li.on-hold-orders {
						display: none;
						}
					<?php } ?>
					<?php if( get_sma_dashboard_widget('remove_woocommerce_status_stock_info_sm') != 'yes' ) { ?>
						#woocommerce_dashboard_status .wc_status_list li.low-in-stock, #woocommerce_dashboard_status .wc_status_list li.out-of-stock {
						display: none;
						}
					<?php } 
				}
			}?>
		</style>	
		<?php 
	}
	
	/// order status function ////
	function get_orders_count_from_status( $status ){
		
		global $wpdb;
		// We add 'wc-' prefix when is missing from order staus
		$status = 'wc-' . str_replace('wc-', '', $status);
	
		$count = $wpdb->get_var("
			SELECT count(ID)  FROM {$wpdb->prefix}posts WHERE post_status LIKE '$status' AND `post_type` LIKE 'shop_order'
		");
		//echo $count; exit;
		if ( $count != '0'){ return " (".$count.")"; }
	}
	
	/**
	* function callback for add not existing key in database.
	*
	**/
	function sma_update_install_callback() {
		if(version_compare(get_option( 'sma_setting_migrate' ),'3.3.0', '<') ){
			update_general_settings_widget( 'image_path', get_option('image_path') );
			update_general_settings_widget( 'link_color', get_option('link_color') );
			update_general_settings_widget( 'bottom_margin', get_option('bottom_margin') );
			update_general_settings_widget( 'bg_color', get_option('bg_color') );
			update_general_settings_widget( 'font_color', get_option('font_color') );
			update_general_settings_widget( 'form_font_color', get_option('form_font_color') );
			update_general_settings_widget( 'form_bg_color', get_option('form_bg_color') );
			update_general_settings_widget( 'btn_color', get_option('btn_color') );
			update_general_settings_widget( 'login_footer_text', get_option('login_footer_text') );
			update_option( 'sma_setting_migrate', '3.3.0' );	
		}
	}
}
