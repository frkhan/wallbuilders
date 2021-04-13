<?php 
/**
 * @wordpress-plugin
 * Plugin Name: Shop Manager Admin for WooCommerce
 * Plugin URI:  https://www.zorem.com/shop
 * Description: Save time managing your WooCommerce shop! Shop Manager Admin adds a customizable WooCommerce quick-links menu to the WordPress admin bar (frontend & backend).
 * Version:     3.4.2
 * Author:      zorem
 * Author URI:  http://www.zorem.com/
 * License:     GPL-2.0+
 * License URI: http://www.zorem.com/
 * Text Domain: woocommerce-shop-manager-admin-bar
 * WC tested up to: 5.0
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class woo_shop_manager_admin {
	
	/*
	 * @var string
	 */
	public $version = '3.4.2';
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		if ( !$this->is_wc_active() ) return;
		$this->includes();
		$this->init();
	}
	
	/**
	 * Check if WC is active
	 *
	 * @access private
	 * @since  1.0.0
	 * @return bool
	*/
	
	private function is_wc_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}
		
		// Do the WC active check
		if ( false === $is_active ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
		}		
		return $is_active;
	}
	
	/**
	 * Display WC active notice
	 *
	 * @access public
	 * @since  1.0.0
	*/
	public function notice_activate_wc() {
		?>
		<div class="error">
			<p><?php printf( __( 'Please install and activate %sWooCommerce%s for Shop Manager Admin for WooCommerce!', 'woocommerce-shop-manager-admin-bar' ), '<a href="' . admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}
	
	public function includes(){
		
		//sma library
		require_once $this->get_plugin_path() . '/includes/class-sma-library.php';
		
		//sma menubar array
		require_once $this->get_plugin_path() . '/includes/class-sma-menubar-array.php';
		
		//admin ui
		require_once $this->get_plugin_path() . '/includes/class-sma-admin.php';
		$this->admin = sma_admin::get_instance();
		
	}
	
	/*
	* include file on plugin load
	*/
	public function on_plugins_loaded() {
		require_once $this->get_plugin_path() . '/includes/customizer/sma-customizer.php';				
		require_once $this->get_plugin_path() . '/includes/customizer/wc-sma-login-page-customizer.php';
	}
	
	/*
	* init when class loaded
	*/
	public function init(){
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
		/***** Init Hook *****/
		add_action('plugins_loaded', array( $this,'sma_load_textdomain'));	
		
		// Method enqueue_script js/css load
		add_action( 'admin_enqueue_scripts', array( $this, 'sma_admin_include_script' ), 200 );
	}
	
	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @return string plugin path
	 */
	public function get_plugin_path() {
		if ( isset( $this->plugin_path ) ) {
			return $this->plugin_path;
		}
		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		return $this->plugin_path;
	}
	
	/*
	* plugin file directory function
	*/	
	public function plugin_dir_url(){
		return plugin_dir_url( __FILE__ );
	}	
	
	/* 
	* plugin textdomain function 
	*/	
	function sma_load_textdomain() {
		load_plugin_textdomain( 'woocommerce-shop-manager-admin-bar', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	}
	
	/* 
	* include js and css function for admin 
	*/	
	function sma_admin_include_script(){
		
		$page = isset( $_GET["page"] ) ? $_GET["page"] : "";
		if( $page != 'woocommerce_shop_manager_admin_option' && $page != 'sma' ){
			return;
		}
		
		// Add the color picker css file       
		wp_enqueue_style( 'wp-color-picker' );
		
		// Add the WP Media 
		wp_enqueue_media();
		
		// Add select2 css & js file
		wp_enqueue_style('select2-sma', plugins_url('/assets/css/select2.min.css', __FILE__ ));
		wp_enqueue_script('select2-sma', plugins_url('/assets/js/select2.min.js', __FILE__));
		
		// Add tiptip js and css file
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'jquery-tiptip' );
		
		// Add custom css & js file 
		wp_enqueue_style( 'sma_admin_style', untrailingslashit( plugins_url( '/', __FILE__ ) ) .'/assets/css/admin-style.css', array(), $this->version );
		wp_enqueue_script( 'sma_admin_script', plugins_url( '/assets/js/admin-script.js', __FILE__ ), array( 'jquery','wp-color-picker' ), $this->version);

		
		$params = array(
			'page' => $page,
		);
		wp_localize_script( 'sma_admin_script', 'sma_options', $params );
	}
	
	/*
	* get_zorem_pluginlist
	* 
	* return array
	*/
	public function get_zorem_pluginlist(){
		
		if ( !empty( $this->zorem_pluginlist ) ) return $this->zorem_pluginlist;
		
		if ( false === ( $plugin_list = get_transient( 'zorem_pluginlist' ) ) ) {
			
			$response = wp_remote_get( 'https://www.zorem.com/wp-json/pluginlist/v1/' );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$body    = $response['body']; // use the content
				$plugin_list = json_decode( $body );
				set_transient( 'zorem_pluginlist', $plugin_list, 60*60*24 );
			} else {
				$plugin_list = array();
			}
		}
		return $this->zorem_pluginlist = $plugin_list;
	}

}

function woo_shop_manager_admin() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new woo_shop_manager_admin();
	}
	
	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
woo_shop_manager_admin();
