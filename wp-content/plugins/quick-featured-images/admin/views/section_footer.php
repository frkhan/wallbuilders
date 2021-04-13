<?php
/**
 * Represents the footer for the admin page
 *
 * @package   Quick_Featured_Images
 * @author    Kybernetik Services <wordpress@kybernetik.com.de>
 * @license   GPL-2.0+
 * @link      https://www.kybernetik-services.com
 * @copyright 2013 Kybernetik Services
 */
// check if file is called in an object context
// else use non-object context
if ( isset($this->plugin_slug ) ) {
    $text_domain = $this->plugin_slug;
} else {
    $text_domain = self::$plugin_slug;
}
// get and set locale code for paypal button
// source: https://developer.paypal.com/docs/classic/archive/buttons/
// source: http://wpcentral.io/internationalization/
$paypal_locale = get_locale();
// if locale is not in registered locale code try to find the nearest match
if ( ! in_array( $paypal_locale, array( 'en_US', 'en_AU', 'es_ES', 'fr_FR', 'de_DE', 'ja_JP', 'it_IT', 'pt_PT', 'pt_BR', 'pl_PL', 'ru_RU', 'sv_SE', 'tr_TR', 'nl_NL', 'zh_CN', 'zh_HK', 'he_IL' ) ) ) {
    if ( 'ja' == $paypal_locale ) { // japanese language
        $paypal_locale = 'ja_JP';
    } else {
        $language_codes = explode( '_', $paypal_locale );
        // test the language
        switch ( $language_codes[ 0 ] ) {
            case 'en':
                $paypal_locale = 'en_US';
                break;
            case 'nl':
                $paypal_locale = 'nl_NL';
                break;
            case 'es':
                $paypal_locale = 'es_ES';
                break;
            case 'de':
                $paypal_locale = 'de_DE';
                break;
            default:
                $paypal_locale = 'en_US';
        } // switch()
    } // if ('ja')
} // if !in_array()

?>
			</div><!-- .qfi_content -->
		</div><!-- #qfi_main -->
		<div id="qfi_footer">
			<div class="qfi_content">
				<h2><?php esc_html_e( 'Credits and information', 'quick-featured-images' ); ?></h2>
				<dl>
					<dt><?php esc_html_e( 'Do you like the plugin?', 'quick-featured-images' ); ?></dt><dd><a href="http://wordpress.org/support/view/plugin-reviews/quick-featured-images" target="_blank"><?php esc_html_e( 'Please rate it at wordpress.org!', 'quick-featured-images' ); ?></a></dd>
                    <dt><?php esc_html_e( 'The plugin is for free. But the plugin author would be delighted to your small contribution.', 'quick-featured-images' ); ?></dt><dd><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=NSEQX73VHXKS8" target="_blank"><img src="https://www.paypalobjects.com/<?php echo $paypal_locale; ?>/i/btn/btn_donateCC_LG.gif" alt="(<?php esc_html_e( 'Donation Button', $text_domain ); ?>)" id="paypal_button" /><br /><?php esc_html_e( 'Donate with PayPal', 'quick-featured-images' ); ?></a><img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1" /></dd>
					<dt><?php esc_html_e( 'Do you need support or have an idea for the plugin?', 'quick-featured-images' ); ?></dt><dd><a href="http://wordpress.org/support/plugin/quick-featured-images" target="_blank"><?php esc_html_e( 'Post your questions and ideas about Quick Featured Images in the forum at wordpress.org!', 'quick-featured-images' ); ?></a></dd>
					<dt><?php esc_html_e( 'Let the thumbnails appear in the widget of the most recent posts with this fast plugin', 'quick-featured-images' ); ?></dt><dd><a href="http://wordpress.org/plugins/recent-posts-widget-with-thumbnails/" target="_blank"><?php printf( esc_html__( 'Download plugin %s at %s!', 'quick-featured-images' ), '<strong>Recent Posts Widget With Thumbnails</strong>', 'wordpress.org' ); ?></a></dd>
                    <dt><?php esc_html_e( 'Get the Pro version', 'quick-featured-images' ); ?> <a href="https://www.kybernetik-services.com/plugins/quick-featured-images-pro/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=quick-featured-images&utm_content=go_pro" target="_blank">Quick Featured Images Pro</a></dd>
				</dl>
			</div><!-- .qfi_content -->
		</div><!-- #qfi_footer -->
	</div><!-- .qfi_wrapper -->
</div><!-- .wrap -->
