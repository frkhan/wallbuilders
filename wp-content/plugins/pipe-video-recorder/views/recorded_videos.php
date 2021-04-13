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
$recordedVideos = $this->getRecordedVideos();

?>

<style type="text/css">
    .widefat td, .widefat th {
        vertical-align: middle;
    }
    .widefat tr:hover {
        background-color: #F1F1F1;
    }
    .widefat tr, .widefat th {
        text-align: center;
    }
    .sync-deleted-videos {
        padding: 4px;
        color: white;
        background-color: darkslategray;
        border: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        cursor: hand;
        cursor: pointer;
    }
</style>

<div class="wrap">

    <!-- Logo/title -->
    <h3>Manage your recorded videos</h3>

    <!-- If we have recorded videos, show the sync deleted option -->
    <?php echo (!empty($recordedVideos)) ? "<h4>Have you deleted videos from your addpipe.com account? Don't forget to <button class='sync-deleted-videos'>Sync Deleted Videos</button></h4>" : ''; ?>

    <!-- Start the listing -->
    <table class="widefat">
        <thead>
        <tr>
            <th>Preview</th>
            <th>Video details</th>
            <th>Date recorded</th>
            <th>Video ID</th>
            <th>Action</th>
            <th>Embed code</th>
        </tr>
        </thead>
        <tbody>

            <!-- Check whether we have recorded videos -->
            <?php if (!empty($recordedVideos)) { ?>
                <!-- Iterate through all videos that are ACTIVE -->
                <?php foreach ($recordedVideos as $arr => $obj) { ?>

                <tr>
                    <td>
                        <!-- Img (snapshot/thumbnail) -->
                        <img src="<?php echo $obj->video_img_url; ?>" width="150px" onerror="imageErrorHandler(this);" />
                    </td>
                    <td>
                        <!-- Details (length, size) -->
                        <?php echo $this->filesizeForHumans($obj->video_size); ?>
                        <br />
                        <?php echo $obj->video_length . ' seconds'; ?>
                        <br />
                        By:
                        <!-- User who recorded the video -->
                        <?php $user_data = get_userdata($obj->user_id); ?>
                        <?php echo ($user_data) ? $user_data->user_login : '<em>visitor</em>'; ?>
                    </td>
                    <td>
                        <!-- Date -->
                        <?php echo $obj->date; ?>
                    </td>
                    <td>
                        <!-- Video ID -->
                        <?php echo $obj->video_id; ?>
                    </td>
                    <td>
                        <!-- Control buttons: Play, Embed code, Download, Delete -->
                        <!-- Play video button -->
                        <a class="button-secondary playvideo" href="<?php echo $obj->video_url; ?>" target="_blank" title="Play video">Play video</a>
                        <!-- Download button -->
                        <a class="button-secondary" href="
                                                        <?php echo
                                                            $this->getPluginViewURL("download.php?key=")
                                                            .
                                                            sha1('addpipe') . '&video=' . filter_var($obj->video_url, FILTER_SANITIZE_URL);
                                                        ?>" target="_blank" title="Download">Download</a>
                    </td>
                    <td>
                        <p>
                            <!-- Shortcode input text -->
                            <input type="text" readonly value="[pipe_playback <?php echo $obj->video_id; ?>]" />
                        </p>
                    </td>
                </tr>

                <?php

                } // End foreach

            } else {

                echo '
                       <td colspan="6">
                            	<h3>There are no video entries in the local database.</h3>
                            	<p>If you have recorded videos through the plugin but they don\'t show up here, make sure you\'ve correctly set up the webhook in your <a href=\'https://addpipe.com\' target=\'_blank\'>addpipe.com account.</a></p> 
                            </td>
                        ';
            }

            ?> <!-- End if check -->

        </tbody>
        <tfoot>
        <tr>
            <th>Preview</th>
            <th>Video details</th>
            <th>Date recorded</th>
            <th>Video ID</th>
            <th>Action</th>
            <th>Embed code</th>
        </tr>
        </tfoot>
    </table>

    <div id="dialog"></div>

</div>
<script type="text/javascript">

    /* Replace the missing videos image */
    function imageErrorHandler(img) {
        img.onerror = '';
        img.src = '<?php echo $this->getPluginViewURL('img/deleted_video.png'); ?>';
        return true;
    }

    /* Sync deleted videos - ajax button */
    jQuery(document).ready(function($) {

        var btnSyncDeleted = $('.sync-deleted-videos');
        btnSyncDeleted.click(function() {
            $(this).html('Synchronizing...');

            setTimeout(function(){
                data = {
                    action: 'addpipe_ajax_sync_deleted',
                    _wpnonce: "<?php echo wp_create_nonce('addpipe_ajax_sync_deleted'); ?>"
                };
                $.post(ajaxurl, data, function(response) {
                    btnSyncDeleted.html(response);
                });
            }, 650);
        });
    });
</script>
