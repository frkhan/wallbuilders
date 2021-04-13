<?php

/* Extensions data */
add_action( 'admin_init', 'rfbwp_ext_data' );
function rfbwp_ext_data() {	
	$ext_data = get_transient( 'rfbwp_ext_data' );
	$old_changes = get_transient( 'rfbwp_ext_changes' );
	
	if( !$ext_data ) {	
		$new_ext_data = rfbwp_get_ext_data();

		if( $new_ext_data !== false ) {			
			set_transient( 'rfbwp_ext_data', $new_ext_data, 24 * 30 * HOUR_IN_SECONDS );	

			// check for changes in version/plugins
			$changes = array( 'new' => array(), 'updated' => array() );
			foreach( $new_ext_data as $new_extension ) {
				if( $new_extension[ 'badge' ] == 'new' )			
					$changes[ 'new' ][] = '<strong>' . $new_extension[ 'name' ] . '</strong>';
				else if( $new_extension[ 'badge' ] == 'updated' ) {
					if( is_array( $ext_data ) ) {
						foreach( $ext_data as $extension ) {
							if( ( $extension[ 'name' ] == $new_extension[ 'name' ] ) && $extension[ 'version' ] < $new_extension[ 'version' ] ) 
								$changes[ 'updated' ][] = '<strong>' .$new_extension[ 'name' ] . ' v.' . $new_extension[ 'version' ] . '</strong>';		
						}
					} else 
						$changes[ 'updated' ][] = '<strong>' .$new_extension[ 'name' ] . ' v.' . $new_extension[ 'version' ] . '</strong>';					
				}
			}
		 
			if( $old_changes !== $changes ) {
				$old_changes = $changes;
				
				delete_option( 'rfbwp_ext_newest_notice' );
			}
			
			set_transient( 'rfbwp_ext_changes', $old_changes, 24 * 30 * HOUR_IN_SECONDS );
		}
	}

}
function rfbwp_get_ext_data() {	
	$protocol = is_ssl() ? 'https' : 'http';
	
	$url = $protocol . '://wizard.mpcreation.net/api/fb_extensions.json';
		
	if( ini_get( 'allow_url_fopen' ) )
		return json_decode( file_get_contents( $url ), true );
	
	return false;
}

/* Notices */
//add_action( 'admin_notices', 'rfbwp_ext_notice' );
function rfbwp_ext_notice() {
	$dismiss = get_option( 'rfbwp_ext_notice' );
	
	if( $dismiss )
		return;
	
	?>
	<div class="rfbwp-notice rfbwp-ext-notice">
		<strong><?php _e( 'Did you know?', 'rfbwp' ); ?></strong> 
			<?php _e( 'Responsive Flipbook plugin has support for extensions like <strong>PDF Wizard</strong> or <strong>Print</strong>. Click <a href="admin.php?page=mp-settings-ext">here </a> for more information!', 'rfbwp' ); ?>

		<a href="#" class="rfbwp-notice-dismiss" data-notice="rfbwp_ext_notice"><i class="dashicons dashicons-no-alt"></i></a>	
	</div>
	<?php
}
		
//add_action( 'admin_notices', 'rfbwp_ext_newest_notice' );
function rfbwp_ext_newest_notice() {
	$dismiss = get_option( 'rfbwp_ext_newest_notice' );
	
	if( $dismiss )
		return;
	
	$changes = get_transient( 'rfbwp_ext_changes' );

	if( is_array( $changes ) && empty( $changes[ 'new' ] ) && empty( $changes[ 'updated'] ) )
		return;
	
	$new_ext = !empty( $changes[ 'new' ] ) ? implode( ', ', $changes[ 'new' ] ) : false; 
	$upd_ext = !empty( $changes[ 'updated' ] ) ? implode( ', ', $changes[ 'updated' ] ) : false; 

	if( $new_ext || $upd_ext ) {
		?>
		<div class="rfbwp-notice rfbwp-notice-info rfbwp-ext-notice">
			<strong><?php _e( 'New stuff: ', 'rfbwp' ); ?></strong> 
				<?php if( $upd_ext ) echo __( 'There is a new update for: ', 'rfbwp' ) . $upd_ext . '. '; ?> 
				<?php if( $new_ext ) echo __( 'New extensions are available for purchase: ', 'rfbwp' ) . $new_ext . '. '; ?>
				<?php _e( 'Click <a href="admin.php?page=mp-settings-ext">here</a> for more information!', 'rfbwp' ); ?>

			<a href="#" class="rfbwp-notice-dismiss" data-notice="rfbwp_ext_newest_notice"><i class="dashicons dashicons-no-alt"></i></a>	
		</div>
		<?php
	}
}

/* Menu Page */
add_action( 'rfbwp/panelSubmenu', 'rfbwp_ext_menu' );
function rfbwp_ext_menu() {
	$hook = add_submenu_page( MP_PAGE_BASENAME, __('Responsive Flipbook Extensions', 'rfbwp'), __( 'Extensions', 'rfbwp' ), 'rfbwp_plugin_cap', 'mp-settings-ext', 'rfbwp_ext_page' );

	add_action( 'load-' . $hook, 'rfbwp_ext_scripts' );
}

/* Scripts */
function rfbwp_ext_scripts() {
	wp_enqueue_style( 'rfbwp-ext-css', MPC_PLUGIN_ROOT . '/massive-panel/css/extensions.css' );
}

function rfbwp_ext_page() {
	$ext_data = get_transient( 'rfbwp_ext_data' );
	
	ob_start();
	?>
	<div class="wrap">
		<h2><?php echo esc_html( get_admin_page_title() ); ?></h2> 
		<div id="rfbwp_ext" class="rfbwp-ext-wrap">
			<?php if( isset( $ext_data ) && !empty( $ext_data ) ) : ?>
				<?php foreach( $ext_data as $extension ) : ?>
					<div class="rfbwp-ext-item<?php if( isset( $extension[ 'coming' ] ) && $extension[ 'coming' ] ) echo ' rfbwp-item-coming'; ?>">
						<?php if( isset( $extension[ 'badge' ] ) && !empty( $extension[ 'badge' ] ) ): ?>
							<span class="rfbwp-item-badge rfbwp-badge-<?php echo esc_attr( $extension[ 'badge' ] ); ?>"><?php echo $extension[ 'badge' ]; ?></span>
						<?php endif; ?>
						<a href="<?php echo esc_url( $extension[ 'purchase'] ); ?>">
							<img src="<?php echo esc_url( $extension[ 'preview_image' ] ); ?>" alt="" />
						</a>
						<div class="rfbwp-item-details">
							<span class="rfbwp-item-title">
								<?php echo $extension[ 'name' ]; ?> 
								<?php if( isset( $extension[ 'version' ] ) && !empty( $extension[ 'version' ] ) ): ?><em>v. <?php echo $extension[ 'version' ]; ?></em><?php endif; ?>
							</span>
							
							<?php if( !isset( $extension[ 'coming' ]) || !$extension[ 'coming' ] ): ?>							
							<span class="rfbwp-item-actions">
								<a href="<?php echo esc_url( $extension[ 'purchase'] ); ?>" class="rfbwp-item-button rfbwp-item-purchase"><i class="dashicons dashicons-cart"></i> Purchase</a>
								<a href="<?php echo esc_url( $extension[ 'live_preview'] ); ?>" class="rfbwp-item-button rfbwp-item-preview"><i class="dashicons dashicons-visibility"></i> Live Preview</a>
							</span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>			
		</div>
	</div>
	<?php
	return ob_end_flush();
}