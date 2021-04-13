<?php
/**
 * Setup Swift Control.
 *
 * @package Swift_Control
 */

namespace SwiftControlPro;

/**
 * Setup dynamic widgets.
 */
class Dynamic_Widgets {
	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {
		// Dynamic new_post_type widget.
		add_filter( 'swift_control_default_available_widgets', array( $this, 'new_post_type_widget' ) );

		// Logout widget.
		add_filter( 'swift_control_default_available_widgets', array( $this, 'logout_widget' ) );
		add_filter( 'swift_control_output_widget_url', array( $this, 'logout_widget_url' ), 10, 3 );
	}

	/**
	 * Add "new_post_type" widget to available widgets.
	 *
	 * @param array $available_widgets The existing available widgets.
	 * @return array The modified available widgets.
	 */
	public function new_post_type_widget( $available_widgets ) {
		$public_post_types = get_post_types(
			array(
				'public'             => true,
				'publicly_queryable' => true,
				'_builtin'           => false,
			)
		);

		$excluded_widgets = require __DIR__ . '/inc/excluded-widgets.php';

		foreach ( $public_post_types as $post_type ) {
			if ( ! isset( $available_widgets[ 'new_' . $post_type ] ) && ! in_array( 'new_' . $post_type, $excluded_widgets, true ) ) {
				$post_type_object = get_post_type_object( $post_type );
				// We don't use `$post_type_object->menu_icon` since they might not be using Font Awesome.
				$icon_class = 'fas fa-pencil-alt';

				// Parse the `icon_class` manually based on their brand.
				switch ( $post_type ) {
					case 'product':
						$icon_class = 'fas fa-cart-plus';
						break;
					case 'elementor_library':
						$icon_class = 'fab fa-elementor';
						break;
				}

				$available_widgets[ 'new_' . $post_type ] = array(
					'text'       => ucwords( $post_type_object->labels->new_item ),
					'url'        => admin_url( 'post-new.php?post_type=' . $post_type ),
					'new_tab'    => false,
					'icon_class' => $icon_class,
				);
			}
		}

		return $available_widgets;
	}

	/**
	 * Add "logout" widget to available widgets.
	 *
	 * @param array $available_widgets The existing available widgets.
	 * @return array The modified available widgets.
	 */
	public function logout_widget( $available_widgets ) {
		$available_widgets['logout'] = array(
			'text'         => __( 'Logout', 'swift-control' ),
			'url'          => 'auto',
			'icon_class'   => 'fas fa-sign-out-alt',
			'redirect_url' => '',
		);

		return $available_widgets;
	}

	/**
	 * Setting up logout widget's url.
	 *
	 * @param string $widget_url The widget url.
	 * @param string $widget_key The widget key.
	 * @param array  $settings The parsed widget settings.
	 *
	 * @return string The modified widget url.
	 */
	public function logout_widget_url( $widget_url, $widget_key, $settings ) {
		if ( 'logout' === $widget_key ) {
			$widget_url = wp_logout_url( $settings['redirect_url'] );
		}

		return $widget_url;
	}
}
