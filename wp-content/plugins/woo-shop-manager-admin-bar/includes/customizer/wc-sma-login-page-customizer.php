<?php

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class sma_login_page_customizer {
	// Get our default values	
	public function __construct() {
							
		// Register our sample default controls
		add_action( 'customize_register', array( $this, 'sma_register_sample_default_controls' ) );
		
		// Only proceed if this is own request.		
		if ( ! sma_login_page_customizer::is_own_customizer_request() && ! sma_login_page_customizer::is_own_preview_request() ) {
			return;
		}	
			
		add_action( 'customize_register', array( sma_customizer(), 'sma_add_customizer_panels' ) );
		// Register our sections
		add_action( 'customize_register', array( sma_customizer(), 'sma_add_customizer_sections' ) );	
		
		// Remove unrelated components.
		add_filter( 'customize_loaded_components', array( sma_customizer(), 'remove_unrelated_components' ), 99, 2 );

		// Remove unrelated sections.
		add_filter( 'customize_section_active', array( sma_customizer(), 'remove_unrelated_sections' ), 10, 2 );	
		
		// Unhook divi front end.
		add_action( 'woomail_footer', array( sma_customizer(), 'unhook_divi' ), 10 );

		// Unhook Flatsome js
		add_action( 'customize_preview_init', array( sma_customizer(), 'unhook_flatsome' ), 50  );
		
		add_filter( 'customize_controls_enqueue_scripts', array( sma_customizer(), 'enqueue_customizer_scripts' ) );				
		
		//add_action( 'parse_request', array( $this, 'set_up_preview' ) );	
		
		add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ) );					
	}
	
	public function enqueue_preview_scripts() {	
		wp_enqueue_script('sma-email-preview-scripts', woo_shop_manager_admin()->plugin_dir_url() . 'assets/js/preview-scripts.js', array('jquery', 'customize-preview'), woo_shop_manager_admin()->version, true);
	}
	
	/**
	* Get blog name formatted for emails.
	*
	* @return string
	*/
	public function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}
	
	/**
	 * Checks to see if we are opening our custom customizer preview
	 *
	 * @access public
	 * @return bool
	 */
	public static function is_own_preview_request() {
		return isset( $_REQUEST['sma-login-page-customizer-preview'] ) && '1' === $_REQUEST['sma-login-page-customizer-preview'];
	}
	
	/**
	 * Checks to see if we are opening our custom customizer controls
	 *
	 * @access public
	 * @return bool
	 */
	public static function is_own_customizer_request() {
		return isset( $_REQUEST['email'] ) && $_REQUEST['email'] === 'login_page_customize';
	}

	/**
	 * Get Customizer URL
	 *
	 */
	public static function get_customizer_url($email) {		
		$customizer_url = add_query_arg( array(
			'sma-customizer' => '1',
			'email' => $email,
			'url'                  => urlencode( add_query_arg( array( 'sma-login-page-customizer-preview' => '1' ), home_url( '/' ) ) ),
			'return'               => urlencode( sma_login_page_customizer::get_email_settings_page_url() ),
		), admin_url( 'customize.php' ) );		

	return $customizer_url;
	}		
	
	/**
	 * Get WooCommerce email settings page URL
	 *
	 * @access public
	 * @return string
	 */
	public static function get_email_settings_page_url() {
		return admin_url( 'admin.php?page=woocommerce_shop_manager_admin_option' );
	}

	/**
	 * Register our sample default controls
	 */
	public function sma_register_sample_default_controls( $wp_customize ) {		
		/**
		* Load all our Customizer Custom Controls
		*/
		require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';
		
		$font_size_array[ '' ] = __( 'Select', 'woocommerce' );
		for ( $i = 10; $i <= 30; $i++ ) {
			$font_size_array[ $i ] = $i."px";
		}
		
		$wp_customize->add_setting( 'sma_general_settings_option[login_page_customize_heading]',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( new SMA_Customize_Heading_Control( $wp_customize, 'sma_general_settings_option[login_page_customize_heading]',
			array(
				'label' => __( 'Login Page customizer', 'advanced-local-pickup-for-woocommerce' ),
				'description' => __( 'This section lets you customize the Login Page.', 'advanced-local-pickup-for-woocommerce' ),
				'section' => 'login_page_customize'
			)
		) );							
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[image_path]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sma_general_settings_option[image_path]',
			array(
               'label'      => __( 'Logo (upload)', 'advanced-local-pickup-for-woocommerce' ),
			   'description' => esc_html__( 'change custom logo in admin login page.', 'advanced-local-pickup-for-woocommerce' ),
               'section'    => 'login_page_customize',
           )
		) );
		
		// Text		
		$wp_customize->add_setting( 'sma_general_settings_option[logo_width]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'sma_general_settings_option[logo_width]',
			array(
				'label' => __( 'Logo Width', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'set custom logo width and maximum limit of width is 320px.', 'advanced-local-pickup-for-woocommerce' ),
				'section' => 'login_page_customize',
				'type' => 'number',
				'input_attrs' => array(
					'min' => 0,
					'max' => 320,
				),
			)
		);
		
		// Text		
		$wp_customize->add_setting( 'sma_general_settings_option[bottom_margin]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'sma_general_settings_option[bottom_margin]',
			array(
				'label' => __( 'Logo Bottom Margin', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'set custom logo width and maximum limit of width is 320px.', 'advanced-local-pickup-for-woocommerce' ),
				'section' => 'login_page_customize',
				'type' => 'number',
			)
		);
		
		// Text		
		$wp_customize->add_setting( 'sma_general_settings_option[sma_border_radius]',
			array(
				'default' => '', 
				'transport' => 'refresh',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'sma_general_settings_option[sma_border_radius]',
			array(
				'label' => __( 'Login box radius', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'set custom radius width and maximum limit of radius is 20px.', 'advanced-local-pickup-for-woocommerce' ),
				'section' => 'login_page_customize',
				'type' => 'number',
			)
		);
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[bg_color]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sma_general_settings_option[bg_color]', 
			array(
				'label'      => __( 'Background Color', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Change background color of login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section'    => 'login_page_customize',
			) 
		) );
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[font_color]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sma_general_settings_option[font_color]', 
			array(
				'label'      => __( 'Text Color', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Change font color in login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section'    => 'login_page_customize',
			) 
		) );
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[link_color]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sma_general_settings_option[link_color]', 
			array(
				'label'      => __( 'Link Color', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Change link color in login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section'    => 'login_page_customize',
			) 
		) );
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[form_font_color]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sma_general_settings_option[form_font_color]', 
			array(
				'label'      => __( 'Login Box Label Color', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Change label font color in login form in login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section'    => 'login_page_customize',
			) 
		) );
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[btn_color]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sma_general_settings_option[btn_color]', 
			array(
				'label'      => __( 'Login Box Button Color', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Change login buttom color in login form in login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section'    => 'login_page_customize',
			) 
		) );
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'sma_general_settings_option[form_bg_color]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sma_general_settings_option[form_bg_color]', 
			array(
				'label'      => __( 'Login Box Background Color', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Change background color of login form in login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section'    => 'login_page_customize',
			) 
		) );
		
		// Text		
		$wp_customize->add_setting( 'sma_general_settings_option[login_footer_text]',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'sma_general_settings_option[login_footer_text]',
			array(
				'label' => __( 'Login Page Footer Text', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( 'Add custom text to the footer of the login page.', 'advanced-local-pickup-for-woocommerce' ),
				'section' => 'login_page_customize',
				'type' => 'text',
			)
		);
	}
		
	/**
	 * Set up preview
	 *
	 * @access public
	 * @return void
	 */
	public function set_up_preview() {

		// Make sure this is own preview request.
		if ( ! sma_login_page_customizer::is_own_preview_request() ) {
			return;
		}
		include woo_shop_manager_admin()->get_plugin_path() . '/includes/customizer/preview/login_page_preview.php';		
		exit;			
	}

}
$sma_login_page_customizer_settings = new sma_login_page_customizer();
