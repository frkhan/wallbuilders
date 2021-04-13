<?php
/** WP Swift Control's functions.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Output memu items on settings page.
 *
 * @param string $widget_key The widget key.
 */
function swift_control_pro_settings_output_widget_item( $widget_key ) {

	if ( ! isset( $GLOBALS['swift_control_default_settings'] ) || ! isset( $GLOBALS['swift_control_widget_settings'] ) ) {
		return;
	}

	$default_settings        = $GLOBALS['swift_control_default_settings'];
	$default_widget_settings = isset( $default_settings[ $widget_key ] ) ? $default_settings[ $widget_key ] : array();
	$saved_widget_settings   = $GLOBALS['swift_control_widget_settings'];

	// If the setting from database is empty, then take the data from default settings.
	$widget_settings = ! empty( $saved_widget_settings[ $widget_key ] ) ? $saved_widget_settings[ $widget_key ] : array();
	$parsed_settings = swift_control_pro_parse_widget_settings( $widget_key, $widget_settings );

	$icon_class   = $parsed_settings['icon_class'];
	$widget_name  = $parsed_settings['text'];
	$widget_url   = $parsed_settings['url'];
	$tab_target   = $parsed_settings['new_tab'];
	$redirect_url = $parsed_settings['redirect_url'];

	// Checking $widget_settings['roles'] for backward compatibility.
	if ( ! isset( $widget_settings['roles'] ) ) {
		$widget_roles = array( 'administrator', 'editor' );

		if ( swift_control_pro_is_network_active() ) {
			array_push( $widget_roles, 'super_admin' );
		}
	} else {
		$widget_roles = $parsed_settings['roles'];
	}

	$roles_obj = new \WP_Roles();
	$roles     = $roles_obj->role_names;

	$item_class       = '';
	$is_custom_widget = false;

	if ( false !== stripos( $widget_key, 'new_' ) ) {
		$common_widgets = array( 'new_post', 'new_page', 'new_product' );

		if ( ! in_array( $widget_key, $common_widgets, true ) ) {
			$item_class = 'advanced-widget';
		}
	} elseif ( false !== stripos( $widget_key, 'custom_widget_' ) ) {
		$item_class       = 'custom-widget';
		$is_custom_widget = true;
	}
	?>

	<li class="widget-item <?php echo esc_attr( $item_class ); ?>" data-widget-key="<?php echo esc_attr( $widget_key ); ?>">
		<div class="cols widget-default-area">
			<div class="col widget-item-col drag-wrapper">
				&nbsp;
				<span class="drag-handle"></span>
			</div>
			<div class="col widget-item-col icon-wrapper">
				<div class="widget-icon dblclick-trigger">
					<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					<button type="button" class="icon-picker blur-trigger"></button>
				</div>
			</div>
			<div class="col widget-item-col text-wrapper">
				<input type="text" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_text" class="text-field widget-text-field dblclick-trigger" value="<?php echo esc_html( $widget_name ); ?>" readonly>
			</div>
			<div class="col widget-item-col extra-settings-wrapper">

				<?php if ( $is_custom_widget ) : ?>
					<div class="widget-item-control edit-mode-control widget-url-setting">
						<input type="url" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_url" class="text-field widget-url-field" value="<?php echo esc_html( $widget_url ); ?>" placeholder="<?php _e( 'Widget Url', 'swift-control' ); ?>">
					</div>
				<?php endif; ?>

				<?php if ( isset( $default_widget_settings['redirect_url'] ) || isset( $widget_settings['redirect_url'] ) ) : ?>
					<div class="widget-item-control edit-mode-control redirect-url-setting">
						<input type="url" id="swift_control_<?php echo esc_attr( $widget_key ); ?>_redirect_url" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_redirect_url" class="text-field redirect-url-field" value="<?php echo esc_html( $redirect_url ); ?>" placeholder="<?php _e( 'Redirect Url', 'swift-control' ); ?>">
					</div>
				<?php endif; ?>

				<?php if ( isset( $default_widget_settings['new_tab'] ) || isset( $widget_settings['new_tab'] ) ) : ?>
					<div class="widget-item-control edit-mode-control new-tab-setting">
						<label for="swift_control_<?php echo esc_attr( $widget_key ); ?>_new_tab" class="label checkbox-label blur-trigger">
							<?php _e( 'New tab', 'swift-control' ); ?>
							<input type="checkbox" name="swift_control_<?php echo esc_attr( $widget_key ); ?>_new_tab" id="swift_control_<?php echo esc_attr( $widget_key ); ?>_new_tab" value="1" class="new-tab-field" <?php checked( $tab_target, 1 ); ?>>
							<div class="indicator"></div>
						</label>
					</div>
				<?php endif; ?>

			</div>
			<div class="col widget-item-col actions-wrapper">
				<button type="button" class="widget-item-control edit-button">
					<?php _e( 'Edit', 'swift-control' ); ?>
				</button>

				<?php if ( false !== stripos( $widget_key, 'custom_widget_' ) ) : ?>
					<button class="delete-button delete-widget">
						<i class="fas fa-times"></i>
					</button>
				<?php endif; ?>
			</div>
		</div><!-- .widget-default-area -->

		<div class="widget-advanced-area">
			<div class="setting-fields is-gapless">
				<div class="field">
					<label for="swift_control_<?php echo esc_attr( $widget_key ); ?>_roles" class="label widget-roles-label select2-label">
						<p>
							<?php _e( 'Show this widget for:', 'swift-control' ); ?>
						</p>
						<select name="swift_control_<?php echo esc_attr( $widget_key ); ?>_roles[]" id="swift_control_<?php echo esc_attr( $widget_key ); ?>_roles" class="is-fullwidth widget-roles-field" multiple>
							<option value="all" <?php echo esc_attr( in_array( 'all', $widget_roles, true ) ? 'selected' : '' ); ?>>
								<?php _e( 'All', 'swift-control' ); ?>
							</option>

							<?php if ( swift_control_pro_is_network_active() ) : ?>
								<option value="super_admin" <?php echo esc_attr( in_array( 'super_admin', $widget_roles, true ) ? 'selected' : '' ); ?>>
									<?php _e( 'Super Admin', 'swift-control' ); ?>
								</option>
							<?php endif; ?>

							<?php foreach ( $roles as $role_key => $role_name ) : ?>
								<?php
								$selected_attr = '';

								if ( in_array( $role_key, $widget_roles, true ) ) {
									$selected_attr = 'selected';
								}
								?>
								<option value="<?php echo esc_attr( $role_key ); ?>" <?php echo esc_attr( $selected_attr ); ?>>
									<?php echo esc_attr( $role_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
		</div><!-- .widget-advanced-area -->
	</li><!-- .widget-item -->

	<?php
}

/**
 * Check if swift control has active widgets.
 *
 * @return boolean Whether swift control has active widget or not.
 */
function swift_control_pro_has_active_widgets() {
	// Fetch active widgets from db.
	$active_widgets = get_option( 'swift_control_active_widgets' );

	// Check if this is fresh install.
	if ( false === $active_widgets ) {
		$active_widgets = require __DIR__ . '/inc/active-widgets.php';
	}

	return empty( $active_widgets ) ? false : true;
}

/**
 * Get default active widgets.
 *
 * @return array The default active widgets.
 */
function swift_control_pro_get_default_active_widgets() {
	$default_active_widgets = require __DIR__ . '/inc/active-widgets.php';
	$default_active_widgets = apply_filters( 'swift_control_default_active_widgets', $default_active_widgets );
	$active_widgets         = array();

	foreach ( $default_active_widgets as $widget_key => $widget_setting ) {
		array_push( $active_widgets, $widget_key );
	}

	return $active_widgets;
}

/**
 * Get un-used default active widgets.
 *
 * @return array The un-used default active widgets.
 */
function swift_control_pro_get_unused_default_active_widgets() {
	$default_active_widgets = swift_control_pro_get_default_active_widgets();
	$active_widgets         = swift_control_pro_get_active_widgets();
	$unused_widgets         = array();

	foreach ( $default_active_widgets as $widget_key ) {
		if ( ! in_array( $widget_key, $active_widgets, true ) ) {
			array_push( $unused_widgets, $widget_key );
		}
	}

	return $unused_widgets;
}

/**
 * Get active widgets.
 *
 * @return array The active widgets.
 */
function swift_control_pro_get_active_widgets() {
	// Fetch active widgets from db.
	$active_widgets = get_option( 'swift_control_active_widgets' );

	// Check if this is fresh install.
	if ( false === $active_widgets ) {
		$active_widgets = swift_control_pro_get_default_active_widgets();
	}

	if ( empty( $active_widgets ) ) {
		return array();
	}

	return apply_filters( 'swift_control_active_widgets', $active_widgets );
}

/**
 * Get all custom widgets.
 *
 * @return array All custom widgets.
 */
function swift_control_pro_get_custom_widgets() {
	$custom_widgets = get_option( 'swift_control_custom_widgets', array() );

	return apply_filters( 'swift_control_custom_widgets', $custom_widgets );
}

/**
 * Get inactive custom widgets.
 *
 * @return array The inactive custom widgets.
 */
function swift_control_pro_get_inactive_custom_widgets() {
	$custom_widgets   = swift_control_pro_get_custom_widgets();
	$active_widgets   = swift_control_pro_get_active_widgets();
	$inactive_widgets = array();

	foreach ( $custom_widgets as $widget_key ) {
		if ( ! in_array( $widget_key, $active_widgets, true ) ) {
			array_push( $inactive_widgets, $widget_key );
		}
	}

	return apply_filters( 'swift_control_inactive_custom_widgets', $inactive_widgets );
}

/**
 * Get available widgets.
 *
 * @return array The available widgets.
 */
function swift_control_pro_get_available_widgets() {
	$default_available_widgets = require __DIR__ . '/inc/available-widgets.php';
	$default_available_widgets = apply_filters( 'swift_control_default_available_widgets', $default_available_widgets );
	$available_widgets         = array();
	$active_widgets            = swift_control_pro_get_active_widgets();
	$unused_active_widgets     = swift_control_pro_get_unused_default_active_widgets();
	$inactive_custom_widgets   = swift_control_pro_get_inactive_custom_widgets();

	// Loop over available widgets and collect their keys.
	foreach ( $default_available_widgets as $widget_key => $default_setting ) {
		array_push( $available_widgets, $widget_key );
	}

	// Merge the un-used default active widgets with existing available widgets.
	$available_widgets = array_merge( $unused_active_widgets, $available_widgets );

	// Merge the inactive custom widgets with existing available widgets.
	$available_widgets = array_merge( $inactive_custom_widgets, $available_widgets );

	// Reduce the available widgets by the real active widgets.
	$available_widgets = array_diff( $available_widgets, $active_widgets );
	$available_widgets = empty( $available_widgets ) ? array() : $available_widgets;

	return apply_filters( 'swift_control_available_widgets', $available_widgets );
}

/**
 * Get locked pro widgets.
 *
 * @return array The locked widgets.
 */
function swift_control_pro_get_locked_widgets() {
	$pro_widgets    = require __DIR__ . '/inc/pro-widgets.php';
	$locked_widgets = array();

	// Loop over pro widgets and collect their keys.
	foreach ( $pro_widgets as $widget_key => $locked_setting ) {
		array_push( $locked_widgets, $widget_key );
	}

	return $locked_widgets;
}

/**
 * Get default settings of both active & available widgets.
 *
 * @return array The default widget settings.
 */
function swift_control_pro_get_default_widget_settings() {
	// Import the default widgets.
	$default_available_widgets = require __DIR__ . '/inc/available-widgets.php';
	$default_available_widgets = apply_filters( 'swift_control_default_available_widgets', $default_available_widgets );
	$default_active_widgets    = require __DIR__ . '/inc/active-widgets.php';
	$default_active_widgets    = apply_filters( 'swift_control_default_active_widgets', $default_active_widgets );

	// Define default settings.
	$default_settings = array();

	// Loop over active widgets to get default settings.
	foreach ( $default_active_widgets as $widget_key => $default_setting ) {
		$default_settings[ $widget_key ] = $default_setting;
	}

	// Also loop over available widgets.
	foreach ( $default_available_widgets as $widget_key => $default_setting ) {
		// Prevent duplicated widget settings.
		if ( ! isset( $default_settings[ $widget_key ] ) ) {
			$default_settings[ $widget_key ] = $default_setting;
		}
	}

	return apply_filters( 'swift_control_default_widget_settings', $default_settings );
}

/**
 * Get saved settings from database.
 *
 * @return array The saved settings.
 */
function swift_control_pro_get_saved_widget_settings() {
	$settings = get_option( 'swift_control_widget_settings', array() );

	return apply_filters( 'swift_control_saved_widget_settings', $settings );
}

/**
 * Get default settings of locked widgets.
 *
 * @return array The locked widget settings.
 */
function swift_control_pro_get_locked_widget_settings() {
	$pro_widgets     = require __DIR__ . '/inc/pro-widgets.php';
	$locked_settings = array();

	// Loop over pro widgets and collect their settings.
	foreach ( $pro_widgets as $widget_key => $locked_setting ) {
		$locked_settings[ $widget_key ] = $locked_setting;
	}

	return apply_filters( 'swift_control_locked_widget_settings', $locked_settings );
}

/**
 * Get default color settings.
 *
 * @return array The default color settings.
 */
function swift_control_pro_get_default_color_settings() {
	return array(
		'widget_bg_color'           => '#f5f5f7',
		'widget_bg_color_hover'     => '#ededf0',
		'widget_icon_color'         => '#616666',
		'setting_button_bg_color'   => '#860ee6',
		'setting_button_icon_color' => '#ffffff',
	);
}

/**
 * Get color settings.
 *
 * @return array The color settings.
 */
function swift_control_pro_get_color_settings() {
	$saved_color_settings   = get_option( 'swift_control_color_settings', array() );
	$default_color_settings = swift_control_pro_get_default_color_settings();
	$color_settings         = array();

	foreach ( $default_color_settings as $color_key => $color_value ) {
		$color_settings[ $color_key ] = isset( $saved_color_settings[ $color_key ] ) ? $saved_color_settings[ $color_key ] : $default_color_settings[ $color_key ];
	}

	return $color_settings;
}

/**
 * Get miscellaneous settings.
 *
 * @return array The miscellaneous settings.
 */
function swift_control_pro_get_misc_settings() {
	$misc_settings = get_option( 'swift_control_misc_settings', array() );
	$switch_blog   = swift_control_pro_needs_to_switch_blog();

	if ( $switch_blog ) {
		$misc_settings = get_blog_option( get_main_site_id(), 'swift_control_misc_settings', array() );
	}

	$misc_settings = apply_filters( 'swift_control_pro_get_misc_settings', $misc_settings );

	return $misc_settings;
}

/**
 * Get display settings.
 *
 * @return array The display settings.
 */
function swift_control_pro_get_display_settings() {
	$saved_settings = get_option( 'swift_control_display_settings', array() );

	$display_settings = array(
		'remove_indicator' => isset( $saved_settings['remove_indicator'] ) ? absint( $saved_settings['remove_indicator'] ) : 0,
		'expanded'         => isset( $saved_settings['expanded'] ) ? absint( $saved_settings['expanded'] ) : 0,
	);

	return $display_settings;
}

/**
 * Parse widget settings
 *
 * @param array $widget_key The widget key.
 * @param array $widget_settings The widget settings from database.
 *
 * @return array The parsed widget settings.
 */
function swift_control_pro_parse_widget_settings( $widget_key, $widget_settings ) {
	$default_settings        = swift_control_pro_get_default_widget_settings();
	$default_widget_settings = isset( $default_settings[ $widget_key ] ) ? $default_settings[ $widget_key ] : array();
	$manual_defaults         = array(
		'icon_class'   => '',
		'text'         => '',
		'url'          => '',
		'new_tab'      => '',
		'redirect_url' => '',
		'roles'        => array(),
	);

	$parsed_defaults = wp_parse_args( $default_widget_settings, $manual_defaults );

	return array(
		'icon_class'   => isset( $widget_settings['icon_class'] ) ? $widget_settings['icon_class'] : $parsed_defaults['icon_class'],
		'text'         => isset( $widget_settings['text'] ) ? $widget_settings['text'] : $parsed_defaults['text'],
		'url'          => isset( $widget_settings['url'] ) ? $widget_settings['url'] : $parsed_defaults['url'],
		'new_tab'      => isset( $widget_settings['new_tab'] ) ? absint( $widget_settings['new_tab'] ) : $parsed_defaults['new_tab'],
		'redirect_url' => isset( $widget_settings['redirect_url'] ) ? $widget_settings['redirect_url'] : $parsed_defaults['redirect_url'],
		'roles'        => isset( $widget_settings['roles'] ) ? $widget_settings['roles'] : $parsed_defaults['roles'],
	);
}

/**
 * Check with what builder is current post built with.
 *
 * @return string The builder name.
 */
function swift_control_pro_get_page_builder() {
	global $post;

	if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $post->ID ) ) {
		return 'elementor';
	}

	if ( function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( $post->ID ) ) {
		return 'divi';
	}

	if ( class_exists( '\FLBuilderModel' ) && \FLBuilderModel::is_builder_enabled() ) {
		return 'beaver';
	}

	if ( class_exists( '\Brizy_Editor_Post' ) && \Brizy_Editor_Post::get( $post->ID )->uses_editor() ) {
		return 'brizy';
	}

	if ( defined( 'CT_VERSION' ) && ! empty( get_post_meta( $post->ID, 'ct_other_template', true ) ) ) {
		return 'oxygen';
	}

	return 'default';
}

