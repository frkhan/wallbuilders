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
 * Display the Landed Cost class edit screen.
 *
 * @type string $hts_code HTS code currently being edited
 * @type array $columns table columns, as `$slug => $heading`
 * @type array $classes stored HTS code classes
 * @type array $products list of \WC_Product objects
 */
?>

<h2>
	<?php echo esc_html( sprintf( __( 'Editing HTS Code "%s"', 'woocommerce-avatax' ), $hts_code ) ); ?>

	<?php wc_back_link( __( 'Return to country classifications', 'woocommerce-avatax' ), admin_url( 'admin.php?page=wc-settings&tab=avatax-landed-cost&section=classes' ) ); ?>
</h2>

<div class="wc-avatax-landed-cost-edit">

	<div class="wc-avatax-landed-cost-edit-classes">

		<table class="wc-avatax-landed-cost-table widefat">

			<thead>
				<tr>
					<?php foreach ( $columns as $id => $heading ) : ?>
						<th class="wc-avatax-landed-cost-class-<?php echo sanitize_html_class( $id ); ?>"><?php echo esc_html( $heading ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<td colspan="<?php esc_attr_e( count( $columns ) ); ?>">
						<input type="submit" class="button wc-avatax-landed-cost-table-add-row" value="<?php esc_attr_e( 'Add Classification', 'woocommerce' ); ?>" />
					</td>
				</tr>
			</tfoot>

			<tbody class="wc-avatax-landed-cost-class-rows">

				<?php $key = 0; ?>

				<?php if ( ! empty( $classes ) ) : ?>

					<?php foreach ( $classes as $code => $countries ) : ?>

						<tr>

							<?php foreach ( array_keys( $columns ) as $column ) : ?>

								<td>

									<?php switch ( $column ) :

										case 'code':
											echo '<input class="wc-avatax-landed-cost-class-code" name="wc_avatax_landed_cost_classes[' . esc_attr( $key ) . '][code]" value="' . esc_attr( $code ) . '" type="text" />';
										break;

										case 'countries': ?>

											<select class="wc-avatax-landed-cost-class-countries wc-enhanced-select"  name="wc_avatax_landed_cost_classes[<?php esc_attr_e( $key ); ?>][countries][]" data-placeholder="<?php esc_attr_e( 'Choose countries&hellip;', 'woocommerce-avatax' ); ?>" multiple="multiple">
												<?php foreach ( WC()->countries->get_allowed_countries() as $country => $name ) : ?>
													<option value="<?php esc_attr_e( $country ); ?>" <?php selected( in_array( $country, $countries ), true ); ?>><?php esc_html_e( $name ); ?></option>
												<?php endforeach; ?>
											</select>

										<?php break;

										case 'remove':
											echo '<a href="#" class="wc-avatax-landed-cost-table-remove-row">' . esc_html__( 'Remove', 'woocommerce-avatax' ) . '</a>';
										break;

										default:
											do_action( "wc_avatax_landed_cost_class_table_row_{$column}", $code, $countries );

									endswitch; ?>

								</td>

							<?php endforeach; ?>

						</tr>

						<?php $key++; ?>

					<?php endforeach; ?>

				<?php else : ?>

					<tr>

						<?php foreach ( array_keys( $columns ) as $column ) : ?>

							<td>

								<?php switch ( $column ) :

									case 'code':
										echo '<input class="wc-avatax-landed-cost-class-code" name="wc_avatax_landed_cost_classes[' . esc_attr( $key ) . '][code]" value="" type="text" />';
									break;

									case 'countries': ?>

										<select class="wc-avatax-landed-cost-class-countries wc-enhanced-select"  name="wc_avatax_landed_cost_classes[<?php esc_attr_e( $key ); ?>][countries][]" data-placeholder="<?php esc_attr_e( 'Choose countries&hellip;', 'woocommerce-avatax' ); ?>" multiple="multiple">
											<?php foreach ( WC()->countries->get_allowed_countries() as $country => $name ) : ?>
												<option value="<?php esc_attr_e( $country ); ?>"><?php esc_html_e( $name ); ?></option>
											<?php endforeach; ?>
										</select>

									<?php break;

									default:
										do_action( "wc_avatax_landed_cost_class_table_row_{$column}" );

								endswitch; ?>

							</td>

						<?php endforeach; ?>

					</tr>

				<?php endif; ?>

			</tbody>

		</table>

	</div>

	<div class="wc-avatax-landed-cost-edit-products">

		<h3><?php esc_html_e( 'Products', 'woocommerce-avatax' ); ?> <a href="<?php echo esc_url( admin_url( "edit.php?post_type=product&wc_avatax_hts_code={$hts_code}" ) ); ?>"><?php esc_html_e( 'Edit', 'woocommerce-avatax' ); ?></a></h3>

		<?php if ( ! empty( $products ) ) : ?>

			<ul>
				<?php foreach ( $products as $product ) : ?>
					<li><?php esc_html_e( $product->get_title() ); ?></li>
				<?php endforeach; ?>
			</ul>

		<?php else : ?>

			<p><?php esc_html_e( 'No products associated with this HTS code.', 'woocommerce-avatax' ); ?></p>

		<?php endif; ?>

	</div>

</div>
