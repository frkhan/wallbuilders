<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}

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
    .widefat td {
        vertical-align: middle;
    }
</style>

<style type="text/css">
    .go-green, .go-red, .go-yellow {
        display: inline-block;
        padding: 2px 4px;
        border-radius: 2px;;
        color: white;
        font-weight: bolder;
    }
    .go-green {
        background-color: green;
    }
    .go-red {
        background-color: red;
    }
    .go-yellow {
        background: darkgoldenrod;
    }
    .postbox {
        /*margin: auto;*/
        text-align: center;
        padding:10px;
    }
</style>

<div class="wrap">
    <div class="settings-container">
        <h3>Your addpipe.com Account Hash</h3>
        <div class="settings-body">

            <!-- General settings -->
            <table class="widefat">
                <tr>
                    <td>
                        <label for="accountHash">
                            Account Hash <br />
                            <?php echo (empty($this->accountHash)) ? "<span style='color: red;'>Missing</span>" : ''; ?>
                        </label>
                    </td>
                    <td>
                        <input id="accountHash" name="accountHash" type="text" style="width: 100%;" value="<?php echo $this->accountHash; ?>" />
                    </td>
                    <td class="comment">
                        The <strong>Account Hash</strong> can be found in your addpipe.com account <br>by clicking on the top right <strong>Account</strong> link after <a href="https://addpipe.com/signin" target="_blank">signing in</a>.
                    </td>
                </tr>
                <tr class="alternate">
                	<td colspan="2">
                		<p>
        					<input class="button-primary" type="submit" name="save-settings" id="button-save-settings" value="Save" />
 						</p>
 					</td>
 					<td></td>
 				</tr>
            </table>
            <br>
			<h3>Get an addpipe.com account</h3>
			<p style="font-weight:normal";>The Pipe Video Recorder Plugin for WordPress uses <a href="https://addpipe.com" target="_blank">addpipe.com</a> to process videos. If you don’t have an <a href="https://addpipe.com" target="_blank">addpipe.com</a> account you can start a free, fully featured, 14 days trial. Just <a href="https://addpipe.com/signup?trial" target="_blank">go to https://addpipe.com/signup?trial</a> and create your account, it takes 2 minutes.</p>
			<a class="button-primary" href="https://addpipe.com/signup?trial" target="_blank" style="width:230px;">Sign-up for a free Pipe account</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){

        var settingsBody = $('.settings-body');
        var buttonSave = $('#button-save-settings');

        buttonSave.click(function(){

            // Mangle the DOM
            settingsBody.slideUp();
            settingsBody.after("<h3 id='message-saving-settings'>Saving new settings...</h3>");
            buttonSave.hide();

            // Grab the data
            var accountHash = $('#accountHash').val();
            // Setup the data object
            data = {
                action: 'addpipe_ajax_save_settings',
                accountHash: accountHash
            };

            // Post it and write the message
            setTimeout(function(){
                $.post(ajaxurl, data, function(response){
                    $('#message-saving-settings').html(response);
                });
            }, 500);

            return false;
        });
    });
</script>




<?php
