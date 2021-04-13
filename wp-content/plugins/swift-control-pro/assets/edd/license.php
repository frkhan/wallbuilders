<?php
/**
 * License
 *
 * @package Swift_Control
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function swift_control_register_option() {
	// creates our settings in the options table
	register_setting( 'swift_control_license', 'swift_control_license_key', 'swift_control_sanitize_license' );
}
add_action('admin_init', 'swift_control_register_option');

function swift_control_sanitize_license( $new ) {
	$old = get_option( 'swift_control_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'swift_control_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

// License Activation
function swift_control_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['swift_control_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'swift_control_nonce', 'swift_control_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'swift_control_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( SWIFT_CONTROL_PRO_PLUGIN_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( SWIFT_CONTROL_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled' :
					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), SWIFT_CONTROL_PRO_PLUGIN_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'options-general.php?page=' . SWIFT_CONTROL_PRO_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"
		update_option( 'swift_control_site_url', $_SERVER['SERVER_NAME'] );
		update_option( 'swift_control_license_status', $license_data->license );
		wp_redirect( admin_url( 'options-general.php?page=' . SWIFT_CONTROL_PRO_LICENSE_PAGE ) );
		exit();
	}
}
add_action('admin_init', 'swift_control_activate_license');

// License Deactivation
function swift_control_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['swift_control_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'swift_control_nonce', 'swift_control_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'swift_control_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( SWIFT_CONTROL_PRO_PLUGIN_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( SWIFT_CONTROL_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

			$base_url = admin_url( 'options-general.php?page=' . SWIFT_CONTROL_PRO_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'swift_control_license_status' );
		}

		wp_redirect( admin_url( 'options-general.php?page=' . SWIFT_CONTROL_PRO_LICENSE_PAGE ) );
		exit();

	}
}
add_action('admin_init', 'swift_control_deactivate_license');

// display messages to the customer
function swift_control_license_notices() {
	
	$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) && strpos( $current_url, SWIFT_CONTROL_PRO_LICENSE_PAGE ) !== false ) {

		switch( $_GET['sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}
add_action( 'admin_notices', 'swift_control_license_notices' );