/**
 * Get edit page url.
 *
 * @return string The edit page url.
 */
function swift_control_pro_get_edit_post_url() {
	global $post;

	$builder = swift_control_pro_get_page_builder();

	switch ( $builder ) {
		case 'elementor':
			return admin_url( 'post.php?post=' . $post->ID . '&action=elementor' );
		case 'divi':
			return et_fb_get_vb_url( get_permalink( $post ) );
		case 'beaver':
			return get_permalink( $post ) . '?fl_builder';
		case 'brizy':
			if ( 'draft' === $post->post_status ) {
				return get_permalink( $post ) . '&brizy-edit';
			} else {
				return get_permalink( $post ) . '?brizy-edit';
			}
		case 'oxygen':
			return get_permalink( $post ) . '?ct_builder=true&ct_inner=true';
	}

	return get_edit_post_link();
}

/**
 * Check if current page is in edit mode inside page builder.
 *
 * @return boolean
 */
function swift_control_pro_is_inside_page_builder() {
	global $post;

	if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['fl_builder'] ) || isset( $_GET['brizy-edit-iframe'] ) || isset( $_GET['ct_builder'] ) ) {
		return true;
	}

	return false;
}

/**
 * Get active inline editor.
 *
 * @return string The inline editor name if available.
 */
function swift_control_pro_get_inline_editor() {
	$builder = swift_control_pro_get_page_builder();

	if ( empty( $builder ) ) {
		return '';
	}

	if ( defined( 'WAL_PLUGIN_FILE_PATH' ) ) {
		/**
		 * When writing this, Walace Inline provide info on their website
		 * that they are compatible with Beaver Builder and Elementor.
		 * Compatibility for Gutenberg and other builders coming soon.
		 *
		 * @see https://wallaceinline.com/
		 * @see https://wallaceinline.com/changelog-roadmap/
		 */
		return ( 'elementor' === $builder || 'beaver' === $builder ? 'wallace' : '' );
	}

	return '';
}

