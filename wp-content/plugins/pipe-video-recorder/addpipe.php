<?php
/*
 * Plugin Name: Pipe Video Recorder
 * Plugin URI: https://addpipe.com
 * Description: Pipe Video Recorder allows you and your website members & visitors  to record video messages, interviews or leave video feedback. Pipe takes care of uploading, converting, publishing and storage of your videos.
 * Author: addpipe.com
 * Author URI: https://addpipe.com
 * Developer: Lucian Alexandru
 * Developer URI: http://plainsight.ro
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Text Domain: addpipe
 * Domain Path: /language
 * Network: false
 * Slug: addpipe
 * Version: 1.5.5
 */

/*
 * Load the main class
 */
require_once 'load/AddPipe.php';

/*
 * Register the install / uninstall hooks
 */
register_activation_hook(__FILE__, array('AddPipe', 'install'));
register_uninstall_hook(__FILE__, array('AddPipe', 'uninstall'));

/*
 * Run the plugin
 */
$addpipe = new AddPipe();
$addpipe->run();
