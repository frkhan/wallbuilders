<?php
/**
 * Change widget order.
 *
 * @package Swift_Control
 */

namespace SwiftControlPro\Ajax;

/**
 * Class to manage ajax request of changing widget order.
 */
class Delete_Widget {
	/**
	 * Available fields.
	 *
	 * @var array
	 */
	private $fields = array( 'widget_key' );

	/**
	 * Sanitized data.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Setup the flow.
	 */
	public function ajax() {
		$this->sanitize();
		$this->validate();
		$this->delete();
	}

	/**
	 * Sanitize the data.
	 */
	public function sanitize() {
		foreach ( $this->fields as $field ) {
			$this->data[ $field ] = isset( $_POST[ $field ] ) ? sanitize_text_field( $_POST[ $field ] ) : '';
		}
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'delete_widget', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'swift-control' ) );
		}
	}

	/**
	 * Remove a widget key from the list of widget keys.
	 *
	 * @param string  $widget_key The widget key to be deleted.
	 * @param array   $widget_keys The list of widget keys.
	 * @param boolean $is_associative Whether the array is associative or not.
	 *
	 * @return array The new list of widget keys.
	 */
	public function remove_item( $widget_key, $widget_keys, $is_associative = false ) {
		if ( $is_associative ) {
			unset( $widget_keys[ $widget_key ] );
			return $widget_keys;
		} else {
			$target_index = absint( array_search( $widget_key, $widget_keys, true ) );

			unset( $widget_keys[ $target_index ] );

			return array_values( $widget_keys );
		}
	}

	/**
	 * Save the data.
	 */
	public function delete() {
		$widget_key     = $this->data['widget_key'];
		$active_widgets = swift_control_pro_get_active_widgets();

		if ( in_array( $widget_key, $active_widgets, true ) ) {
			$active_widgets = $this->remove_item( $widget_key, $active_widgets );

			update_option( 'swift_control_active_widgets', $active_widgets );
		}

		$saved_settings = swift_control_pro_get_saved_widget_settings();

		if ( isset( $saved_settings[ $widget_key ] ) ) {
			$saved_settings = $this->remove_item( $widget_key, $saved_settings, true );

			update_option( 'swift_control_widget_settings', $saved_settings );
		}

		if ( false !== stripos( $widget_key, 'custom_widget_' ) ) {
			$custom_widgets = swift_control_pro_get_custom_widgets();

			if ( in_array( $widget_key, $custom_widgets, true ) ) {
				$custom_widgets = $this->remove_item( $widget_key, $custom_widgets );

				update_option( 'swift_control_custom_widgets', $custom_widgets );
			}
		}

		wp_send_json_success( __( 'Widget is deleted' ), 'swift-control' );
	}
}