/**
 * Generate random string.
 *
 * @link https://stackoverflow.com/questions/4356289/php-random-string-generator/#answer-4356295
 *
 * @param integer $length The wanted character length.
 * @return string The random string.
 */
function swift_control_pro_generate_random_string( $length = 10 ) {
	$characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters_length = strlen( $characters );
	$random_string     = '';

	for ( $i = 0; $i < $length; $i++ ) {
		$random_string .= $characters[ rand( 0, $characters_length - 1 ) ];
	}

	return $random_string;
}

/**
 * Parse widget name.
 *
 * @param string $widget_name The widget name.
 * @param string $widget_key The widget key.
 * @param array  $settings The parsed widget settings.
 *
 * @return string The parsed widget name.
 */
function swift_control_pro_parse_widget_name( $widget_name, $widget_key, $settings = array() ) {
	if ( is_singular() ) {
		global $post;

		if ( false !== stripos( $widget_name, '{post_type}' ) ) {
			$post_type_object        = get_post_type_object( $post->post_type );
			$post_type_singular_name = $post_type_object->labels->singular_name;
			$widget_name             = str_ireplace( '{post_type}', $post_type_singular_name, $widget_name );
		}
	} else {
		if ( 'edit_post_type' === $widget_key ) {
			$widget_name = __( 'Disabled', 'swift-control' );
		}
	}

	return ucwords( $widget_name );
}

