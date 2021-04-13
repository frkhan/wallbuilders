<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tr valign="top">
            <th scope="row">
                <?php _e('Custom CSS Code', 'advanced-content-pagination'); ?>:
            </th>
            <td>
                <label>
                    <textarea rows="10" cols="50" name="acp_custom_css" id="acp_custom_css"><?php echo isset($this->optionsSerialized->acp_custom_css) ? $this->optionsSerialized->acp_custom_css : ''; ?></textarea>
                </label><br>
            </td>
        </tr>
    </table>
</div>