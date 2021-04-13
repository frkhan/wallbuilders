<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}


/*
 * Get all recorded videos
 *
 * $limit - how many to pull at once
 * $offset - from which index should we start displaying
 */
$generatedShortcodes = $this->getGeneratedShortcodes();

?>
<style type="text/css">
    .settings-container {
        display: flex;
        flex-wrap: wrap;
        flex-flow: column;
        font-weight: bold;
    }
    .settings-body {
        padding-top: 15px;
        max-width: 750px;
        display: flex;
        flex-wrap: wrap;
        flex-flow: column;
    }
    td.comment {
        font-weight: normal;
        font-size: 12px;
        text-align: left;
    }
    .widefat td, .widefat th {
        vertical-align: middle;
    }
</style>
<div class="wrap">

    <?php if (!empty($this->accountHash)) { ?>
    <div class="settings-container">
        <h3>Embed a video recorder in your posts or pages</h3>
		 <p>How to embed a video recorder in a page or blog post:</p>
		 <ol>
			<li>Choose the desired video resolution and maximum video length in seconds and generate a shortcode. </li>
			<li>Copy the shortcode and paste it in your post/page where you want the video recorder to show up.</li>
		</ol>
        <div class="settings-body">

            <!-- Generate new shortcode -->
            <table class="widefat">
                <tr>
                    <th colspan="2" class="alternate"><strong>Generate a shortcode</strong></th>
                    <th class="alternate"></th>
                </tr>
                <tr>
                    <td>
                        <label for="videoLength">Maximum video length</label>
                    </td>
                    <td>
                        <input id="videoLength" name="videoLength" type="text" style="width: 100%;" value="<?php echo $this->videoLength; ?>" />
                    </td>
                    <td class="comment">
                        How many <strong>seconds</strong> should a user be able to record himself?
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label for="videoQuality">Video resolution</label>
                    </td>
                    <td>
                       <select id="videoQuality" style="width: 100%;">
                            <option value="240p" <?php echo ($this->videoQuality == '240p') ? 'selected' : '' ?>>Small (320x240 @30fps, 4:3)</option>
                            <option value="480p" <?php echo ($this->videoQuality == '480p') ? 'selected' : '' ?>>Medium (640x480 @30fps, 4:3)</option>
                            <option value="720p" <?php echo ($this->videoQuality == '720p') ? 'selected' : '' ?>>Large (1280x720 @30fps, 16:9)</option>
                        </select>
                    </td>
                    <td class="comment" style="max-width: 250px;">
 						<p>A high quality webcam is needed for recording at high resolutions like 720p.</p>
                    </td>
                </tr>
                <tr>
					<td colspan=2>
						<p>
							<input class="button-primary" type="submit" name="save-settings" id="button-save-settings" value="Generate shortcode" />
						</p>
					</td>
                </tr>
            </table>
            

            <div style="margin-top: 35px;"></div>

            <!-- Available shortcodes -->
            <table class="widefat">
                <tr>
                    <th class="alternate"><strong>Copy and paste one of these shortcodes</strong></th>
                </tr>
                <?php

                if (empty($generatedShortcodes)) {
                    echo "
                            <tr>
                                <td>
                                    <h3>No shortcodes have been generated.</h3>
                                </td>
                            </tr>
                            ";
                } else {
                    foreach ($generatedShortcodes as $arr => $shortcode) {
                        echo "
                                <tr>
                                    <td>
                                        <input type='text' readonly value='[pipe_recorder {$shortcode->shortcode_id}]' /><br />
                                        <small>
                                            <i class='dashicons dashicons-clock'></i>{$shortcode->video_length} seconds
                                            <i class='dashicons dashicons-admin-generic'></i> {$shortcode->video_quality}
                                        </small>
                                    </td>
                                </tr>
                        ";
                    }
                }
                ?>
            </table>

        </div><!-- End settings-body -->
        <p>Once you paste in a page or post one of the shortcodes above, registered users (members) or visitors will be able to record video messages, CVs or any other type of video content.</p>
    </div><!-- End settings-container -->

    <!-- Account hash check -->
    <?php
    } else {
       ?>
		<div class="error">
			<h4>Missing account hash. Please enter your account hash in the <a href="<?php echo admin_url(); ?>admin.php?page=addpipe-for-wordpress/load/settings">settings</a> page to record and play videos.</h4>
		</div>
       <?php 
    }
    ?>

</div><!-- End wrap -->


<script type="text/javascript">
    jQuery(document).ready(function($) {

        var settingsBody = $('.settings-body');
        var buttonSave = $('#button-save-settings');

        buttonSave.click(function() {

            // Mangle the DOM
            settingsBody.slideUp();
            settingsBody.after("<h3 id='message-saving-settings'>Generating shortcode...</h3>");
            buttonSave.hide();

            // Grab the data
            var videoLength = $('#videoLength').val();
            var videoQuality = $('#videoQuality option:selected').val();

            // Setup the data object
            data = {
                action: 'addpipe_ajax_shortcode_generator',
                length: videoLength,
                quality: videoQuality
            };

            // Post it and write the message
            setTimeout(function() {
                $.post(ajaxurl, data, function(response){
                    $('#message-saving-settings').html(response);
                });
            }, 1000);

            return false;
        });
    });
</script>




<?php
