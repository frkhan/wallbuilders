<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}

class AddPipe {

    /**
     * Account hash holder
     * @var string
     */
    public $accountHash = '';

    /**
     * Video length holder
     * @var int
     */
    public $videoLength = 20;

    /**
     * Video quality holder
     * @var string
     */
    public $videoQuality = '480p';

    public $videoWidth = 640;
    public $videoHeight = 510;

    /**
     * Plugin instance
     * @var
     */
    private static $_instance;

    /**
     * Plugin screen name
     * @var string
     */
    public $_pluginScreenName = 'AddPipe';

    /**
     * L'Constructeur
     */
    public function __construct() {

        // Get the user's account hash from the database
        $this->accountHash = $this->getAccountHash();

        // Retrieve the video length from the database
        $this->videoLength = $this->getVideoLength();

        // Retrieve the video quality from the database
        $this->videoQuality = $this->getVideoQuality();

        // Based on video quality, setup the object's videoWidth and videoHeight
        $this->setVideoWidthAndHeight($this->videoQuality);
    }

    /**
     * Hook our methods into WordPress
     */
    public function run() {

        /* Draw the menu */
        add_action('admin_menu', array($this, 'drawMenu'));

        /* AJAX: save settings */
        add_action('wp_ajax_addpipe_ajax_save_settings', array($this, 'addpipe_ajax_save_settings'));
        /* AJAX: Delete video - unavailable [TODO] */
        add_action('wp_ajax_addpipe_ajax_delete_video', array($this, 'addpipe_ajax_delete_video'));
        /* AJAX: Generate a shortcode for the video recorder */
        add_action('wp_ajax_addpipe_ajax_shortcode_generator', array($this, 'addpipe_ajax_shortcode_generator'));
        /* AJAX: sync deleted videos */
        add_action('wp_ajax_addpipe_ajax_sync_deleted', array($this, 'addpipe_ajax_sync_deleted'));

        /* Listen to incoming hooks from addpipe.com */
        add_action('init', array($this, 'addpipeWebhook'));
        /* The [pipe_recorder] shortcode */
        add_shortcode('pipe_recorder', array($this, 'newRecorder'));
        /* The [pipe_playback] */
        add_shortcode('pipe_playback', array($this, 'newPlayback'));

        // Unused for now
        // Enqueue scripts (css and js) in the admin area - only on our plugin pages
        add_action('admin_enqueue_scripts', array($this, 'enqueueBackendScripts'));
        // Enqueue scripts (css and js) in the frontend area
        add_action('wp_enqueue_scripts', array($this, 'enqueueFrontendScripts'));
    }

    public function enqueueBackendScripts() {
//        wp_enqueue_style('dashicons');
//        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-ui-core');
    }

    public function enqueueFrontendScripts() {
//        wp_enqueue_style('dashicons');
//        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-ui-core');
    }


