<?php
/**
 * Swift Control page template.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

?>

<form method="post" action="options.php">

<div class="neatbox has-bigger-heading is-smooth">

	<?php
	$license = get_option( 'swift_control_license_key' );
	$status  = get_option( 'swift_control_license_status' );

	settings_fields( 'swift_control_license' );
	?>

	<h2>
		<?php _e( 'Status', 'swift-control' ); ?>:
		<?php if ( swift_control_pro_license_key_mismatch() ) { ?>
			<span style="color: tomato; font-weight: 700; font-style: italic;"><?php _e( 'Mismatch!', 'swift-control' ); ?></span>
		<?php } elseif ( $status !== false && $status === 'valid' ) { ?>
			<span style="color:#6dbb7a; font-weight: 700; font-style: italic;"><?php _e( 'Active', 'swift-control' ); ?></span>
		<?php } else { ?>
			<span style="color: tomato; font-weight: 700; font-style: italic;"><?php _e( 'Inactive', 'swift-control' ); ?></span>
		<?php } ?>
	</h2>

	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<?php _e( 'License Key', 'swift-control' ); ?>
				</th>
				<td>
					<input id="swift_control_license_key" name="swift_control_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
					<p class="description" for="swift_control_license_key"><?php _e( 'Enter your Premium Add-On license key.', 'swift-control' ); ?></p>
				</td>
			</tr>
			<?php if ( false !== $license && '' !== $license ) { ?>
			<tr>
				<th>
					<?php _e( 'Activate License', 'swift-control' ); ?>
					<!-- <a href="https://wpswiftcontrol.com/docs-category/installation/" target="_blank" class="dashicons dashicons-editor-help"></a> -->
				</th>
				<td>
					<?php if ( $status !== false && $status === 'valid' ) { ?>
						<?php wp_nonce_field( 'swift_control_nonce', 'swift_control_nonce' ); ?>
						<input type="submit" class="button-primary" name="swift_control_license_activate" value="<?php _e( 'Revalidate', 'swift-control' ); ?>"/>
						<input type="submit" class="button-secondary" name="swift_control_license_deactivate" value="<?php _e( 'Deactivate License', 'swift-control' ); ?>"/>
					<?php } else { ?>
						<?php wp_nonce_field( 'swift_control_nonce', 'swift_control_nonce' ); ?>
						<input type="submit" class="button-secondary" name="swift_control_license_activate" value="<?php _e( 'Activate License', 'swift-control' ); ?>"/>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<?php submit_button(); ?>

	</div>
</form>
