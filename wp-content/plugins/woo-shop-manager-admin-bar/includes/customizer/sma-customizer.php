<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_SMA_Customizer {

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

    }
	
	/**
	 * Register the Customizer panels
	 */
	public function sma_add_customizer_panels( $wp_customize ) {
		
		/**
		* Add our Header & Navigation Panel
		*/
		$wp_customize->add_panel( 'sma_naviation_panel',
			array(
				'title' => __( 'Login Page Customizer', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( '', 'advanced-local-pickup-for-woocommerce' )
			)
		);
		
	}
	
	/**
	 * Register the Customizer sections
	 */
	public function sma_add_customizer_sections( $wp_customize ) {	
		
		$wp_customize->add_section( 'login_page_customize',
			array(
				'title' => __( 'Login Page Custmizer Settings', 'advanced-local-pickup-for-woocommerce' ),
				'description' => esc_html__( '', 'advanced-local-pickup-for-woocommerce'  ),
				'panel' => 'sma_naviation_panel'
			)
		);
				
	}
	
	/**
	 * add css and js for customizer
	*/
	public function enqueue_customizer_scripts(){
		if(isset( $_REQUEST['sma-customizer'] ) && '1' === $_REQUEST['sma-customizer']){
			wp_enqueue_style( 'wp-color-picker' );	
			wp_enqueue_style('sma-customizer-styles', woo_shop_manager_admin()->plugin_dir_url() . 'assets/css/customizer-styles.css', array(), woo_shop_manager_admin()->version  );
			wp_enqueue_script('sma-customizer-scripts', woo_shop_manager_admin()->plugin_dir_url() . 'assets/js/customizer-scripts.js', array('jquery', 'customize-controls'), woo_shop_manager_admin()->version, true);
	
			// Send variables to Javascript
			wp_localize_script('sma-customizer-scripts', 'sma_customizer', array(
				'ajax_url'              => admin_url('admin-ajax.php'),
				'login_page_preview_url'        => $this->get_login_page_preview_url(),
				'trigger_click'        => '#accordion-section-'.$_REQUEST['email'].' h3',
			));		
		}
	}
	
	/**
	 * Get Customizer URL
	 *
	 */
	public static function get_login_page_preview_url() {		
			$preview_url = add_query_arg( array(
				'sma-login-page-customizer-preview' => '1',
			), home_url( '' ) );		

		return $preview_url;
	}
	
	/**
     * Remove unrelated components
     *
     * @access public
     * @param array $components
     * @param object $wp_customize
     * @return array
     */
    public function remove_unrelated_components($components, $wp_customize)	{
        // Iterate over components
        foreach ($components as $component_key => $component) {

            // Check if current component is own component
            if ( ! $this->is_own_component( $component ) ) {
                unset($components[$component_key]);
            }
        }

        // Return remaining components
        return $components;
    }

    /**
     * Remove unrelated sections
     *
     * @access public
     * @param bool $active
     * @param object $section
     * @return bool
     */
    public function remove_unrelated_sections( $active, $section ) {
        // Check if current section is own section
        if ( ! $this->is_own_section( $section->id ) ) {
            return false;
        }

        // We can override $active completely since this runs only on own Customizer requests
        return true;
    }

	/**
	* Remove unrelated controls
	*
	* @access public
	* @param bool $active
	* @param object $control
	* @return bool
	*/
	public function remove_unrelated_controls( $active, $control ) {
		
		// Check if current control belongs to own section
		if ( ! sma_add_customizer_sections::is_own_section( $control->section ) ) {
			return false;
		}

		// We can override $active completely since this runs only on own Customizer requests
		return $active;
	}

	/**
	* Check if current component is own component
	*
	* @access public
	* @param string $component
	* @return bool
	*/
	public static function is_own_component( $component ) {
		return false;
	}

	/**
	* Check if current section is own section
	*
	* @access public
	* @param string $key
	* @return bool
	*/
	public static function is_own_section( $key ) {
				
		if ($key === 'sma_naviation_panel' || $key === 'login_page_customize' ) {
			return true;
		}

		// Section not found
		return false;
	}
	
	/*
	 * Unhook flatsome front end.
	 */
	public function unhook_flatsome() {
		// Unhook flatsome issue.
		wp_dequeue_style( 'flatsome-customizer-preview' );
		wp_dequeue_script( 'flatsome-customizer-frontend-js' );
	}
	
	/*
	 * Unhook Divi front end.
	 */
	public function unhook_divi() {
		// Divi Theme issue.
		remove_action( 'wp_footer', 'et_builder_get_modules_js_data' );
		remove_action( 'et_customizer_footer_preview', 'et_load_social_icons' );
	}
		
}
/**
 * Returns an instance of zorem_woocommerce_advanced_shipment_tracking.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return zorem_woocommerce_advanced_shipment_tracking
*/
function sma_customizer() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new wc_sma_customizer();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
$GLOBALS['WC_SMA_Customizer'] = sma_customizer();