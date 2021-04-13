<?php
/**
 * CSS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_features() ) {

		// Heatbox.
		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Toggle switch.
		wp_enqueue_style( 'udb-toggle-switch', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/toggle-switch.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		// Features.
		wp_enqueue_style( 'udb-features', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/css/features.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

};
