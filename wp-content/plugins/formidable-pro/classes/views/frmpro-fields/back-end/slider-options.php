<p>
	<label for="unit_<?php echo esc_attr( $field['id'] ); ?>">
		<?php esc_html_e( 'Unit', 'formidable-pro' ); ?>
	</label>
	<input type="text" name="field_options[unit_<?php echo esc_attr( $field['id'] ); ?>]" id="unit_<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['unit'] ); ?>" data-changeme="range_unit_<?php echo esc_attr( $field['id'] ); ?>"/>
</p>
