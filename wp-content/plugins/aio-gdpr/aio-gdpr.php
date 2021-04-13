<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://GDPRPlug.in
 * @since             1.0.0
 * @package           All-in-One GDPR
 *
 * @wordpress-plugin
 * Plugin Name:       All-in-One GDPR
 * Plugin URI:        https://GDPRPlug.in
 * Description:       All-in-One GDPR provides tools for compliance with EU <a target="_blank" href="https://ico.org.uk/for-organisations/data-protection-reform/overview-of-the-gdpr/">GDPR</a> regulations.
 * Version:           5.3
 * Author:            Anthony Budd, Ideea
 * Author URI:        https://GDPRPlug.in
 * Text Domain:       all-in-one-gdpr
 */

define('AIOGDPR_VERSION', 	'5.3');
define('AIOGDPR_NAME',     'aio-gdpr');


// If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}


// License check and auto update
if($license_key = get_option('AIO_GDPR_license_key')){
	require plugin_dir_path(__FILE__) .'core/lib/plugin-update-checker/plugin-update-checker.php';
	$updateChecker = Puc_v4p3_Factory::buildUpdateChecker('https://gdprplug.in/wp-update-server-master/?action=get_metadata&slug=all-in-one-gdpr&license_key='.$license_key, __FILE__, 'all-in-one-gdpr');
}


// Boot
function AIOGDPR_boot(){
	require 'core/class-aio-gdpr.php';
	$app = AIOGDPR::instance();
}

add_action('init', 'AIOGDPR_boot');