    /**
     * Install method
     */
    static function install() {
        // General settings
        update_option('AddPipe' . 'AccountHash', '');
        update_option('AddPipe' . 'VideoLength', '20');
        update_option('AddPipe' . 'VideoQuality', '480p');

        // Install the custom tables
        global $wpdb;
        $wpdb->query("
                      CREATE TABLE IF NOT EXISTS
                      `{$wpdb->prefix}addpipe_records`
                        (
                        `internal_id` INT(6) NOT NULL AUTO_INCREMENT,
                        `user_id` INT(11),
                        `video_id` INT(11),
                        `video_length` INT(11),
                        `video_size` INT(11),
                        `video_url` VARCHAR(255),
                        `video_img_url` VARCHAR(255),
                        `post_id` INT(11) DEFAULT 0,
                        `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `json` varchar(3000),
                        `active` SMALLINT(1) DEFAULT 1,
                        `views` INT(11) DEFAULT 0,
                        PRIMARY KEY (`internal_id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
        $wpdb->query("
                      CREATE TABLE IF NOT EXISTS
                      `{$wpdb->prefix}addpipe_shortcodes`
                        (
                        `shortcode_id` INT(6) NOT NULL AUTO_INCREMENT,
                        `video_length` INT(11),
                        `video_quality` VARCHAR(5),
                        `post_id` INT(11) DEFAULT 0,
                        `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `active` SMALLINT(1) DEFAULT 1,
                        `views` INT(11) DEFAULT 0,
                        PRIMARY KEY (`shortcode_id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");


        //Add the pipe_access_plugin capability to administrator role
        $role = get_role('administrator');
 
        //add the umbrella capability
        $role->add_cap('pipe_access_plugin', true);

        //add individual capabilities for each fo the 4 menus of the plugin
        $role->add_cap('pipe_access_record', true);
        $role->add_cap('pipe_access_embed', true);
        $role->add_cap('pipe_access_recordings', true);
        $role->add_cap('pipe_access_setup', true);

        // Log any error to file
        file_put_contents(dirname(__FILE__) . '/_plugin_error_activation.log', ob_get_contents(), FILE_APPEND);
    }

    /**
     * Uninstall plugin method
     */
    static function uninstall() {

            // Delete the general settings
            delete_option('AddPipe' . 'AccountHash');
            delete_option('AddPipe' . 'VideoLength');
            delete_option('AddPipe' . 'VideoQuality');

            //remove the capabilities added to the administrator
            $role = get_role('administrator');
            $role->remove_cap('pipe_access_plugin');
            $role->remove_cap('pipe_access_record');
            $role->remove_cap('pipe_access_embed');
            $role->remove_cap('pipe_access_recordings');
            $role->remove_cap('pipe_access_setup');

            // Delete custom tables
            global $wpdb;
            $wpdb->query("DROP TABLE `{$wpdb->prefix}addpipe_records`");
            $wpdb->query("DROP TABLE `{$wpdb->prefix}addpipe_shortcodes`");
    }

    /**
     * Returns the post/page ID, whether it's inside The Loop (or outside)
     * @return false|int
     */
    public function getThePostId() {

        global $wpdb, $post, $wp_query;

        if (in_the_loop()) {
            return get_the_ID();
        } else {
            return $wp_query->get_queried_object_id();
        }

    }

    /**
     * Returns the AddPipe instance
     * @return AddPipe
     */
    static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Class name
     * @return string
     */
    public function __toString() {
        return 'AddPipe';
    }

    /**
     * Returns the plugin folder path
     * @return string
     */
    public function getPluginFolder() {
        return dirname(dirname(__FILE__));
    }

    /**
     * Returns the plugin web address (URL)
     * @return string
     */
    public function getPluginUrl() {
        return plugins_url() . '/pipe-video-recorder/';
    }

    /**
     * Returns the plugin's server path, eg /srv/users/serverpilot/apps/dev/public/wordpress/wp-content/plugins/pipe-video-recorder/
     * @return string
     */
    public function getPluginPath() {
        return plugin_dir_path(__FILE__);
    }

    /**
     * Return the URL to the static folder (css, js, img)
     * @param $staticFileOrFolder
     * @return string
     */
    public function getPluginViewURL($staticFileOrFolder) {
        return $this->getPluginUrl() . 'static/' . $staticFileOrFolder;
    }

    /**
     * Returns the path to the Views folder
     * @return string
     */
    public function getViewsPath() {
        return $this->getPluginPath() . 'views/';
    }

    /**
     * Loads the appropriate controller based on URL
     * @return RecordedVideos|RecordNew|Settings
     * @throws Exception
     */
    public function loadController() {
        if (isset($_GET) && !empty($_GET['page'])) {
            switch ($_GET['page']) :
                case 'addpipe-for-wordpress/load/settings':
                    require_once $this->getPluginPath() . 'Settings.php';
                    return new Settings();
                    break;
                case 'addpipe-for-wordpress/load/recorded-videos':
                    require_once $this->getPluginPath() . 'RecordedVideos.php';
                    return new RecordedVideos();
                    break;
                case 'addpipe-for-wordpress':
                    require_once $this->getPluginPath() . 'RecordNew.php';
                    return new RecordNew();
                    break;
                case 'addpipe-for-wordpress/load/embed-recorder':
                    require_once $this->getPluginPath() . 'EmbedRecorder.php';
                    return new EmbedRecorder();
                    break;
                case 'addpipe-for-wordpress/load/get-trial':
                    require_once $this->getPluginPath() . 'GetTrial.php';
                    return new GetInvited();
                    break;
                default:
                    include_once $this->getPluginPath() . '../views/not_found.php';
                    throw new Exception('Not found. #98645312');
                endswitch;
        }
    }

    /**
     * "Draws" the plugin menu
     * @return string
     */
    public function drawMenu() {
        if (current_user_can('pipe_access_plugin')) {
            $this->_pluginScreenName = add_menu_page(
                // $page_title - The text to be displayed in the title tags of the page when the menu is selected
                'Pipe Video Recorder',
                // $menu_title - The on-screen name text for the menu
                'Pipe Video Recorder',
                // $capability - The capability required for this menu to be displayed to the user
                'pipe_access_plugin',
                // $menu_slug - The slug name to refer to this menu by (should be unique for this menu). Prior to Version 3.0 this was called the file (or handle) parameter.
                // If the function parameter is omitted, the menu_slug should be the PHP file that handles the display of the menu page content.
                'addpipe-for-wordpress',
                // function - (optional) The function that displays the page content for the menu page.
                // Default: None. Technically, the function parameter is optional, but if it is not supplied, then WordPress will assume that including the PHP file will generate the administration screen,
                // without calling a function. Most plugin authors choose to put the page-generating code in a function within their main plugin file. In the event that the function parameter is specified,
                // it is possible to use any string for the menu_slug parameter. This allows usage of pages such as ?page=my_super_plugin_page instead of ?page=my-super-plugin/admin-options.php.
                array($this, 'loadController'),
                // $icon_url
                plugins_url('/pipe-video-recorder/static/img/logo.png'),
                // position
                81
            );

            // add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
            add_submenu_page('addpipe-for-wordpress', 'Pipe Video Recorder', 'Record Video',         'pipe_access_record', 'addpipe-for-wordpress',        array($this, 'loadController'));
            add_submenu_page('addpipe-for-wordpress', 'Pipe Video Recorder', 'Embed Video Recorder', 'pipe_access_embed', 'addpipe-for-wordpress/load/embed-recorder',   array($this, 'loadController'));
            add_submenu_page('addpipe-for-wordpress', 'Pipe Video Recorder', 'Recorded Videos',      'pipe_access_recordings', 'addpipe-for-wordpress/load/recorded-videos',  array($this, 'loadController'));
            add_submenu_page('addpipe-for-wordpress', 'Pipe Video Recorder', 'Set up',             'pipe_access_setup', 'addpipe-for-wordpress/load/settings',                       array($this, 'loadController'));

            // If there is no account hash, probably the user doesn't have an addpipe.com account
//             if (empty($this->accountHash)) {
//                 add_submenu_page('addpipe-for-wordpress', 'Pipe Video Recorder', 'Get Trial', 'manage_options', 'addpipe-for-wordpress/load/get-trial', array($this, 'loadController'));
//             }
        }

        // Log any error to file
        file_put_contents(dirname(__FILE__) . '/_plugin_error_menu_draw.log', ob_get_contents(), FILE_APPEND);
    }

    /**
     * Listen to any webhooks from AddPipe.com
     */
    public function addpipeWebhook() {
        if (isset($_POST['payload']) && !empty($_POST['payload'])) {

            global $wpdb;

            /* Process the request */
            $json = (isset($_POST['payload'])) ? $_POST['payload'] : die('Missing payload.');
            $json = str_replace('\r', '', $json);
            $json = str_replace('\n', '', $json);
            $json = str_replace("\\", '', $json);
            $json = json_decode($json);

			if($json->event == "video_transcoded" || $json->event == "video_converted" || $json->event == "video_copied_pipe_s3") {
                //video_transcoed has been deprectated and some of it's functions have been replaced by video_converted & video_copied_pipe_s3 https://addpipe.com/blog/changes-to-our-webhooks/

				/* Slice the custom values from the payload */
				$payload = explode(',', $json->data->payload);
				$accountHash = filter_var($payload[0], FILTER_SANITIZE_STRING);
				$userId = filter_var($payload[1], FILTER_SANITIZE_NUMBER_INT);
				$postId = filter_var($payload[2], FILTER_SANITIZE_NUMBER_INT);

				/* Build the Insert query */
				$jsonData = json_encode($json); // Encode the Object back to json

                if($json->event == "video_transcoded"){
				    $queryInsert = "
						INSERT INTO `{$wpdb->prefix}addpipe_records`
						(
							`user_id`,
							`video_id`,
							`video_size`,
							`video_length`,
							`video_url`,
							`video_img_url`,
							`post_id`,
							`json`
						)
						VALUES
						(
							'{$userId}', /* user_id from Payload */
							'{$json->data->id}', /* video_id */
							'{$json->data->size}', /* video_size */
							'{$json->data->duration}', /* video_length */
							'{$json->data->url}', /* video_url */
							'{$json->data->snapshotUrl}', /* video_img_url */
							'{$postId}', /* post_id */
							'{$jsonData}'
						);
						";
                } else if ($json->event == "video_converted"){
                    $queryInsert = "
                        INSERT INTO `{$wpdb->prefix}addpipe_records`
                        (
                            `user_id`,
                            `video_id`,
                            `video_size`,
                            `video_length`,
                            `post_id`,
                            `json`
                        )
                        VALUES
                        (
                            '{$userId}', /* user_id from Payload */
                            '{$json->data->id}', /* video_id */
                            '{$json->data->size}', /* video_size */
                            '{$json->data->duration}', /* video_length */
                            '{$postId}', /* post_id */
                            '{$jsonData}'
                        );
                        ";

                } else if ($json->event == "video_copied_pipe_s3"){
                    $queryInsert = "
                        UPDATE `{$wpdb->prefix}addpipe_records`
                        SET  `video_url`= '{$json->data->url}', `video_img_url`= '{$json->data->snapshotUrl}'
                        WHERE `video_id`= '{$json->data->id}';
                        ";
                }

				/* Check the contents and the account hash */
				if ($this->accountHash == $accountHash) {

					// insert/update db ; give something back and die
					$wpdb->query($queryInsert);
					http_response_code(200);
					$_POST['payload'] .= "------------- 200 OK! -------------" . PHP_EOL;
					file_put_contents(dirname(__FILE__) . '/_addpipe_payloads.log', print_r($_POST['payload'], true) . PHP_EOL, LOCK_EX | FILE_APPEND);
					die('200 - OK.');

				} else {

					// The Payload isn't correct
					http_response_code(417);
					$_POST['payload'] .= "------------- Error 417 -------------" . PHP_EOL;
					file_put_contents(dirname(__FILE__) . '/_addpipe_errors.log', print_r($_POST['payload'], true) . PHP_EOL, LOCK_EX | FILE_APPEND);
					die('417 - Expectation Failed. The Payload is not correct.');

				}

				// Default: $_POST['payload'] exists, but could not be proccessed
				http_response_code(501);
				$_POST['payload'] .= "------------- Unknown error -------------" . PHP_EOL;
				file_put_contents(dirname(__FILE__) . '/_addpipe_errors.log', print_r($_POST['payload'], true) . PHP_EOL, LOCK_EX | FILE_APPEND);
				die('501 - Not implemented. I cannot process the payload.');
			}
        }
    }

    /**
     * Translates a given SIZE in a human readable format
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    public function filesizeForHumans($bytes, $decimals = 2) {
        $size = array(' B', ' KB', ' MB', ' GB', ' TB',' PB', ' EB', ' ZB', ' YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /**
     * Selects from DB only active videos
     * @param int $limit
     * @param int $offset
     * @return array|null|object
     */
    public function getRecordedVideos($limit = 25, $offset = 0) {

        global $wpdb;

        $limit = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
        $offset = filter_var($offset, FILTER_SANITIZE_NUMBER_INT);

        // return only 10 records, start on record 0 (OFFSET 0)
        return $wpdb->get_results("
                                  SELECT * FROM {$wpdb->prefix}addpipe_records
                                  WHERE `active` = 1
                                  ORDER BY `date` DESC
                                  LIMIT {$limit} OFFSET {$offset}
                                  ");
    }

    /**
     * Selects and returns from DB active video recorder shortcodes
     * @param int $limit
     * @param int $offset
     * @return array|null|object
     */
    public function getGeneratedShortcodes($limit = 25, $offset = 0) {

        global $wpdb;

        $limit = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
        $offset = filter_var($offset, FILTER_SANITIZE_NUMBER_INT);

        // return only 10 records, start on record 0 (OFFSET 0)
        return $wpdb->get_results("
                                  SELECT * FROM {$wpdb->prefix}addpipe_shortcodes
                                  WHERE `active` = 1
                                  ORDER BY `date` DESC
                                  LIMIT {$limit} OFFSET {$offset}
                                  ");
    }

    /**
     * Check remote URL if exists (used to Sync Deleted Videos)
     * @param $url
     * @return bool
     */
    public function isFileOnServer($url) {

        $serverResponse = wp_remote_get(filter_var($url, FILTER_SANITIZE_URL));
        $serverResponse = $serverResponse['response']['code'];

        if ($serverResponse === 403 || $serverResponse === 404) {
            return false;
        }

        return true;

    }

    /* ------------------------------------------------------------------------------------------
     * AJAX CALLS
     * ------------------------------------------------------------------------------------------
     */

    /**
     * AJAX: generate a shortcode for the video recorder - EmbedRecorder.php
     */
    public function addpipe_ajax_shortcode_generator() {
        if (isset($_POST['action']) && $_POST['action'] === 'addpipe_ajax_shortcode_generator') {

            $length = filter_var($_POST['length'], FILTER_SANITIZE_NUMBER_INT);
            $quality = filter_var($_POST['quality'], FILTER_SANITIZE_STRING);

            global $wpdb;
            $wpdb->query("INSERT INTO {$wpdb->prefix}addpipe_shortcodes (`video_length`, `video_quality`) VALUES ('{$length}', '{$quality}')");
            $shortcode = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}addpipe_shortcodes` ORDER BY `date` DESC LIMIT 1");
            $shortcode = $shortcode[0];

            echo "
                    <h3 style='color: darkgreen;'>Shortcode created &check;</h3>
                    <h4>Now you can use the shortcode <code>[pipe_recorder {$shortcode->shortcode_id}]</code> in any page or post.</h4>
            ";

            // Kill the script, according to WP
            wp_die();
        }
    }

    /**
     * AJAX: save data from the Settings.php
     */
    public function addpipe_ajax_save_settings() {
        if (isset($_POST['action']) && $_POST['action'] === 'addpipe_ajax_save_settings') {

            if(isset($_POST['accountHash'])){
              $this->setAccountHash($_POST['accountHash']);
            }
            if(isset($_POST['videoLength'])){
              $this->setVideoLength($_POST['videoLength']);
            }
            if(isset($_POST['videoQuality'])){
              $this->setVideoQuality($_POST['videoQuality']);
            }

            echo "
                <h4 style='color: green;'>Settings have been updated &check;</h4>
                <p class='update-nag'>
                    Enter the following link <code>" . rtrim(get_site_url(), '/') . '/' . "</code> in your <a href='https://addpipe.com/home#webhook' target='_blank'>AddPipe.com</a> account &#8594; Webhook.<br />
                    Your videos will not be visible in the Recorded Videos menu until you register the above webhook. <a href='https://addpipe.com/docs#setting-up-webhooks' target='_blank'>Learn why</a>.
                </p>
            ";

            // Kill the script, according to WP
            wp_die();
        }
    }

    /**
     * AJAX: delete the video - RecordedVideos.php
     * TODO: currently not used - wait for the API to be ready first
     */
    public function addpipe_ajax_delete_video() {
        if (isset($_POST['action']) && $_POST['action'] === 'addpipe_ajax_save_settings') {

            global $wpdb;
            $video_id = filter_var($_POST['video_id'], FILTER_SANITIZE_NUMBER_INT);
            $wpdb->query("UPDATE {$wpdb->prefix}addpipe_records SET `active` = 0 WHERE `video_id` = {$video_id} LIMIT 1");
            echo "<strong>Deleted</strong>";

            // Kill the script, according to WP
            wp_die();
        }
    }

    public function addpipe_ajax_sync_deleted() {
        if (isset($_POST['action']) && $_POST['action'] === 'addpipe_ajax_sync_deleted' && wp_verify_nonce($_POST['_wpnonce'], $_POST['action'])) {

            global $wpdb;

            foreach ($this->getRecordedVideos() as $arr => $obj) {

                // Check the URL; if it returns 403 or 404, delete it from the database
                if ($this->isFileOnServer($obj->video_url)) {
                    echo "Video {$obj->video_id} OK | ";
                } else {
                    echo "Video {$obj->video_id} Deleted | ";
                    $wpdb->query("UPDATE {$wpdb->prefix}addpipe_records SET `active` = 0 WHERE `video_url` = '{$obj->video_url}' LIMIT 1");
                }

            }

            echo "<strong> Sync completed &check; </strong>";

            // Kill the script, according to WP
            wp_die();

        } else {

            echo "Request failed. Error #846531";
            wp_die();
        }
    }

    /* ------------------------------------------------------------------------------------------
     * GETTERS AND SETTERS
     * ------------------------------------------------------------------------------------------
     */

    /**
     * Get the account hash
     * @return string
     */
    public function getAccountHash() {
        $this->accountHash = get_option($this->_pluginScreenName . 'AccountHash');
        return (string) $this->accountHash;
    }

    /**
     * Get the video length
     * @return int
     */
    public function getVideoLength() {
        $this->videoLength = get_option($this->_pluginScreenName . 'VideoLength');
        return (int) $this->videoLength;
    }

    /**
     * Get the video quality
     * @return string
     */
    public function getVideoQuality() {
        $this->videoQuality = get_option($this->_pluginScreenName . 'VideoQuality');
        return (string) $this->videoQuality;
    }

    /**
     * Retrieves and sets to the current object the width and height based on video quality
     */
    public function getVideoWidthAndHeight() {

        if ($this->videoQuality == '240p') {
            $this->videoWidth = 320;
            $this->videoHeight = 270;
        } elseif ($this->videoQuality == '480p') {
            $this->videoWidth = 640;
            $this->videoHeight = 510;
        } else {
            $this->videoWidth = 640;
            $this->videoHeight = 390;
        }
    }

    /**
     * Set the account hash
     * @param $accountHash
     * @return bool
     */
    public function setAccountHash($accountHash) {
        $this->accountHash = filter_var(trim(rtrim($accountHash)), FILTER_SANITIZE_STRING);
        return update_option($this->_pluginScreenName . 'AccountHash', $accountHash);
    }

    /**
     * Set the video length
     * @param $videoLength
     * @return bool
     */
    public function setVideoLength($videoLength) {
        $this->videoLength = (int) trim(rtrim($videoLength));
        return update_option($this->_pluginScreenName . 'VideoLength', $videoLength);
    }

    /**
     * Set the video quality
     * @param $videoQuality
     * @return bool
     */
    public function setVideoQuality($videoQuality) {
        $this->videoQuality = filter_var($videoQuality, FILTER_SANITIZE_STRING);
        return update_option($this->_pluginScreenName . 'VideoQuality', $videoQuality);
    }

    /**
     * Sets the video width and height according to a custom user given value
     * @param $quality
     */
    public function setVideoWidthAndHeight($quality) {
        if ($quality == '240p') {
            $this->videoWidth = 320;
            $this->videoHeight = 270;
        } elseif ($quality == '480p') {
            $this->videoWidth = 640;
            $this->videoHeight = 510;
        } else {
            $this->videoWidth = 640;
            $this->videoHeight = 390;
        }
    }


    /**
     * New playback shortcode
     * @param $atts
     * @return string
     */
    public function newPlayback($atts) {

        global $wpdb;
        $videoId = filter_var($atts[0], FILTER_SANITIZE_NUMBER_INT);
        $html = '';

        // Check if the user has placed a video id
        if (empty($videoId)) {
            return $html = "Pipe Video Recorder error: you must enter a video id for the playback. Please copy the correct shortcode from the plugin's Recorded Videos.";
        }

        // Grab the video
        $videoDetails = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}addpipe_records` WHERE `video_id` = '{$videoId}' LIMIT 1");
        $videoDetails = $videoDetails[0];

        // Second check - does the video still exists? It's marked as active (1 or 0) in the DB
        if ($videoDetails->active == 0) {
            return $html = "Pipe Video Recorder error: this video has been deleted. Please run <strong>Sync Deleted Videos</strong> from the plugin's Recorded videos menu and use another playback shortcode.";
        }

        $html .= "
                <style type='text/css'>
                    .addpipe-playback {
                        text-align: center;
                        width: 100%;
                        height: 100%;
                    }
                    .player-playback {
                        border-radius: 5px;
                    }
                </style>
                <div class='addpipe-playback'>
                    <video class='player-playback' poster='{$videoDetails->video_img_url}' controls>
                        <source src='{$videoDetails->video_url}' type='video/mp4'>
                        <strong>Your browser does not support the video tag. Please use a newer browser (Chrome, Firefox).</strong>
                    </video>
                </div>
        ";

        return $html;

    }

    /**
     * Embed a new recorder - shortcode
     * * checks whether there are given values (as it should be)
     * * fault tolerant: uses the default values if no custom values are sent/set
     * @param $atts
     * @return string
     */
    public function newRecorder($atts) {

        global $wpdb;
        $recorderId = filter_var($atts[0], FILTER_SANITIZE_NUMBER_INT);
        $shortcodeRecord = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}addpipe_shortcodes` WHERE `shortcode_id` = '{$recorderId}' LIMIT 1");
        $shortcodeRecord = $shortcodeRecord[0];

        // If the user forgot to enter a recorder id, we'll just use the default settings
        if (empty($recorderId)) {
            $shortcodeRecord = new stdClass();
            $shortcodeRecord->video_quality = $this->videoQuality;
            $shortcodeRecord->video_length = $this->videoLength;
        }

        // Set the video width & height
        $this->setVideoWidthAndHeight($shortcodeRecord->video_quality);

        $html = "
                <style type='text/css'>
                    #accountHashCheck-missing {
                        color: red;
                        font-weight: bold;
                        padding: 20px;
                        margin-top: 20px;
                        margin-bottom: 20px;
                        background-color: white;
                        border-radius: 15px 15px;
                        text-align: center;
                    }
                    .addpipe-recorder {
                        text-align: center;
                    }
                </style>
                <div class='addpipe-recorder'></div>
                <script type='text/javascript'>

                    var size = {
                        width: {$this->videoWidth},
                        height: {$this->videoHeight}
                    };

                    var flashvars = {
                            qualityurl: 'avq/{$shortcodeRecord->video_quality}.xml',
                            accountHash: '{$this->accountHash}',
                            showMenu: 'true',
                            lang: 'translations/en.xml',
                            mrt: {$shortcodeRecord->video_length},
                            payload: '{$this->accountHash},".get_current_user_id().",{$this->getThePostId()}'
                        };

                    if (flashvars.accountHash.length < 10) {
                        document.getElementsByClassName('addpipe-recorder')[0].innerHTML += '<div id=\'accountHashCheck-missing\'></div>';
                        document.getElementById('accountHashCheck-missing').innerText = 'Missing account hash. You cannot Record or Play any videos. Please enter your account hash in the Settings menu';
                    } else {
                        document.getElementsByClassName('addpipe-recorder')[0].innerHTML += '<div id=\'hdfvr-content\'></div>';
                    }

                    (function () {
                    var pipe = document.createElement('script');
                    pipe.type = 'text/javascript';
                    pipe.async = true;
                    pipe.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 's1.addpipe.com/1.3/pipe.js';

                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(pipe, s);

                    })();
                </script>
        ";

        return $html;

    }

}