/**
 * Parse widget url.
 *
 * @param string $widget_url The widget url.
 * @param string $widget_key The widget key.
 * @param array  $settings The parsed widget settings.
 *
 * @return string The parsed widget url.
 */
function swift_control_pro_parse_widget_url( $widget_url, $widget_key, $settings = array() ) {
	global $wp;

	switch ( $widget_key ) {
		case 'theme_customizer':
			$widget_url = add_query_arg(
				array(
					'url' => rawurlencode( home_url( $wp->request ) ),
				),
				$widget_url
			);
			break;
		case 'edit_post_type':
			$widget_url = swift_control_pro_get_edit_post_url();
			break;
	}

	$current_url = home_url( $wp->request );

	$widget_url = str_ireplace( '{site_url}', home_url(), $widget_url );
	$widget_url = str_ireplace( '{current_url}', $current_url, $widget_url );

	return $widget_url;
}

/**
 * Parse widget class.
 *
 * @param string $widget_class The widget class names.
 * @param string $widget_key The widget key.
 * @param array  $settings The parsed widget settings.
 *
 * @return string The parsed widget class.
 */
function swift_control_pro_parse_widget_class( $widget_class, $widget_key, $settings = array() ) {
	$space_prefix = empty( $widget_class ) ? '' : ' ';

	if ( 'edit_post_type' === $widget_key ) {
		if ( ! is_singular() ) {
			$widget_class .= $space_prefix . 'is-disabled';
		} else {
			$inline_editor = swift_control_pro_get_inline_editor();

			if ( ! empty( $inline_editor ) ) {
				$widget_class .= $space_prefix . 'inline-edit';

				switch ( $inline_editor ) {
					case 'wallace':
						$widget_class .= ' wallace-edit';
						break;
				}
			}
		}
	}

	return $widget_class;
}

/**
 * Check whether plugin is active on multisite network wide or not.
 *
 * @return bool
 */
function swift_control_pro_is_network_active() {
	// Load plugin.php if it doesn't already exist.
	if ( ! function_exists( 'is_plugin_active_for_network' ) || ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	return ( is_multisite() && is_plugin_active_for_network( 'swift-control-pro/swift-control-pro.php' ) ? true : false );
}

/**
 * Check if we need to to switch blog or get data from other blog.
 *
 * @return bool
 */
function swift_control_pro_needs_to_switch_blog() {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	return ( swift_control_pro_is_network_active() && ! is_main_site() ) ? true : false;
}
