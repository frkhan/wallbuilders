<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tr>
            <th scope="row"><?php _e('Carousel wrapping options', 'advanced-content-pagination'); ?>:</th>
            <td colspan="1">
                <fieldset>            
                    <select name="acp_jcarousel_wrapping" id="acp_jcarousel_wrapping" class="acp_jcarousel_wrapping">
                        <?php $acp_jcarousel_wrapping = isset($this->optionsSerialized->acp_jcarousel_wrapping) ? $this->optionsSerialized->acp_jcarousel_wrapping : 'circular'; ?>
                        <option value="none" <?php selected($acp_jcarousel_wrapping, 'none'); ?>>None</option>
                        <option value="circular" <?php selected($acp_jcarousel_wrapping, 'circular'); ?>>Circular</option>
                        <option value="first" <?php selected($acp_jcarousel_wrapping, 'first'); ?>>First</option>
                        <option value="last" <?php selected($acp_jcarousel_wrapping, 'last'); ?>>Last</option>
                    </select>
                </fieldset>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_arrows_bg"><?php _e('Carousel arrows background color', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_arrows_bg; ?>" id="acp_arrows_bg" name="acp_arrows_bg"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_arrows_hover_bg"><?php _e('Carousel arrows hover background color', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_arrows_hover_bg; ?>" id="acp_arrows_hover_bg" name="acp_arrows_hover_bg"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_arrows_color"><?php _e('Carousel arrows color', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_arrows_color; ?>" id="acp_arrows_color" name="acp_arrows_color"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_arrows_hover_color"><?php _e('Carousel arrows hover color', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_arrows_hover_color; ?>" id="acp_arrows_hover_color" name="acp_arrows_hover_color"/>
            </td>
        </tr>
    </table>
</div>