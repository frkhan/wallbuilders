<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}

/*
 * Record new view
 */

?>

<?php 
if(!empty($this->accountHash)){
?>
<style>
.inside-centered {
    margin: auto;
    text-align: center;
}
</style>
<div class="wrap">
    <h3>Record a new video</h3>
    <div id="poststuff">
        <!-- Record a new video and place it on any post or page using the embed code from the <a href="<?php echo admin_url() . 'admin.php?page=addpipe-for-wordpress/load/recorded-videos'; ?>" target="_self">list of recorded videos</a>. -->
        <p>Use this page to record a new video. Once recorded, the video will show up in the <a href="<?php echo admin_url();?>admin.php?page=addpipe-for-wordpress/load/recorded-videos">list of recorded videos</a>. You can use the embed code in the list to add the video to any page or blog post.</p>
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div>
                        <div class="inside">
                            <div class="inside-centered">
                                <!-- The Pipe video recorder will be visible here -->
                            </div>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .meta-box-sortables .ui-sortable -->
            </div><!-- post-body-content -->
            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h3>
                            <span>
                                Good to know
                            </span>
                        </h3>
                        <div class="inside">
                            <!-- <p>
                                The video will be made available in the <code>Recorded videos</code> menu and it can take from a few seconds up to a couple of minutes for it to be visible in your dashboard, depending on the video quality and the length you have selected.
                            </p> -->
                            <p>Depending on the video quality and the length of the video it can take from a few seconds up to a couple of minutes for it to show up in the <a href="<?php echo admin_url(); ?>admin.php?page=addpipe-for-wordpress/load/recorded-videos">list of recorded videos</a>.</p>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .meta-box-sortables -->
            </div><!-- #postbox-container-1 .postbox-container -->
            <!-- Default settings -->
            <table class="widefat">
                <tr>
                    <th colspan="2" class="alternate"><strong>Recorder settings</strong></th>
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
                        What's the maximum length (in seconds) the video can have?
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
            </table>
        </div><!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div><!-- #poststuff -->
</div>


<style type="text/css">
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

<script type="text/javascript">

    var size = {
        width: <?php echo $this->videoWidth; ?>,
        height: <?php echo $this->videoHeight; ?>
    };

    var flashvars = {
        qualityurl: "avq/<?php echo $this->videoQuality; ?>.xml",
        accountHash: "<?php echo $this->accountHash; ?>",
        showMenu: "true",
        lang: "translations/en.xml",
        mrt: <?php echo $this->videoLength; ?>,
        payload: '<?php echo $this->accountHash . ',' . get_current_user_id() . ',' . $this->getThePostId(); ?>'
    };



    var pipeCode = function () {
        var pipeCheck = document.getElementById("VideoRecorder");
        if(pipeCheck){
          pipeCheck.parentNode.innerHTML = "";
        }
       //  if (flashvars.accountHash.length < 10) {
// //             document.getElementsByClassName('inside-centered')[0].innerHTML += "<div id='accountHashCheck-missing'></div>";
// //             document.getElementById('accountHashCheck-missing').innerText = 'Missing account hash. You cannot Record or Play any videos. Please enter your account hash in the Settings menu';
// 				var notice = document.createElement("div");
// 				// notice.id = "accountHashCheck-missing";
// 				notice.className = "error";
// 				notice.innerText = 'Missing account hash. You cannot Record or Play any videos. Please enter your account hash in the Settings page';
// 				notice.style.padding = '10px';
// 				document.getElementById("poststuff").insertBefore(notice,document.getElementById("poststuff").firstChild);
//         } else {
            document.getElementsByClassName('inside-centered')[0].innerHTML += "<div id='hdfvr-content'></div>";
//         }
        var pipe = document.createElement('script');
        pipe.type = 'text/javascript';
        pipe.async = true;
        pipe.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 's1.addpipe.com/1.3/pipe.js';
        pipe.id = "pipeEmbed";

        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(pipe, s);

    };

    //initialise the recorder with the default settings
    pipeCode();


    // on changing, the settings will also be updated in the database
    var videoLengthInput = document.getElementById("videoLength");
    var videoResolutionInput = document.getElementById("videoQuality");

    videoLengthInput.addEventListener("change", function() {
      if(this.value <=0){
        alert('The length cannot be 0');
      } else {
        flashvars.mrt=this.value;
        data = {
            action: 'addpipe_ajax_save_settings',
            videoLength: flashvars.mrt
        };
        videoLengthInput.disabled = "disabled";
        jQuery.post(ajaxurl, data, function(response){
            // console.log(response);
            pipeCode();
            videoLengthInput.removeAttribute('disabled');
        });
      }
    });

    videoResolutionInput.addEventListener("change", function() {
      var vq = this.value
      flashvars.qualityurl = "avq/"+vq+".xml";

      data = {
          action: 'addpipe_ajax_save_settings',
          videoQuality: vq
      };
      videoResolutionInput.disabled = "disabled";
      jQuery.post(ajaxurl, data, function(response){
          // console.log(response);
          pipeCode();
          videoResolutionInput.removeAttribute('disabled');
      });
    });
</script>
<?php } else { ?>
	 <div class="error">
		<h4>Missing account hash. Please enter your account hash in the <a href="<?php echo admin_url(); ?>admin.php?page=addpipe-for-wordpress/load/settings">settings</a> page to record and play videos.</h4>
	</div>
<?php } ?>