<?php
/**
 * Swift Control custom widget template.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$roles_obj = new \WP_Roles();
$roles     = $roles_obj->role_names;

ob_start();
?>

<li class="widget-item" data-widget-key="new_custom_widget">
	<div class="cols widget-default-area">
		<div class="col widget-item-col drag-wrapper">
			&nbsp;
			<span class="drag-handle"></span>
		</div>
		<div class="col widget-item-col icon-wrapper">
			<div class="widget-icon dblclick-trigger">
				<i class="fas fa-cog"></i>
				<button type="button" class="icon-picker blur-trigger"></button>
			</div>
		</div>
		<div class="col widget-item-col text-wrapper">
			<input type="text" name="swift_control_new_custom_widget_text" class="text-field widget-text-field dblclick-trigger" value="<?php _e( 'Custom Widget', 'swift-control' ); ?>" readonly>
		</div>
		<div class="col widget-item-col extra-settings-wrapper">

			<div class="widget-item-control edit-mode-control widget-url-setting">
				<input type="url" name="swift_control_new_custom_widget_url" class="text-field widget-url-field" placeholder="<?php _e( 'Widget URL', 'swift-control' ); ?>">
			</div>

			<div class="widget-item-control edit-mode-control new-tab-setting">
				<label for="swift_control_new_custom_widget_new_tab" class="label checkbox-label blur-trigger">
					<?php _e( 'New tab', 'swift-control' ); ?>
					<input type="checkbox" name="swift_control_new_custom_widget_new_tab" id="swift_control_new_custom_widget_new_tab" value="1" class="new-tab-field">
					<div class="indicator"></div>
				</label>
			</div>

		</div>
		<div class="col widget-item-col actions-wrapper">
			<button type="button" class="widget-item-control edit-button">
				<?php _e( 'Edit', 'swift-control' ); ?>
			</button>

			<button class="delete-button delete-widget">
				<i class="fas fa-times"></i>
			</button>
		</div>
	</div><!-- .widget-default-area -->

	<div class="widget-advanced-area">
		<div class="setting-fields is-gapless">
			<div class="field">
				<label for="swift_control_new_custom_widget_roles" class="label widget-roles-label select2-label">
					<p>
						<?php _e( 'Show this widget for:', 'swift-control' ); ?>
					</p>
					<select name="swift_control_new_custom_widget_roles[]" id="swift_control_new_custom_widget_roles" class="is-fullwidth widget-roles-field" multiple>
						<option value="all" <?php echo esc_attr( in_array( 'all', $roles, true ) ? 'selected' : '' ); ?>>
							<?php _e( 'All', 'swift-control' ); ?>
						</option>

						<?php if ( swift_control_pro_is_network_active() ) : ?>
							<option value="super_admin" selected>
								<?php _e( 'Super Admin', 'swift-control' ); ?>
							</option>
						<?php endif; ?>

						<?php foreach ( $roles as $role_key => $role_name ) : ?>
							<?php
							$selected_attr = '';

							if ( 'administrator' === $role_key || 'editor' === $role_key ) {
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
</li>

<?php
return ob_get_clean();
