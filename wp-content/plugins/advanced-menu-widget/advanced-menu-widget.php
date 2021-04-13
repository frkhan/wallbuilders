<?php
/*
 * Plugin Name: Advanced Menu Widget
 * Plugin URI: http://webikon.sk/
 * Description: Enhanced Navigation Menu Widget
 * Version: 0.4.1
 * Author: Ján Bočínec
 * Author URI: http://bocinec.sk/
 * License: GPL2+
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'class-advanced-menu-walker.php';
require plugin_dir_path( __FILE__ ) . 'class-advanced-menu-widget.php';

function add_rem_menu_widget() {
	//unregister_widget( 'WP_Nav_Menu_Widget' );
	register_widget('Advanced_Menu_Widget');
}
add_action('widgets_init', 'add_rem_menu_widget');

// shortcode => [advMenu id=N]
function amw_shortcode( $atts ) {
	$instance =	shortcode_atts(array(
					'nav_menu' 				=> '',
					'title' 				=> '',
					'dropdown' 				=> '',
					'only_related' 			=> '',
					'depth' 				=> '',
					'container' 			=> '',
					'container_id' 			=> '',
					'menu_class'			=> '',
					'before' 				=> '',
					'after' 				=> '',
					'link_before' 			=> '',
					'link_after' 			=> '',
					'filter' 				=> '',
					'filter_selection' 		=> '',
					'include_parent' 		=> '',
					'start_depth' 			=> '',
					'hide_title' 			=> '',
					'custom_widget_class' 	=> ''
				), $atts);

	ob_start();
	the_widget('Advanced_Menu_Widget', $instance, '' );
	$output = ob_get_contents();
  	ob_end_clean();

  	return $output;
}
add_shortcode( 'advMenu', 'amw_shortcode' );
