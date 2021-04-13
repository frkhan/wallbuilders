<?php
/**
 * Script to run on Swift Control's un-installation.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Check if we need to apply multisite support.
 *
 * @return bool
 */
function swift_control_pro_needs_to_switch_blog() {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	return is_multisite() && ! is_main_site() && is_plugin_active_for_network( 'swift-control-pro/swift-control-pro.php' ) ? true : false;
}

$misc_settings = get_option( 'swift_control_misc_settings', array() );
$switch_blog   = swift_control_pro_needs_to_switch_blog();

if ( $switch_blog ) {
	$misc_settings = get_blog_option( get_main_site_id(), 'swift_control_misc_settings', array() );
}

$delete_on_uninstall = isset( $misc_settings['delete_on_uninstall'] ) ? absint( $misc_settings['delete_on_uninstall'] ) : 0;

if ( ! $delete_on_uninstall ) {
	return;
}

delete_option( 'swift_control_active_widgets' );
delete_option( 'swift_control_widget_settings' );
delete_option( 'swift_control_widget_settings' );
delete_option( 'swift_control_color_settings' );
delete_option( 'swift_control_misc_settings' );
delete_option( 'swift_control_install_date' );
delete_option( 'swift_control_plugin_activated' );
delete_option( 'swift_control_site_url' );
