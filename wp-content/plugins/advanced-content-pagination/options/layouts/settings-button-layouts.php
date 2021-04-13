<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tr>
            <th scope="row">
                <?php _e('Display Only Previous and Next Buttons', 'advanced-content-pagination'); ?>:
        <p style="font-size:13px; color:#666666; margin:0px; "><?php _e('This option is only related to the first and second layouts. Allows to only show the previous and next titles in subPage buttons.', 'advanced-content-pagination'); ?></p>
        </th>
        <td colspan="3">
            <label title="Only Prev/Next Buttons">
                <?php $acp_buttons_prev_next = isset($this->optionsSerialized->acp_buttons_prev_next) ? $this->optionsSerialized->acp_buttons_prev_next : 0; ?>
                <input type="checkbox" <?php checked($acp_buttons_prev_next == 1); ?> value="1" name="acp_buttons_prev_next" id="acp_buttons_prev_next"/> 
            </label><br>
        </td>
        </tr>

        <tr valign="top" class="paging_btn_layout">
            <th scope="row"><?php _e('Pagination Button Style', 'advanced-content-pagination'); ?>:</th>
            <td colspan="3">
                <fieldset>
                    <?php
                    $acp_btns_visual_style = $this->optionsSerialized->acp_buttons_visual_style;
                    ?>
                    <label title="Title">
                        <input type="radio" <?php checked($acp_btns_visual_style == 1); ?> value="1" name="acp_buttons_visual_style" id="t_button" /> 
                        <span>
                            <img src="<?php echo plugins_url(ACP_DIR_NAME . '/assets/img/t_button.png'); ?>" align="absmiddle" style="padding:3px 5px;"  />
                        </span>
                    </label><br>
                    <label title="Title and Number">
                        <input type="radio" <?php checked($acp_btns_visual_style == 2); ?> value="2" name="acp_buttons_visual_style" id="nt_button" /> 
                        <span>
                            <img src="<?php echo plugins_url(ACP_DIR_NAME . '/assets/img/nt_button.png'); ?>" align="absmiddle" style="padding:3px 5px;" />
                        </span>
                    </label><br>
                    <label title="Next and Prev">
                        <input type="radio" <?php checked($acp_btns_visual_style == 4); ?> value="4" name="acp_buttons_visual_style" id="nt_button" /> 
                        <span>
                            <img src="<?php echo plugins_url(ACP_DIR_NAME . '/assets/img/pn_button.png'); ?>" align="absmiddle" style="padding:3px 5px;"  />
                        </span>
                    </label><br>
                </fieldset>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="acpPhrasePrevious"><?php _e('Previous button text', 'advanced-content-pagination'); ?></label></th>
            <td>
                <input type="text" id="acpPhrasePrevious" name="acpPhrasePrevious" value="<?php echo $this->optionsSerialized->acpPhrasePrevious; ?>"/>                   
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="acpPhraseNext"><?php _e('Next button text', 'advanced-content-pagination'); ?></label></th>   
            <td>
                <input type="text" id="acpPhraseNext" name="acpPhraseNext" value="<?php echo $this->optionsSerialized->acpPhraseNext; ?>"/>
            </td>
        </tr>
    </table>
</div>