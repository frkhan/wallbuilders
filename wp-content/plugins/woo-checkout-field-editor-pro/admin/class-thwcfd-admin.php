<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themehigh.com
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/admin
 */

if(!defined('WPINC')){	die; }

if(!class_exists('THWCFD_Admin')):
 
class THWCFD_Admin {
	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.9.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//add_action('admin_notices', array($this, 'output_premium_version_notice'));
	}
	
	public function enqueue_styles_and_scripts($hook) {
		if(strpos($hook, 'page_checkout_form_designer') !== false) {
			$debug_mode = apply_filters('thwcfd_debug_mode', false);
			$suffix = $debug_mode ? '' : '.min';
			
			$this->enqueue_styles($suffix);
			$this->enqueue_scripts($suffix);
		}
	}
	
	private function enqueue_styles($suffix) {
		wp_enqueue_style('woocommerce_admin_styles');
		wp_enqueue_style('thwcfd-admin-style', THWCFD_ASSETS_URL_ADMIN . 'css/thwcfd-admin'. $suffix .'.css', $this->version);
	}

	private function enqueue_scripts($suffix) {
		$deps = array('jquery', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-tiptip', 'woocommerce_admin', 'selectWoo', 'wp-color-picker');
			
		wp_enqueue_script('thwcfd-admin-script', THWCFD_ASSETS_URL_ADMIN . 'js/thwcfd-admin'. $suffix .'.js', $deps, $this->version, false);
	}

	public function wcfd_capability() {
		$allowed = array('manage_woocommerce', 'manage_options');
		$capability = apply_filters('thwcfd_required_capability', 'manage_woocommerce');

		if(!in_array($capability, $allowed)){
			$capability = 'manage_woocommerce';
		}
		return $capability;
	}
	
	public function admin_menu() {
		$capability = $this->wcfd_capability();
		$this->screen_id = add_submenu_page('woocommerce', __('WooCommerce Checkout Field Editor', 'woo-checkout-field-editor-pro'), __('Checkout Form', 'woo-checkout-field-editor-pro'), $capability, 'checkout_form_designer', array($this, 'output_settings'));
	}
	
	public function add_screen_id($ids){
		$ids[] = 'woocommerce_page_checkout_form_designer';
		$ids[] = strtolower(__('WooCommerce', 'woo-checkout-field-editor-pro')) .'_page_checkout_form_designer';

		return $ids;
	}

	public function plugin_action_links($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=checkout_form_designer').'">'. __('Settings', 'woo-checkout-field-editor-pro') .'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	
	public function output_premium_version_notice(){
		$is_dismissed = get_transient('thwcfd_upgrade_notice_dismissed');
		if($is_dismissed){
			return;
		}
		?>
        <div id="message" class="notice notice-success is-dismissible thpladmin-notice" data-nonce="<?php echo wp_create_nonce( 'thwcfd_upgrade_notice'); ?>">
            <div class="squeezer">
            	<table>
                	<tr>
                    	<td width="70%">
                        	<p><strong><i>WooCommerce Checkout Field Editor Pro</i></strong> premium version provides more features to design your checkout page.</p>
                            <ul>
                            	<li>17 field types available,  (<i>Text, Hidden, Password, Telephone, Email, Number, Textarea, Radio, Checkbox, Checkbox Group, Select, Multi-select, Date Picker, Time Picker, File Upload, Heading, Label</i>).</li>
                                <li>Conditionally display fields based on cart items and other field(s) values.</li>
                                <li>Add an extra cost to the cart total based on field selection.</li>
                                <li>Custom validation rules using RegEx.</li>
                                <li>Option to add more sections in addition to the core sections (billing, shipping and additional) in checkout page.</li>
                            </ul>
                        </td>
                        <td>
                        	<a target="_blank" href="https://www.themehigh.com/product/woocommerce-checkout-field-editor-pro/">
                            	<img src="<?php echo THWCFD_ASSETS_URL_ADMIN ?>css/upgrade-btn.png" />
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
	}

	private function output_review_request_link(){
		?>
		<p>If you like our <strong>Checkout Field Editor</strong> plugin please leave us a <a href="https://wordpress.org/support/plugin/woo-checkout-field-editor-pro/reviews?rate=5#new-post" target="_blank" aria-label="five star" data-rated="Thanks :)">★★★★★</a> rating. A huge thanks in advance!</p>
		<?php

		//If you find this plugin useful please show your support and rate it ★★★★★ on WordPress.org - much appreciated! :)
	}

	public function get_current_tab(){
		return isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'fields';
	}
	
	public function output_settings(){
		$this->output_premium_version_notice();
		$this->output_review_request_link();

		$tab = $this->get_current_tab();

		echo '<div class="thwcfd-wrap">';
		if($tab === 'advanced_settings'){			
			$advanced_settings = THWCFD_Admin_Settings_Advanced::instance();	
			$advanced_settings->render_page();		
		}else{
			$general_settings = THWCFD_Admin_Settings_General::instance();	
			$general_settings->init();
		}
		echo '</div">';
	}

	public function dismiss_thwcfd_upgrade_notice(){
		if(! check_ajax_referer( 'thwcfd_upgrade_notice', 'security' )){
			die();
		}
		set_transient('thwcfd_upgrade_notice_dismissed', true, 4 * WEEK_IN_SECONDS);
	}
}

endif;