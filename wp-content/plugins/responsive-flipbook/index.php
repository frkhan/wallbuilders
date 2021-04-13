<?php

/**

Plugin Name: Responsive FlipBook WordPress Plugin
Plugin URI: http://codecanyon.net/user/mpc
Description: This is a jQuery Flip Book plugin, no Flash Player required. Gives each user the same experience (mobile & desktop)..
Version: 2.1.3
Author: MassivePixelCreation
Author URI: http://codecanyon.net/user/mpc
Text Domain: rfbwp
Domain Path: /languages/
**/

/*-----------------------------------------------------------------------------------*/
/*	Globals
/*-----------------------------------------------------------------------------------*/

global $rfbwp_shortname;
global $mpcrf_options;

$rfbwp_shortname = 'rfbwp';

/*-----------------------------------------------------------------------------------*/
/*	Constants
/*-----------------------------------------------------------------------------------*/

define( 'MPC_PLUGIN_ROOT', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'MPC_PLUGIN_FILE', __FILE__ );

/*-----------------------------------------------------------------------------------*/
/*	TextDomain
/*-----------------------------------------------------------------------------------*/
add_action( 'plugins_loaded',	'rfb_localization' );
function rfb_localization() {
	load_plugin_textdomain( 'rfbwp', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

/*-----------------------------------------------------------------------------------*/
/*	Add CSS & JS
/*-----------------------------------------------------------------------------------*/
function rfb_enqueue_scripts() { // TODO: check if shortcode is used before adding all styles/scripts to every page...
	// CSS
	wp_enqueue_style('rfbwp-fontawesome', MPC_PLUGIN_ROOT.'/assets/fonts/font-awesome.css');
	wp_enqueue_style('rfbwp-et_icons', MPC_PLUGIN_ROOT.'/assets/fonts/et-icons.css');
	wp_enqueue_style('rfbwp-et_line', MPC_PLUGIN_ROOT.'/assets/fonts/et-line.css');
	wp_enqueue_style('rfbwp-styles', MPC_PLUGIN_ROOT.'/assets/css/style.min.css');

	// JS
	wp_enqueue_script('ion-sound', MPC_PLUGIN_ROOT.'/assets/js/ion.sound.min.js', array('jquery'));
	wp_enqueue_script('jquery-doubletab', MPC_PLUGIN_ROOT.'/assets/js/jquery.doubletap.js', array('jquery'));
	
	wp_localize_script( 'ion-sound', 'mpcthLocalize', array(
		'soundsPath' => MPC_PLUGIN_ROOT . '/assets/sounds/',	
		'downloadPath' => MPC_PLUGIN_ROOT . '/includes/download.php?file='
	) );
}
add_action('wp_enqueue_scripts', 'rfb_enqueue_scripts');

add_action('wp_head', create_function('', 'echo \'<!--[if lt IE 9]><script>var rfbwp_ie_8 = true;</script><![endif]-->\';'));

function rfbwp_get_google_fonts( $options ) {
	$protocol = is_ssl() ? 'https' : 'http';
	/* Google Fonts */
	$enable_heading_font = isset( $options['rfbwp_fb_heading_font'] ) && $options['rfbwp_fb_heading_font'] == '1' ? true : false;
	$enable_content_font = isset( $options['rfbwp_fb_content_font'] ) && $options['rfbwp_fb_content_font'] == '1' ? true : false;
	$enable_num_font = isset( $options['rfbwp_fb_num_font'] ) && $options['rfbwp_fb_num_font'] == '1' ? true : false;
	$enable_toc_font = isset( $options['rfbwp_fb_toc_font'] ) && $options['rfbwp_fb_toc_font'] == '1' ? true : false;

	if ( $enable_heading_font ) {
		if ( !empty( $options['rfbwp_fb_heading_family'] ) && $options['rfbwp_fb_heading_family'] !== 'default' ) {
			$heading_family = str_replace(' ', '+', $options['rfbwp_fb_heading_family']);
			echo '<link rel="stylesheet" type="text/css" href="' . apply_filters( 'rfbwp/font', "$protocol://fonts.googleapis.com/css?family={$heading_family}" ) . '" media="screen">';
		}
	}

	if ( $enable_content_font ) {
		if ( !empty( $options['rfbwp_fb_content_family'] ) && $options['rfbwp_fb_content_family'] !== 'default' ) {
			$content_family = str_replace(' ', '+', $options['rfbwp_fb_content_family']);
			echo '<link rel="stylesheet" type="text/css" href="' . apply_filters( 'rfbwp/font', "$protocol://fonts.googleapis.com/css?family={$content_family}" ) . '" media="screen">';
		}
	}

	if ( $enable_num_font ) {
		if ( !empty( $options['rfbwp_fb_num_family'] ) && $options['rfbwp_fb_num_family'] !== 'default' ) {
			$num_family = str_replace(' ', '+', $options['rfbwp_fb_num_family']);
			echo '<link rel="stylesheet" type="text/css" href="' . apply_filters( 'rfbwp/font', "$protocol://fonts.googleapis.com/css?family={$num_family}" ) . '" media="screen">';
		}
	}

	if ( $enable_toc_font ) {
		if ( !empty( $options['rfbwp_fb_toc_family'] ) && $options['rfbwp_fb_toc_family'] !== 'default' ) {
			$toc_family = str_replace(' ', '+', $options['rfbwp_fb_toc_family']);
			echo '<link rel="stylesheet" type="text/css" href="' . apply_filters( 'rfbwp/font', "$protocol://fonts.googleapis.com/css?family={$toc_family}" ) . '" media="screen">';
		}
	}
}

/*--------------------------- END CSS & JS -------------------------------- */

/* ---------------------------------------------------------------- */
/* Cache Google Webfonts
/* ---------------------------------------------------------------- */
if( !function_exists( 'rfbwp_cache_google_webfonts' )) {
    add_action('wp_ajax_rfbwp_cache_google_webfonts', 'rfbwp_cache_google_webfonts');
    function rfbwp_cache_google_webfonts() {
		$google_webfonts = isset($_POST['google_webfonts']) ? $_POST['google_webfonts'] : '';

		if(!empty($google_webfonts)) {
			set_transient('mpcth_google_webfonts', $google_webfonts, DAY_IN_SECONDS);
		}

		die();
    }
}

/*-----------------------------------------------------------------------------------*/
/*	Hook MPC Shortcode button & Shortcodes Source
/*-----------------------------------------------------------------------------------*/
function rfb_plugin_setup() {
	require_once ('tinymce/tinymce-settings.php');
	require_once ('includes/theme-shortcodes.php');
	require_once ('includes/vc-shortcodes.php');
}
add_action('after_setup_theme', 'rfb_plugin_setup');


/*-----------------------------------------------------------------------------------*/
/*	Hook Massive Panel & Get Options
/*-----------------------------------------------------------------------------------*/
if( is_admin() )
	require_once( 'massive-panel/theme-settings.php' );

function mp_get_global_options() {
	global $rfbwp_shortname;
	$mp_option = get_option($rfbwp_shortname.'_options');

	$mp_option = rfbwp_set_base_options( $mp_option );

	return $mp_option;
}

$mpcrf_options = mp_get_global_options();

/*--------------------------- END Massive Panel Hook -------------------------------- */

/*----------------------------------------------------------------------------*\
	Get Flipbook via AJAX request
\*----------------------------------------------------------------------------*/
add_action( 'wp_ajax_rfbwp_get_flipbook', 'rfbwp_get_flipbook' );
add_action( 'wp_ajax_nopriv_rfbwp_get_flipbook', 'rfbwp_get_flipbook' );
function rfbwp_get_flipbook() {
	global $rfbwp_force_open_overwrite;
	require_once('php/settings.php');

	if ( isset( $_POST[ 'rfbwp_id' ] ) ) {
		$rfbwp_force_open_overwrite = true;
		echo do_shortcode( '[responsive-flipbook id="' . $_POST[ 'rfbwp_id' ] . '"]' );
	}

	die();
}

/*-----------------------------------------------------------------------------------*/
/*	Add Flip Book to the stage
/*-----------------------------------------------------------------------------------*/
if( !is_admin() )
	require_once('php/settings.php');

add_shortcode('responsive-flipbook', 'rfbwp_add_book');
function rfbwp_add_book($att, $content = null) {
	global $mpcrf_options;
	global $flipbook_id;
	global $rfbwp_force_open_overwrite;

	$id = $att['id'];
	$flipbook_id = $id;

	if($id == "")
		return __( 'Oops! You need to specify flip book id inside the shortcode.', 'rfbwp' );
	else
		$book_name = $id;

	$i = 0;

	if ( ! isset( $mpcrf_options[ 'books' ] ) )
		return __( 'ERROR: You should create the Flipbook first.', 'rfbwp' );

	// get the book ID based on the books name
	foreach($mpcrf_options['books'] as $book) {
		if(strtolower(str_replace(" ", "_", $book['rfbwp_fb_name'])) == $id)
			break;
		$i++;
	}

	$id = $i;

	if(!isset($mpcrf_options['books'][$id]['pages']) || $mpcrf_options['books'][$id]['pages'] == '')
		return __( 'ERROR: There is no book with ID', 'rfbwp' ) . ' <strong>'.$book_name.'</strong>';

	if ( $rfbwp_force_open_overwrite ) {
		$rfbwp_force_open_overwrite = false;
		$mpcrf_options['books'][$id]['rfbwp_fb_force_open'] = '0';
	}

	rfbwp_get_google_fonts( $mpcrf_options['books'][$id] );

	wp_enqueue_script('turn-js', MPC_PLUGIN_ROOT.'/assets/js/turn.min.js', array('jquery'));
	wp_enqueue_script('flipbook-js', MPC_PLUGIN_ROOT.'/assets/js/flipbook.min.js', array('jquery'));
	
	do_action( 'rfbwp/flipbook/scripts' );
	
	/* Fullscreen */
	$fullscreen	= isset( $mpcrf_options['books'][$id]['rfbwp_fb_fs_color'] ) ? true : false;
	if( $fullscreen ) {
		$fs_color	= !empty( $mpcrf_options['books'][$id]['rfbwp_fb_fs_color'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_fs_color'] : 'transparent';
		$fs_opacity = $mpcrf_options['books'][$id]['rfbwp_fb_fs_opacity'];

		$fullscreen_icon = (isset( $mpcrf_options['books'][$id]['rfbwp_fb_fs_icon_color'] ) && $mpcrf_options['books'][$id]['rfbwp_fb_fs_icon_color'] == "1" ) ? 'true' : 'false';
		$fullscreen = $fs_color . '|' . $fs_opacity . '|' . $fullscreen_icon;
	}

	/* ToC */
	$toc = isset( $mpcrf_options['books'][$id]['rfbwp_fb_toc_display_style'] ) ? true : false;
	$toc = ( $toc && $mpcrf_options['books'][$id]['rfbwp_fb_toc_display_style'] == '1' ? 'toc-new' : 'toc-old' );

	/* SlideShow delay */
	$slide_show = isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_ss'] ) ? true : false;
	if( $slide_show ) {
		$slide_show = isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_ss_delay'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_nav_ss_delay'] : 2000;
		$slide_show = $slide_show < 2000 ? 2000 : $slide_show;
	}

	$add_hard_cover = false;
	if ( isset( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc' ] ) && $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc' ] == '1' ) {
		$add_hard_cover = true;

		if ( ! empty( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_fco' ] ) )
			$cover_front_outside = $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_fco' ];
		else
			$add_hard_cover = false;
		if ( ! empty( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_fci' ] ) )
			$cover_front_inside = $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_fci' ];
		else
			$add_hard_cover = false;
		if ( ! empty( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_bco' ] ) )
			$cover_back_outside = $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_bco' ];
		else
			$add_hard_cover = false;
		if ( ! empty( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_bci' ] ) )
			$cover_back_inside = $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_bci' ];
		else
			$add_hard_cover = false;
	}

	if( function_exists( 'rfbwp_setup_css') )
		rfbwp_setup_css($id, $mpcrf_options);

	$menuType = ( $mpcrf_options['books'][$id]['rfbwp_fb_nav_menu_type'] == '1' || strtolower( $mpcrf_options['books'][$id]['rfbwp_fb_nav_menu_type'] ) == 'compact' ) ? 'compact' : 'spread';
	$menuPosition = strtolower($mpcrf_options['books'][$id]['rfbwp_fb_nav_menu_position']);
	$textMenu = isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_text'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_nav_text'] : false;
	$stackedButtons = ( $mpcrf_options['books'][$id]['rfbwp_fb_nav_stack'] ) ? 'buttonsStacked' : '';

	$nav_text = apply_filters( 'rfbwp/navTextMode', array(
		'toc'			=> __('toc', 'rfbwp'),
		'zoom'			=> __('zoom', 'rfbwp'),
		'zoom_out'		=> __('exit', 'rfbwp'),
		'slide'			=> __('play', 'rfbwp'),
		'slide_stop'	=> __('stop', 'rfbwp'),
		'all'			=> __('all', 'rfbwp'),
		'all_close'		=> __('exit', 'rfbwp'),
		'full'			=> __('full', 'rfbwp'),
		'full_close'	=> __('exit', 'rfbwp'),
		'download'		=> __('save', 'rfbwp')
	) );

	$arrows = ($mpcrf_options['books'][$id]['rfbwp_fb_nav_arrows'] == 1) ? true : false;
	$arrowsData = '';
	if( $arrows ) {
		$arrowsData .= ($mpcrf_options['books'][$id]['rfbwp_fb_nav_arrows_toolbar'] == 1) ? ' data-grouped="true"' : '';
		$arrowsData .= (!empty( $mpcrf_options['books'][$id]['rfbwp_fb_nav_prev_icon'] )) ? ' data-prev-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_prev_icon'] . '"' : '';
		$arrowsData .= (!empty( $mpcrf_options['books'][$id]['rfbwp_fb_nav_next_icon'] )) ? ' data-next-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_next_icon'] . '"' : '';

		$arrowsData .= (!empty( $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_icon_prev'] )) ? ' data-up-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_icon_prev'] . '"' : '';
		$arrowsData .= (!empty( $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_icon_next'] )) ? ' data-down-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_icon_next'] . '"' : '';
	}

	$nav_output = '';
	$nav_output .= '<div id="fb-nav-'.$id.'" class="fb-nav mobile ' . $menuType . ' ' . $menuPosition . ' ' . $stackedButtons . '" data-menu-type="' . $menuType . '" ' . $arrowsData . '>';
	$nav_output .= '<ul class="alternative-nav">';
		$nav_output .= '<li id="fb-zoom-out-'.$id.'" class="fb-zoom-out" ' . ($textMenu ? 'data-text="' . $nav_text[ 'zoom_out' ] . '"' : 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_zoom_out_icon'] . '"' ) . '></li>';
		$nav_output .= '<li class="big-next show-all-close" ' . ($textMenu ? 'data-text="' . $nav_text[ 'all_close' ] . '"' : 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_icon_close'] . '"' ) . '></li>';
	$nav_output .= '</ul>';
	$nav_output .= '<ul class="main-nav">';

	$numberOfButtons = 0;

	if($mpcrf_options['books'][$id]['rfbwp_fb_nav_toc'] == '1')
		$numberOfButtons++;

	if($mpcrf_options['books'][$id]['rfbwp_fb_nav_zoom'] == '1')
		$numberOfButtons++;

	if($mpcrf_options['books'][$id]['rfbwp_fb_nav_ss'] == '1')
		$numberOfButtons++;

	if($mpcrf_options['books'][$id]['rfbwp_fb_nav_sap'] == '1')
		$numberOfButtons++;

	if($mpcrf_options['books'][$id]['rfbwp_fb_nav_fs'] == '1')
		$numberOfButtons++;

	if( isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl'] ) &&  $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl'] == '1')
		$numberOfButtons++;
	
	$numberOfButtons = apply_filters( 'rfbwp/navButtonsCount', $numberOfButtons, $mpcrf_options['books'][$id] );

	for($i = 1; $i < $numberOfButtons+1; $i++) {

		$class = '';

		if($menuType == 'spread')
			$class = 'round';

		if($mpcrf_options['books'][$id]['rfbwp_fb_nav_toc'] == '1' && $mpcrf_options['books'][$id]['rfbwp_fb_nav_toc_order'] == $i) {			
			$toc_index = isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_toc_index'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_nav_toc_index'] : '3'; 
			
			$icons = 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_toc_icon'] . '"';
			$icons = $textMenu ? 'data-text="' . $nav_text[ 'toc' ] . '"' : $icons ;
	
			$icons .= ' data-toc-index="' . $toc_index . '"';
			
			$nav_output .= '<li class="toc '.$class.'" ' .  $icons . '></li>';
		}

		if($mpcrf_options['books'][$id]['rfbwp_fb_nav_zoom'] == '1' && $mpcrf_options['books'][$id]['rfbwp_fb_nav_zoom_order'] == $i) {
			$icons = '';
			$icons .= 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_zoom_icon'] . '" ';
			$icons .= 'data-icon-active="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_zoom_out_icon'] . '"';

			$icons = $textMenu ? 'data-text="' . $nav_text[ 'zoom' ] . '" data-text-active="' . $nav_text[ 'zoom_out' ] . '"' : $icons ;

			$nav_output .= '<li class="zoom '.$class.'" ' .  $icons . '></li>';
		}

		if($mpcrf_options['books'][$id]['rfbwp_fb_nav_ss'] == '1' && $mpcrf_options['books'][$id]['rfbwp_fb_nav_ss_order'] == $i){
			$icons = '';
			$icons .= 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_ss_icon'] . '" ';
			$icons .= 'data-icon-active="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_ss_stop_icon'] . '"';

			$icons = $textMenu ? 'data-text="' . $nav_text[ 'slide' ] . '" data-text-active="' . $nav_text[ 'slide_stop' ] . '"' : $icons ;

			$nav_output .= '<li class="slideshow '.$class.'" ' .  $icons . '></li>';
		}

		if($mpcrf_options['books'][$id]['rfbwp_fb_nav_sap'] == '1' && $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_order'] == $i) {
			$icons = 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_sap_icon'] . '" ';
			$icons = $textMenu ? 'data-text="' . $nav_text[ 'all' ] . '"' : $icons ;
			if ( $mpcrf_options['books'][$id]['rfbwp_fb_sa_thumb_cols'] == '' ) $mpcrf_options['books'][$id]['rfbwp_fb_sa_thumb_cols'] = 3;

			$nav_output .= '<li class="show-all '.$class.'" ' .  $icons . ' data-cols="' . $mpcrf_options['books'][$id]['rfbwp_fb_sa_thumb_cols'] . '"></li>';
		}

		if($mpcrf_options['books'][$id]['rfbwp_fb_nav_fs'] == '1' && $mpcrf_options['books'][$id]['rfbwp_fb_nav_fs_order'] == $i) {
			$icons = '';
			$icons .= 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_fs_icon'] . '" ';
			$icons .= 'data-icon-active="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_fs_close_icon'] . '"';

			$icons = $textMenu ? 'data-text="' . $nav_text[ 'full' ] . '" data-text-active="' . $nav_text[ 'full_close' ] . '"' : $icons ;

			$nav_output .= '<li class="fullscreen '.$class.'" ' .  $icons . '></li>';
		}
		
		if( isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl'] ) && $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl'] == '1' && $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl_order'] == $i) {
			$file = isset( $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl_file'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl_file'] : false;
			$file = !empty( $file ) ? urlencode( $file ) : false;
			
			$icons = '';
			$icons .= 'data-icon="' . $mpcrf_options['books'][$id]['rfbwp_fb_nav_dl_icon'] . '" ';
	
			$icons = $textMenu ? 'data-text="' . $nav_text[ 'download' ] . '"' : $icons ;

			$nav_output .= '<li' . ( $file ? ' data-file="' . $file . '"' : '' ) . '" class="download '.$class.'" ' .  $icons . '></li>';
		}
		
		$nav_output = apply_filters( 'rfbwp/navRender', $nav_output, $mpcrf_options['books'][$id], $i, $textMenu );
	}

	$nav_output .= '</ul>';
	$nav_output.= '</div>'; /* end navigation */

	$force_open = isset( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_force_open' ] ) && $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_force_open' ] == '1';
	$nav_with_covers = $add_hard_cover ? 'nav-with-cover' : '';

	/* Enable Sound */
	$turn_sound = isset( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_enable_sound' ] ) && $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_enable_sound' ] == '1';
	
	$output = '';
	$output .= '<div id="flipbook-container-'.$id.'"  data-fullscreen="' . $fullscreen . '" data-slide-delay="' . $slide_show . '" data-display="front" ' . ( $force_open ? 'data-force-open="1"' : '' ) . ' ' . ( $turn_sound ? 'data-turn-sound="1"' : '' ) . ' class="flipbook-container flipbook-container-' . $id . ' ' . $nav_with_covers . ' ' . $toc . '">';

	if( $menuPosition == 'top' ) {
		$output .= $nav_output;
	}

	if ( $add_hard_cover ) {
		$cover_front_color = ! empty( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_fcc' ] ) ? $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_fcc' ] : '#dddddd';
		$output .= '<div class="rfbwp-cover-wrap rfbwp-front"><div class="rfbwp-cover"><img src="' . $cover_front_outside . '"><img src="' . $cover_front_inside . '"><div class="rfbwp-side" style="background:' . $cover_front_color . ';"></div></div></div>';
	}

	$output .= '<div id="flipbook-'.$id.'" data-fb-id="'.$book_name.'" data-fb-w="'.$mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_width' ].'" data-fb-h="'.$mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_height' ].'" class="flipbook rfbwp-init' . (!$arrows ? ' no-arrows' : '') . '">';

	$enable_numeration = isset( $mpcrf_options['books'][$id]['rfbwp_fb_num'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_num'] : false;
	if( $enable_numeration ) {
		$page_num = 1;

		$hide_num = isset( $mpcrf_options['books'][$id]['rfbwp_fb_num_hide'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_num_hide'] : true;
		$num_v_position = isset( $mpcrf_options['books'][$id]['rfbwp_fb_num_v_position'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_num_v_position'] : '';
		$num_h_position = isset( $mpcrf_options['books'][$id]['rfbwp_fb_num_h_position'] ) ? $mpcrf_options['books'][$id]['rfbwp_fb_num_h_position'] : '';
	}

	/* Insert flipbook pages */
	foreach($mpcrf_options['books'][$id]['pages'] as $page) {
		$pageType  = isset($page['rfbwp_fb_page_type']) ? $page['rfbwp_fb_page_type'] : '';
		$pageIndex = isset($page['rfbwp_fb_page_index']) ? $page['rfbwp_fb_page_index'] : '';
		$image = isset($page['rfbwp_fb_page_bg_image']) ? $page['rfbwp_fb_page_bg_image'] : '';
		$imageZoomed = isset($page['rfbwp_fb_page_bg_image_zoom']) ? $page['rfbwp_fb_page_bg_image_zoom'] : '';

		$content = isset( $page['rfbwp_page_html'] ) ? $page['rfbwp_page_html'] : ''; //undefined index error
		$is_second_column = isset( $page['rfbwp_page_html_second'] ) ? true : false;

		$pageType = ($pageType == 'Double Page') ? 'double' : 'single';
		$output .= '<div class="fb-page '.$pageType.'">'; // page wrap;
		$output .= '<div class="fb-page-content">'; // page content wrap;

		if ( ! empty( $page['rfbwp_fb_page_url'] ) )
			$output .= '<a href="' . esc_url( $page['rfbwp_fb_page_url'] ) . '" class="fb-container">';
		else
			$output .= '<div class="fb-container">';

		$custom_class = '';
		if(!empty($page['rfbwp_fb_page_custom_class']))
			$custom_class = ' ' . $page['rfbwp_fb_page_custom_class'];

		if(!empty($page['rfbwp_page_css'])) {
			$output .= '<style>' . PHP_EOL;
			$output .= stripslashes( $page['rfbwp_page_css'] );
			$output .= '</style>' . PHP_EOL;
		}

		if( $is_second_column && $pageType == 'double' ) {
			$content = '<div class="left">' . $content . '</div>';
			$content .= '<div class="right">' . $page['rfbwp_page_html_second'] . '</div>';
		}

		$output .= '<div class="page-html' . $custom_class . '">';
		$output .= do_shortcode(stripslashes(stripslashes( $content )));
		$output .= '</div>';

		$output .= '<img src="'.$image.'" class="bg-img"/>';
		// TODO: Add preloading scripts for all FBs.
//		$output .= '<img src="'.MPC_PLUGIN_ROOT.'/images/preloader.gif" data-src="'.$page['rfbwp_fb_page_bg_image'].'" class="bg-img"/>';

		if($imageZoomed != '')
			$output .= '<img src="'.MPC_PLUGIN_ROOT.'/assets/images/preloader.gif" data-src="'.$imageZoomed.'" class="bg-img zoom-large"/>';

		if( $enable_numeration )
			$output .= '<div class="mpc-numeration-wrap ' . $num_v_position . ' ' . $num_h_position . '" data-page-number="' . $page_num . '" data-hide="' . $hide_num . '"><span>' . $page_num . '</span></div>';

		if ( ! empty( $page['rfbwp_fb_page_url'] ) )
			$output .= '</a>'; // end .fb-container
		else
			$output .= '</div>'; // end .fb-container

		$output .= '</div>'; // end .fb-page
		$output .= '</div>'; // end .fb-page-content

		if( $enable_numeration )
			$page_num = ( $pageType == "double" ) ? $page_num + 2 : $page_num + 1;
	}

	$output .= '</div>'; /* end flipbook */

	if ( $add_hard_cover ) {
		$cover_back_color = ! empty( $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_bcc' ] ) ? $mpcrf_options[ 'books' ][ $id ][ 'rfbwp_fb_hc_bcc' ] : '#dddddd';
		$output .= '<div class="rfbwp-cover-wrap rfbwp-back"><div class="rfbwp-cover"><img src="' . $cover_back_inside . '"><img src="' . $cover_back_outside . '"><div class="rfbwp-side" style="background:' . $cover_back_color . ';"></div></div></div>';
	}

	if( $menuPosition != 'top' ) {
		$output .= $nav_output;
	}

	$output .= '</div>'; /* end flipbook-container */

	return $output;
}

/*----------------------------------------------------------------------------*\
	Add shelf to the page
\*----------------------------------------------------------------------------*/
add_shortcode( 'flipbook-shelf', 'rfbwp_shelf_shortcode' );
function rfbwp_shelf_shortcode( $atts ) {
	if ( empty( $atts[ 'ids' ] ) )
		return __( 'ERROR: The shelf is empty', 'rfbwp' );

	$css_id = uniqid( 'rfbwp_' );

	if ( empty( $atts[ 'style' ] ) || in_array( $atts[ 'style' ], array( 'classic', 'wood-light', 'wood-dark', 'custom-color', 'custom-image' ) ) === false )
		$atts[ 'style' ] = 'classic';

	if ( $atts[ 'style' ] == 'custom-color' ) {
		if ( empty( $atts[ 'color' ] ) ) {
			$atts[ 'style' ] = 'classic';
		} else {
			$style = '<style>';
			$style .= '#' . $css_id . ' .rfbwp-shelf-front { background: ' . $atts[ 'color' ] . '; }';
			$style .= '#' . $css_id . ' .rfbwp-shelf-top { background: ' . $atts[ 'color' ] . '; }';
			$style .= '</style>';
		}
	} else if ( $atts[ 'style' ] == 'custom-image' ) {
		if ( empty( $atts[ 'image' ] ) ) {
			$atts[ 'style' ] = 'classic';
		} else {
			$image = wp_get_attachment_image_src( $atts[ 'image' ], 'full' );
			$style = '<style>';
			$style .= '#' . $css_id . ' .rfbwp-shelf-front { background: url(\'' . $image[ 0 ] . '\'); }';
			$style .= '#' . $css_id . ' .rfbwp-shelf-top { background: url(\'' . $image[ 0 ] . '\'); }';
			$style .= '</style>';
		}
	}

	wp_enqueue_script( 'flipbook-shelf-js', MPC_PLUGIN_ROOT . '/assets/js/flipbook-shelf.min.js', array( 'jquery') );
	wp_enqueue_script( 'turn-js', MPC_PLUGIN_ROOT . '/assets/js/turn.min.js', array( 'jquery') );
	wp_enqueue_script( 'flipbook-js', MPC_PLUGIN_ROOT . '/assets/js/flipbook.min.js', array( 'jquery') );

	do_action( 'rfbwp/flipbook/scripts' );
	
	$ajax_url = admin_url( 'admin-ajax.php' );
	wp_localize_script( 'flipbook-shelf-js', 'rfbwp_ajax', $ajax_url );

	global $mpcrf_options;

	$books = explode( ',', $atts[ 'ids' ] );
	ob_start();
	?>

	<?php if ( isset( $style ) ) echo $style; ?>
	<div id="<?php echo $css_id; ?>" class="rfbwp-shelf rfbwp-shelf-style-<?php echo $atts[ 'style' ]; ?>">
		<div class="rfbwp-shelf-books">
			<?php foreach ( $books as $single_book ) {
				foreach ( $mpcrf_options[ 'books' ] as $book ) {
					$id = strtolower( str_replace( ' ', '_', $book[ 'rfbwp_fb_name' ] ) );
					if ( $id !== trim( $single_book ) )
						continue;

					$top_nav = isset( $book[ 'rfbwp_fb_nav_menu_position' ] ) && $book[ 'rfbwp_fb_nav_menu_position' ] == 'top';
					$side_nav = isset( $book[ 'rfbwp_fb_nav_menu_position' ] ) && ( $book[ 'rfbwp_fb_nav_menu_position' ] == 'aside left' || $book[ 'rfbwp_fb_nav_menu_position' ] == 'aside right' );
					$hard_cover = isset( $book[ 'rfbwp_fb_hc' ] ) && $book[ 'rfbwp_fb_hc' ] == '1' && isset( $book[ 'rfbwp_fb_hc_fco' ] ) && $book[ 'rfbwp_fb_hc_fco' ] != '';

					$nav_height = 0;
					if ( rfbwp_get_value( $book, 'rfbwp_fb_nav_toc' ) || rfbwp_get_value( $book, 'rfbwp_fb_nav_zoom' ) || rfbwp_get_value( $book, 'rfbwp_fb_nav_ss' ) || rfbwp_get_value( $book, 'rfbwp_fb_nav_sap' ) || rfbwp_get_value( $book, 'rfbwp_fb_nav_fs' ) || rfbwp_get_value( $book, 'rfbwp_fb_nav_arrows_toolbar' ) ) {
						if ( isset( $book[ 'rfbwp_fb_nav_general' ] ) && $book[ 'rfbwp_fb_nav_general' ] == '1' ) {
							$nav_height = ( int )$book[ 'rfbwp_fb_nav_general_v_padding' ] * 2 + ( int )$book[ 'rfbwp_fb_nav_general_fontsize' ] + ( int )$book[ 'rfbwp_fb_nav_general_bordersize' ] * 2 + ( $book[ 'rfbwp_fb_nav_menu_type' ] == '1' ? 0 : 20 ) + ( $hard_cover ? 10 : 0 );
						} else {
							$nav_height = 48 + ( $book[ 'rfbwp_fb_nav_menu_type' ] == '1' ? 0 : 20 ) + ( $hard_cover ? 10 : 0 );
						}
					}

					$style = '';
					$border_width = '0';
					if ( isset( $book[ 'rfbwp_fb_border_size' ] ) && (int)$book[ 'rfbwp_fb_border_size' ] > 0 && isset( $book[ 'rfbwp_fb_border_color' ] ) )
						$border_width = $book[ 'rfbwp_fb_border_size' ] . 'px ';

					if ( ! $hard_cover ) {
						if ( isset( $book[ 'rfbwp_fb_border_size' ] ) && (int)$book[ 'rfbwp_fb_border_size' ] > 0 && isset( $book[ 'rfbwp_fb_border_color' ] ) ) {
							$style .= 'padding:' . str_repeat( $border_width, 3 ) . ' 0;';
							$style .= 'background:' . $book[ 'rfbwp_fb_border_color' ] . ';';
						}
						if ( isset( $book[ 'rfbwp_fb_border_radius' ] ) && (int)$book[ 'rfbwp_fb_border_radius' ] > 0 ) {
							$border_radius = $book[ 'rfbwp_fb_border_radius' ] . 'px';
							$style .= 'border-top-right-radius:' . $border_radius . ';';
							$style .= 'border-bottom-right-radius:' . $border_radius . ';';
						}
						if ( isset( $book[ 'rfbwp_fb_outline' ] ) && $book[ 'rfbwp_fb_outline' ] == '1' && isset( $book[ 'rfbwp_fb_outline_color' ] ) ) {
							$style .= 'border:1px solid ' . $book[ 'rfbwp_fb_outline_color' ] . ';';
							$style .= 'border-left:none;';
						}
					}

					/* Fullscreen */
					$fullscreen	= isset( $book['rfbwp_fb_fs_color'] ) ? true : false;
					if( $fullscreen ) {
						$fs_color	= !empty( $book['rfbwp_fb_fs_color'] ) ? $book['rfbwp_fb_fs_color'] : 'transparent';
						$fs_opacity = $book['rfbwp_fb_fs_opacity'];

						$fullscreen_icon = (isset( $book['rfbwp_fb_fs_icon_color'] ) && $book['rfbwp_fb_fs_icon_color'] == "1" ) ? 'true' : 'false';
						$fullscreen = $fs_color . '|' . $fs_opacity . '|' . $fullscreen_icon;
					}

					echo '<a href="#' . $id . '" data-fb-id="' . $id . '" data-fb-w="' . $book[ 'rfbwp_fb_width' ] . '" data-fb-h="' . $book[ 'rfbwp_fb_height' ] . '" data-fb-r="' . $book[ 'rfbwp_fb_width' ] / $book[ 'rfbwp_fb_height' ] . '" data-fb-p="' . $border_width . '" data-fb-n="' . $nav_height . '" data-fb-fs="' . $fullscreen . '" class="rfbwp-shelf-book' . ( $hard_cover ? ' rfbwp-hard-cover' : '' ) . ( $top_nav ? ' rfbwp-nav-top' : '' ) . ( $side_nav ? ' rfbwp-nav-aside' : '' ) . '" style="' . $style . '">';
					if ( $hard_cover )
						echo '<img src="' . $book[ 'rfbwp_fb_hc_fco' ] . '">';
					else if ( isset ( $book[ 'pages' ][ 0 ][ 'rfbwp_fb_page_bg_image' ] ) )
						echo '<img src="' . $book[ 'pages' ][ 0 ][ 'rfbwp_fb_page_bg_image' ] . '">';
					echo '</a>';
				}
			} ?>
		</div>
		<div class="rfbwp-shelf-box"><a href="#" class="rfbwp-close"></a><div class="rfbwp-shelf-wrap"></div></div>
		<div class="rfbwp-shelf-cache"></div>
		<div class="rfbwp-shelf-display">
			<div class="rfbwp-shelf-front"></div>
			<div class="rfbwp-shelf-front-gradient"></div>
			<div class="rfbwp-shelf-top"></div>
			<div class="rfbwp-shelf-top-gradient"></div>
			<div class="rfbwp-shelf-shadow"></div>
		</div>
	</div>

	<?php
	return ob_get_clean();
}

/*----------------------------------------------------------------------------*\
	Add popup to the page
\*----------------------------------------------------------------------------*/
add_shortcode( 'flipbook-popup', 'rfbwp_popup_shortcode' );
function rfbwp_popup_shortcode( $atts, $content ) {
	if ( empty( $atts[ 'id' ] ) )
		return __( 'ERROR: The popup is empty', 'rfbwp' );

	add_action( 'wp_footer', 'rfbwp_popup_markup' );

	wp_enqueue_script( 'flipbook-shelf-js', MPC_PLUGIN_ROOT . '/assets/js/flipbook-shelf.min.js', array( 'jquery') );
	wp_enqueue_script( 'turn-js', MPC_PLUGIN_ROOT . '/assets/js/turn.min.js', array( 'jquery') );
	wp_enqueue_script( 'flipbook-js', MPC_PLUGIN_ROOT . '/assets/js/flipbook.min.js', array( 'jquery') );

	do_action( 'rfbwp/flipbook/scripts' );
	
	$ajax_url = admin_url( 'admin-ajax.php' );
	wp_localize_script( 'flipbook-shelf-js', 'rfbwp_ajax', $ajax_url );

	global $mpcrf_options;

	$flipbook_exists = false;
	$index = 0;
	foreach ( $mpcrf_options[ 'books' ] as $book ) {
		$book_id = strtolower( str_replace( ' ', '_', $book['rfbwp_fb_name'] ) );
		if ( $book_id == $atts['id'] ) {
			$flipbook_exists = true;
			break;
		} else {
			$index++;
		}
	}

	if ( ! $flipbook_exists )
		return __( 'ERROR: There is no book with ID', 'rfbwp' ) . ' <strong>' . $atts[ 'id' ] . '</strong>';

	$book = $mpcrf_options[ 'books' ][ $index ];

	/* Fullscreen */
	$fullscreen	= isset( $book['rfbwp_fb_fs_color'] ) ? true : false;
	if( $fullscreen ) {
		$fs_color	= !empty( $book['rfbwp_fb_fs_color'] ) ? $book['rfbwp_fb_fs_color'] : 'transparent';
		$fs_opacity = $book['rfbwp_fb_fs_opacity'];

		$fullscreen_icon = (isset( $book['rfbwp_fb_fs_icon_color'] ) && $book['rfbwp_fb_fs_icon_color'] == "1" ) ? 'true' : 'false';
		$fullscreen = $fs_color . '|' . $fs_opacity . '|' . $fullscreen_icon;
	}

	$return = '<a href="#' . $atts[ 'id' ] . '" data-fb-id="' . $atts[ 'id' ] . '" data-fb-fs="' . $fullscreen . '" class="rfbwp-popup-book ' . ( isset( $atts[ 'class' ] ) ? esc_attr( $atts[ 'class' ] ) : '' ) . '">' . $content . '</a>';

	return $return;
}

function rfbwp_popup_markup() {
	echo '<div class="rfbwp-popup">';
		echo '<div class="rfbwp-popup-cache"></div>';
		echo '<div class="rfbwp-popup-box">';
			echo '<a href="#" class="rfbwp-close"></a>';
			echo '<div class="rfbwp-popup-wrap"></div>';
		echo '</div>';
	echo '</div>';
}

function rfbwp_get_value( $set, $key ) {
	if ( ! empty( $set[ $key ] ) )
		if ( $set[ $key ] != 0 && $set[ $key ] != '0' && $set[ $key ] != '' )
			return true;
		else
			return false;
	else
		return false;
}

/*----------------------------------------------------------------------------*\
	BASE FLIPBOOK OPTIONS
\*----------------------------------------------------------------------------*/
function rfbwp_set_base_options( $options ) {
	$base_flipbook = array(
		'rfbwp_fb_force_open'              => '0',
		'rfbwp_fb_pre_style'               => 'none',
		'rfbwp_fb_border_size'             => '0',
		'rfbwp_fb_border_color'            => '#ececec',
		'rfbwp_fb_border_radius'           => '0',
		'rfbwp_fb_outline'                 => '0',
		'rfbwp_fb_outline_color'           => '#bfbfbf',
		'rfbwp_fb_inner_shadows'           => '1',
		'rfbwp_fb_edge_outline'            => '0',
		'rfbwp_fb_edge_outline_color'      => '#bfbfbf',
		'rfbwp_fb_fs_color'                => '#ededed',
		'rfbwp_fb_fs_opacity'              => '95',
		'rfbwp_fb_fs_icon_color'           => '1',
		'rfbwp_fb_toc_display_style'       => '0',
		'rfbwp_fb_heading_font'            => '0',
		'rfbwp_fb_heading_family'          => 'default',
		'rfbwp_fb_heading_fontstyle'       => 'regular',
		'rfbwp_fb_heading_size'            => '',
		'rfbwp_fb_heading_line'            => '',
		'rfbwp_fb_heading_color'           => '#2b2b2b',
		'rfbwp_fb_content_font'            => '0',
		'rfbwp_fb_content_family'          => 'default',
		'rfbwp_fb_content_fontstyle'       => 'regular',
		'rfbwp_fb_content_size'            => '',
		'rfbwp_fb_content_line'            => '',
		'rfbwp_fb_content_color'           => '#2b2b2b',
		'rfbwp_fb_num_font'                => '0',
		'rfbwp_fb_num_family'              => 'default',
		'rfbwp_fb_num_fontstyle'           => 'regular',
		'rfbwp_fb_num_size'                => '',
		'rfbwp_fb_num_line'                => '',
		'rfbwp_fb_num_color'               => '#2b2b2b',
		'rfbwp_fb_toc_font'                => '0',
		'rfbwp_fb_toc_family'              => 'default',
		'rfbwp_fb_toc_fontstyle'           => 'regular',
		'rfbwp_fb_toc_size'                => '',
		'rfbwp_fb_toc_line'                => '',
		'rfbwp_fb_toc_color'               => '#2b2b2b',
		'rfbwp_fb_toc_colorhover'          => '#333333',
		'rfbwp_fb_zoom_border_size'        => '10',
		'rfbwp_fb_zoom_border_color'       => '#ececec',
		'rfbwp_fb_zoom_border_radius'      => '10',
		'rfbwp_fb_zoom_outline'            => '1',
		'rfbwp_fb_zoom_outline_color'      => '#d0d0d0',
		'rfbwp_fb_sa_thumb_cols'           => '3',
		'rfbwp_fb_sa_thumb_border_size'    => '1',
		'rfbwp_fb_sa_thumb_border_color'   => '#878787',
		'rfbwp_fb_sa_vertical_padding'     => '10',
		'rfbwp_fb_sa_horizontal_padding'   => '10',
		'rfbwp_fb_sa_border_size'          => '10',
		'rfbwp_fb_sa_border_color'         => '#f6f6f6',
		'rfbwp_fb_sa_border_radius'        => '10',
		'rfbwp_fb_sa_outline'              => '1',
		'rfbwp_fb_sa_outline_color'        => '#d6d6d6',
		'rfbwp_fb_nav_menu_type'           => '0',
		'rfbwp_fb_nav_menu_position'       => 'bottom',
		'rfbwp_fb_nav_stack'               => '0',
		'rfbwp_fb_nav_text'                => '0',
		'rfbwp_fb_nav_toc'                 => '1',
		'rfbwp_fb_nav_toc_order'           => '1',
		'rfbwp_fb_nav_toc_index'           => '2',
		'rfbwp_fb_nav_toc_icon'            => 'fa fa-th-list',
		'rfbwp_fb_nav_zoom'                => '1',
		'rfbwp_fb_nav_zoom_order'          => '2',
		'rfbwp_fb_nav_zoom_icon'           => 'fa fa-search-plus',
		'rfbwp_fb_nav_zoom_out_icon'       => 'fa fa-search-minus',
		'rfbwp_fb_nav_ss'                  => '1',
		'rfbwp_fb_nav_ss_order'            => '3',
		'rfbwp_fb_nav_ss_icon'             => 'fa fa-play',
		'rfbwp_fb_nav_ss_stop_icon'        => 'fa fa-pause',
		'rfbwp_fb_nav_ss_delay'            => '2000',
		'rfbwp_fb_nav_sap'                 => '1',
		'rfbwp_fb_nav_sap_order'           => '4',
		'rfbwp_fb_nav_sap_icon_prev'       => 'fa fa-chevron-up',
		'rfbwp_fb_nav_sap_icon_next'       => 'fa fa-chevron-down',
		'rfbwp_fb_nav_sap_icon'            => 'fa fa-th',
		'rfbwp_fb_nav_sap_icon_close'      => 'fa fa-times',
		'rfbwp_fb_nav_fs'                  => '1',
		'rfbwp_fb_nav_fs_order'            => '5',
		'rfbwp_fb_nav_fs_icon'             => 'fa fa-expand',
		'rfbwp_fb_nav_fs_close_icon'       => 'fa fa-compress',
		'rfbwp_fb_nav_arrows'              => '1',
		'rfbwp_fb_nav_arrows_toolbar'      => '0',
		'rfbwp_fb_nav_prev_icon'           => 'fa fa-chevron-left',
		'rfbwp_fb_nav_next_icon'           => 'fa fa-chevron-right',
		'rfbwp_fb_nav_general'             => '1',
		'rfbwp_fb_nav_general_v_padding'   => '15',
		'rfbwp_fb_nav_general_h_padding'   => '15',
		'rfbwp_fb_nav_general_margin'      => '20',
		'rfbwp_fb_nav_general_fontsize'    => '22',
		'rfbwp_fb_nav_general_bordersize'  => '0',
		'rfbwp_fb_nav_general_shadow'      => '0',
		'rfbwp_fb_nav_default'             => '1',
		'rfbwp_fb_nav_default_color'       => '#2b2b2b',
		'rfbwp_fb_nav_default_background'  => '',
		'rfbwp_fb_nav_hover'               => '1',
		'rfbwp_fb_nav_hover_color'         => '#22b4d8',
		'rfbwp_fb_nav_hover_background'    => '',
		'rfbwp_fb_nav_border_default'      => '1',
		'rfbwp_fb_nav_border_color'        => '',
		'rfbwp_fb_nav_border_radius'       => '2',
		'rfbwp_fb_nav_border_hover'        => '1',
		'rfbwp_fb_nav_border_hover_color'  => '',
		'rfbwp_fb_nav_border_hover_radius' => '2',
		'rfbwp_fb_num'                     => '0',
		'rfbwp_fb_num_hide'                => '0',
		'rfbwp_fb_num_style'               => '1',
		'rfbwp_fb_num_background'          => '',
		'rfbwp_fb_num_border'              => '1',
		'rfbwp_fb_num_border_color'        => '',
		'rfbwp_fb_num_border_size'         => '2',
		'rfbwp_fb_num_border_radius'       => '2',
		'rfbwp_fb_num_v_position'          => 'bottom',
		'rfbwp_fb_num_h_position'          => 'center',
		'rfbwp_fb_num_v_padding'           => '',
		'rfbwp_fb_num_h_padding'           => '',
		'rfbwp_fb_num_v_margin'            => '',
		'rfbwp_fb_num_h_margin'            => '',
		'rfbwp_fb_hc'                      => '0',
		'rfbwp_fb_hc_fco'                  => '',
		'rfbwp_fb_hc_fci'                  => '',
		'rfbwp_fb_hc_fcc'                  => '#dddddd',
		'rfbwp_fb_hc_bco'                  => '',
		'rfbwp_fb_hc_bci'                  => '',
		'rfbwp_fb_hc_bcc'                  => '#dddddd',
		'pages'                            => array()
	);

	$base_page = array(
		'rfbwp_fb_page_type'          => 'Single Page',
		'rfbwp_fb_page_bg_image'      => '',
		'rfbwp_fb_page_bg_image_zoom' => '',
		'rfbwp_fb_page_index'         => '0',
		'rfbwp_fb_page_custom_class'  => '',
		'rfbwp_fb_page_title'         => '',
		'rfbwp_page_css'              => '',
		'rfbwp_page_html'             => '',
		'rfbwp_page_html_second'      => '',
		'rfbwp_fb_page_url'           => '',
	);

	if ( ! empty( $options[ 'books' ] ) ) {
		foreach( $options[ 'books' ] as $book_id => $book ) {
			$options[ 'books' ][ $book_id ] = array_merge( $base_flipbook, $options[ 'books' ][ $book_id ] );

			if ( ! empty( $options[ 'books' ][ $book_id ][ 'pages' ] ) ) {
				foreach( $options[ 'books' ][ $book_id ][ 'pages' ] as $page_id => $page ) {
					$options[ 'books' ][ $book_id ][ 'pages' ][ $page_id ] = array_merge( $base_page, $options[ 'books' ][ $book_id ][ 'pages' ][ $page_id ] );
				}
			}
		}
	}

	return $options;
}
