<?php
/**
 * Plugin Name: Swift Control PRO
 * Plugin URI: https://wpswiftcontrol.com/
 * Description: Quickly access all important areas of your website.
 * Version: 1.4.10
 * Author: David Vongries
 * Author URI: https://github.com/MapSteps/
 * Text Domain: swift-control
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Helper constants.
define( 'SWIFT_CONTROL_PRO_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'SWIFT_CONTROL_PRO_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'SWIFT_CONTROL_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SWIFT_CONTROL_PRO_LICENSE_PAGE', 'swift-control&tab=license' );
define( 'SWIFT_CONTROL_PRO_STORE_URL', 'https://wp-pagebuilderframework.com' );
define( 'SWIFT_CONTROL_PRO_PLUGIN_NAME', 'Swift Control PRO' );
define( 'SWIFT_CONTROL_PRO_ITEM_ID', 72373 );
define( 'SWIFT_CONTROL_PRO_PLUGIN_VERSION', '1.4.10' );

// Load plugin updater if it doesn't exist.
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	require __DIR__ . '/assets/edd/EDD_SL_Plugin_Updater.php';
}

/**
 * Plugin updater.
 */
function swift_control_pro_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	$license_key = trim( get_option( 'swift_control_license_key' ) );

	$edd_updater = new EDD_SL_Plugin_Updater(
		SWIFT_CONTROL_PRO_STORE_URL,
		__FILE__,
		array(
			'version' => SWIFT_CONTROL_PRO_PLUGIN_VERSION,
			'license' => $license_key,
			'item_id' => SWIFT_CONTROL_PRO_ITEM_ID,
			'author'  => 'David Vongries',
			'beta'    => false,
		)
	);

}
add_action( 'init', 'swift_control_pro_plugin_updater' );

/**
 * Plugin activation.
 */
function swift_control_pro_activation() {

	if ( ! current_user_can( 'activate_plugins' ) || 'true' == get_option( 'swift_control_plugin_activated' ) ) {
		return;
	}

	add_option( 'swift_control_install_date', current_time( 'mysql' ) );
	add_option( 'swift_control_site_url', $_SERVER['SERVER_NAME'] );
	add_option( 'swift_control_plugin_activated', 'true' );

}
add_action( 'init', 'swift_control_pro_activation' );

/**
 * License key mismatch.
 *
 * @return boolean
 */
function swift_control_pro_license_key_mismatch() {

	$status           = get_option( 'swift_control_license_status' );
	$current_site_url = get_option( 'swift_control_site_url' );

	// Stop if $current_site_url is not set.
	if ( ! $current_site_url ) {
		return false;
	}

	// Stop if there's no valid license key.
	if ( $status !== 'valid' ) {
		return false;
	}

	// Stop if domain hasn't changed.
	if ( $current_site_url === $_SERVER['SERVER_NAME'] ) {
		return false;
	}

	return true;

}

// Required files.
require_once SWIFT_CONTROL_PRO_PLUGIN_DIR . '/assets/edd/license.php';

// Autoload.
require __DIR__ . '/autoload.php';
