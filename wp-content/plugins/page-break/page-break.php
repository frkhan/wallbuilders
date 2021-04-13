<?php
/**
 * Plugin Name:       Page Break
 * Plugin URI:        http://espresonmedia.com/wordpress-plugin-page-break/
 * Description:       Adds Page Break Button to Wordpress editor For easy insertion of Page Break "<!--nextpage" Tag in your Blog Posts.
 * Version:           1.1.1
 * Author:            Espreson Media
 * Author URI:        http://espresonmedia.com/
 * License:           GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}

/** Loads the 'page-break' class file. */
require_once( dirname( __FILE__ ) . '/class-page-break.php' );

/**
 * Creates an instance of the 'page-break' class
 * and calls its initialization method.
 *
 * @since    1.0.0
 */
function page_break_run() {

    $page_break = new page_break();
    $page_break->init();

}
page_break_run();
