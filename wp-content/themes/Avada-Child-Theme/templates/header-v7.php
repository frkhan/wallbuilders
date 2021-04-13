<?php
/**
 * Header-v7 template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="fusion-header-sticky-height"></div>
<div class="fusion-header" >
	<div class="fusion-row fusion-middle-logo-menu">
		<?php if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
			<div class="fusion-header-has-flyout-menu-content">
		<?php endif; ?>
		<?php avada_main_menu(); ?>
		<?php avada_mobile_menu_search(); ?>
		<?php if ( 'flyout' === Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
  global $post;
  if( $post->post_parent == 10119 || $post->ID == 10119 ) :
  // For displaying CHW homepage navigation across children pages
?>
<div id="chw-home-link" style="
    text-align: center;
    font-family: &quot;Roboto Condensed&quot;;
    font-size: 16px;
    font-weight: 400;
    font-style: normal;
    background: #eee;
"><a href="<?php echo get_permalink( $post->post_parent ); ?>" style="
    display: block;
    padding: 8px 0 6px;
    color: inherit;
"><i class="fa fa-home" aria-hidden="true"></i> HOME — Christian Heritage Week</a></div>
<?php endif; // display CHW homepage navigation ?>