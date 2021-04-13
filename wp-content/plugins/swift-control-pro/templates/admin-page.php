<?php
/**
 * Swift Control page template.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="wrap settingstuff swift-control-settings">

	<h1>
		<?php echo esc_html( get_admin_page_title() ); ?>
		<span style="font-size: 60%; opacity: .6; font-weight: 600; background: #ccc; border-radius: 5px; padding: 5px 12px; line-height: 1;"><?php echo esc_html( SWIFT_CONTROL_PRO_PLUGIN_VERSION ); ?></span>
	</h1>

	<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings'; ?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=swift-control&tab=settings" class="nav-tab<?php echo esc_attr( 'settings' === $active_tab ? ' nav-tab-active' : '' ); ?>"><?php _e( 'Settings', 'swift-control' ); ?></a>
		<a href="?page=swift-control&tab=license" class="nav-tab<?php echo esc_attr( 'license' === $active_tab ? ' nav-tab-active' : '' ); ?>"><?php _e( 'License', 'swift-control' ); ?></a>
		<?php do_action( 'swift_control_pro_settings_tab_menu', $active_tab ); ?>
	</h2>

	<?php

	if ( 'settings' === $active_tab ) {

		require_once __DIR__ . '/widget-settings.php';

	} elseif ( 'license' === $active_tab ) {

		require_once __DIR__ . '/license-settings.php';

	}

	do_action( 'swift_control_pro_settings_tab_content', $active_tab );

	?>

</div>
