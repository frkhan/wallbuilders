<?php
/**
 * Skyrocket Customizer Custom Controls
 *
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class SMA_Customize_Heading_Control extends WP_Customize_Control {		

		public function render_content() {
			?>
			<label>
				<h3 class="control_heading"><?php _e( $this->label, 'advanced-local-pickup-for-woocommerce' ); ?></h3>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
			</label>
			<?php
		}
	}
}
