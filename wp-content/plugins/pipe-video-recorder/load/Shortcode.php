<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}

/**
 * Class Shortcode
 *
 * Handles the Recorder and Playback shortcodes
 * Currently unused
 */
class Shortcode extends AddPipe {

    public $atts = array();

    public function __construct($atts) {
        parent::__construct();
        $this->atts = $atts;

        if (in_array('pipe_playback', $atts)) {
            $this->newPlaybackView();
        } elseif (in_array('pipe_recorder', $atts)) {
            $this->newRecorderView();
        } else {
            $this->newRecorderView();
        }

    }

    public function __toString() {
        return __CLASS__;
    }

    public function newPlaybackView($videoId = '') {

        if (empty($videoId)) {
            throw new Exception('The playback shortcode must have a numerical id! Error #784531.');
        }

        global $wpdb;
        $videoId = filter_var($videoId, FILTER_SANITIZE_NUMBER_INT);
        $videoDetails = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}addpipe_records` WHERE `video_id` = '{$videoId}' LIMIT 1");
        $videoDetails = $videoDetails[0];

        return $videoDetails;
    }

    public function newRecorderView() {
        print_r($this->atts);
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
                </style>
                <script type='text/javascript'>

                    var size = {
                        width: {$this->videoWidth},
                        height: {$this->videoHeight}
                    };

                    var flashvars = {
                            qualityurl: 'avq/{$this->videoQuality}.xml',
                            accountHash: '{$this->accountHash}',
                            showMenu: 'true',
                            lang: 'translations/en.xml',
                            mrt: {$length},
                            payload: '{$this->accountHash},".get_current_user_id().",{$this->getThePostId()}'
                        };

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
    }
}
