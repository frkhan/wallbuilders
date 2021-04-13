<?php
/** Divi integration
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Divi exit widget.
 *
 * @param array $widgets The widgets.
 *
 * @return array The Divi exit widget.
 */
function swift_control_divi_exit_widget( $widgets ) {

	global $post;

	if ( isset( $_GET['et_fb'] ) ) {
		$widgets = array( 'divi_active' );
	}

	return $widgets;

}
add_filter( 'swift_control_active_widgets', 'swift_control_divi_exit_widget' );

function swift_control_divi_exit_url( $widget_url, $widget_key, $settings ) {

	if ( 'divi_active' === $widget_key ) {

		$post_id = get_the_ID();

		$is_divi_library = 'et_pb_layout' === get_post_type( $post_id );

		$widget_url = $is_divi_library ? get_edit_post_link( $post_id ) : get_permalink( $post_id );

	}

	return $widget_url;

}
add_filter( 'swift_control_output_widget_url', 'swift_control_divi_exit_url', 0, 3 );

function swift_control_divi_widget_class( $widget_class, $widget_key, $parsed_settings ) {

	if ( 'divi_active' === $widget_key ) {
		$widget_class = 'swift-control-exit-divi';
	}

	return $widget_class;

}
add_filter( 'swift_control_output_widget_class', 'swift_control_divi_widget_class', 0, 3 );

function swift_control_divi_icon_class( $icon_class, $widget_key, $parsed_settings ) {

	if ( 'divi_active' === $widget_key ) {
		$icon_class = 'fas fa-sign-out-alt';
	}

	return $icon_class;

}
add_filter( 'swift_control_output_icon_class', 'swift_control_divi_icon_class', 0, 3 );

function swift_control_divi_widget_name( $widget_name, $widget_key, $parsed_settings ) {

	if ( 'divi_active' === $widget_key ) {
		$widget_name = 'Exit Divi Builder';
	}

	return $widget_name;

}
add_filter( 'swift_control_output_widget_name', 'swift_control_divi_widget_name', 0, 3 );

function swift_control_divi_exit_javascript() {

	global $post;

	if ( isset( $_GET['et_fb'] ) ) {

	?>

	<script type="text/javascript">
		jQuery(document).ready(function($) {

			function removeDiviClass() {

				var iFrameDOM = $("#et-fb-app-frame").contents();
				iFrameDOM.find('.swift-control-widgets').removeClass('et-fb-root-ancestor-sibling');

			}
			setTimeout(removeDiviClass, 2000);

		});
	</script>

	<?php

	}

}
add_action( 'wp_footer', 'swift_control_divi_exit_javascript' );
