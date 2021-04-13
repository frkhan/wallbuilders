<?php

/*-----------------------------------------------------------------------------------*/
/*	Constants
/*-----------------------------------------------------------------------------------*/

define('MPC_TINYMCE_PATH', MPC_PLUGIN_ROOT.'/tinymce');
define('MPC_TINYMCE_URI', MPC_PLUGIN_ROOT.'/tinymce');

/*-----------------------------------------------------------------------------------*/
/*	Hooks
/*-----------------------------------------------------------------------------------*/

add_action('admin_init', 'tinymce_enqueue_fb');					// enqueue scripts
add_action('init', 'tinymce_register_buttons_fb');				// register buttons

/*-----------------------------------------------------------------------------------*/
/*	Functions
/*-----------------------------------------------------------------------------------*/

/* Enqueue Scripts */
function tinymce_enqueue_fb() {
		/* MPC custom JS/CSS */
		wp_enqueue_style('mpc-win', MPC_TINYMCE_URI . '/css/mpc-win.css');

		/* Libraries */
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-livequery', MPC_TINYMCE_URI . '/js/jquery.livequery.js', false);
		wp_enqueue_script('jquery-appendo', MPC_TINYMCE_URI . '/js/jquery.appendo.js', false);
		wp_enqueue_script('base64', MPC_TINYMCE_URI . '/js/base64.js', false);
		wp_enqueue_script('mpc-win', MPC_TINYMCE_URI . '/js/mpc-win.js', array( 'jquery', 'wp-color-picker' ), false);
}

/* Register TinyMCE Button*/
function tinymce_register_buttons_fb(){
	if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
		if (get_user_option('rich_editing') == 'true'){
			add_filter('mce_external_plugins', 'mpc_add_plugin_fb');
			add_filter('mce_buttons', 'mpc_add_button_fb');
		}
	}
}

/* Main.js adds the button and other stuff to the post editor */
function mpc_add_plugin_fb($array){
	$array['mpcWizard'] = MPC_TINYMCE_URI . '/main.js';
	return $array;
}

/* Adds button */
function mpc_add_button_fb( $buttons ){
	array_push( $buttons, '|', 'rfbwp_menu' );
	return $buttons;
}

?>