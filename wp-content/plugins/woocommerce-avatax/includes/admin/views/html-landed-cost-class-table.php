<?php
/**
 * WooCommerce AvaTax
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce AvaTax to newer
 * versions in the future. If you wish to customize WooCommerce AvaTax for your
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-avatax/
 *
 * @author    SkyVerge
 * @copyright Copyright (c) 2016-2020, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Display the Landed Cost code table.
 *
 * @type array $columns table columns, as `$slug => $heading`
 * @type array $hts_codes available stored HTS codes
 * @type array $classes stored country classes
 */
?>

<h2><?php esc_html_e( 'Country Classifications', 'woocommerce-avatax' ); ?></h2>

<p><?php esc_html_e( 'To increase the accuracy of calculated import duties, fees, and taxes, you can assign country-specific classifications to each of your Harmonized Tariff codes. If configured below, the correct classification code will be chosen at checkout based on the customer\'s shipping address.', 'woocommerce-avatax' ); ?></p>

<table class="wc-avatax-landed-cost-table widefat">

	<thead>
		<tr>
			<?php foreach ( $columns as $id => $heading ) : ?>
				<th class="wc-avatax-landed-cost-class-<?php echo sanitize_html_class( $id ); ?>"><?php echo esc_html( $heading ); ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>

	<tbody class="wc-avatax-landed-cost-class-rows">

		<?php foreach ( $hts_codes as $hts_code ) : ?>

			<tr>

				<?php foreach ( array_keys( $columns ) as $column ) : ?>

					<?php $edit_url = admin_url( "admin.php?page=wc-settings&tab=avatax-landed-cost&section=classes&hts_code={$hts_code}" ); ?>

					<td>

						<?php switch ( $column ) :

							// HTS code column
							case 'hts-code':
								echo '<a href="' . esc_url( $edit_url ) . '">' . esc_html( $hts_code ) . '</a>';
							break;

							case 'codes':
								echo ! empty( $classes[ $hts_code ] ) ? esc_html( implode( ', ', array_keys( $classes[ $hts_code ] ) ) ) : '<a href="' . esc_url( $edit_url ) . '">' . esc_html__( 'Configure &raquo;', 'woocommerce-avatax' ) . '</a>';
							break;

							case 'countries':

								$all_countries   = WC()->countries->get_countries();
								$class_countries = ! empty( $classes[ $hts_code ] ) ? array_values( $classes[ $hts_code ] ) : array();
								$country_names   = array();

								foreach ( $class_countries as $countries ) {

									foreach ( $countries as $country ) {

										if ( isset( $all_countries[ $country ] ) ) {
											$country_names[] = $all_countries[ $country ];
										}
									}
								}

								echo esc_html( implode( ', ', array_unique( $country_names ) ) );

							break;

							default:

								/**
								 * Fires for custom Landed Cost table columns.
								 *
								 * @since 1.5.0
								 *
								 * @param array $hts_codes available stored HTS codes
								 */
								do_action( "wc_avatax_landed_cost_class_table_row_{$column}", $hts_codes );

						endswitch; ?>

					</td>

				<?php endforeach; ?>

			</tr>

		<?php endforeach; ?>

	</tbody>

</table>
