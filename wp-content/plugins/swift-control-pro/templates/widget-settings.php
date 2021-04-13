<?php
/**
 * Swift Control widget settings template.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Get the widgets.
$active_widgets    = swift_control_pro_get_active_widgets();
$available_widgets = swift_control_pro_get_available_widgets();

// Get the settings.
$default_settings = swift_control_pro_get_default_widget_settings();

// Get saved settings.
$saved_widget_settings = swift_control_pro_get_saved_widget_settings();

// Pass saved settings & default settings to $GLOBALS so we can re-use it later.
$GLOBALS['swift_control_default_settings'] = $default_settings;
$GLOBALS['swift_control_widget_settings']  = $saved_widget_settings;
?>

<div class="swift-control-page widgets-tab">
	<div class="cols">
		<div class="col left-section">

			<div class="neatbox is-smooth has-bigger-heading active-items-box">
				<h2>
					<?php _e( 'Swift Control', 'swift-control' ); ?>
					<span class="saved-status">
						<?php _e( 'Updated', 'swift-control' ); ?> 🚀
					</span>
				</h2>
				<ul id="active-items" class="widget-items active-items">
					<?php
					foreach ( $active_widgets as $widget_key ) {
						swift_control_pro_settings_output_widget_item( $widget_key );
					}
					?>
				</ul>
			</div>

			<div class="neatbox add-widget-wrapper">
				<button type="button" class="add-widget">
					<i class="fas fa-plus"></i>
				</button>
			</div>

			<div class="general-settings-area">
				<?php
				require __DIR__ . '/color-settings.php';
				require __DIR__ . '/display-settings.php';
				require __DIR__ . '/misc-settings.php';
				?>
				<p class="submit">
					<button type="button" name="submit" id="submit" class="button button-primary save-general-settings" value="Save Changes">
						<?php _e( 'Save Changes', 'swift-control' ); ?>
					</button>
				</p>

				<div class="saved-status-bar"><?php _e( 'Your settings have been saved.', 'swift-control' ); ?></div>
			</div><!-- .general-settings-area -->

		</div><!-- .left-section -->

		<div class="col right-section">

			<div class="neatbox has-bigger-heading is-clean available-items-box">
				<h2>
					<?php _e( 'Available Widgets', 'swift-control' ); ?>
					<button type="button" class="button show-advanced-widgets">
						<?php _e( 'Advanced', 'swift-control' ); ?>
					</button>
				</h2>

				<?php
				/**
				 * We echo the `ul` opening and closing tag so when there's no `li`,
				 * We can get `ul` without whitespace inside so we can use :empty css selector.
				 */
				echo '<ul id="available-items" class="widget-items available-items">';

				foreach ( $available_widgets as $widget_key ) {
					swift_control_pro_settings_output_widget_item( $widget_key );
				}

				echo '</ul>';
				?>

			</div>

			<div class="export-import-area">
				<?php
				require __DIR__ . '/export-widgets.php';
				require __DIR__ . '/import-widgets.php';
				?>
			</div>

		</div><!-- .right-section -->
	</div><!-- .cols -->
</div><!-- .swift-control-page -->
