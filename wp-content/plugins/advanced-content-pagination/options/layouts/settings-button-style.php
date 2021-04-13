<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tr valign="top">
            <th scope="row">
                <?php _e('Carousel Buttons Arrows Fixed Position', 'advanced-content-pagination'); ?>:
            </th>
            <td colspan="3">                                
                <label for="acp_buttons_is_arrow_fixed">
                    <input type="checkbox" <?php checked($this->optionsSerialized->acp_buttons_is_arrow_fixed == 1) ?> value="1" name="acp_buttons_is_arrow_fixed" id="acp_buttons_is_arrow_fixed" />
                </label>
            </td>
        </tr> 
        <tr>
            <th scope="row">
                <label for="acp_buttons_title_size_css"><?php _e('Button Title Font Size', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td colspan="3">
                <select id="acp_buttons_title_size_css" name="acp_buttons_title_size_css">
                    <?php
                    $acp_btns_text_size = $this->optionsSerialized->acp_buttons_title_size_css;
                    for ($i = 10; $i <= 20; $i++) {
                        echo '<option value="' . $i . 'px"' . selected($acp_btns_text_size, $i . 'px') . '>' . $i . 'px</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>       
        <tr valign="top">
            <th scope="row">
                <label for="acp_buttons_border_css"><?php _e('Button Border', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text" value="<?php echo $this->optionsSerialized->acp_buttons_border_css; ?>" id="acp_buttons_border_css" name="acp_buttons_border_css" placeholder="<?php _e('Example: 1px solid #ff0000', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_buttons_background_css"><?php _e('Button Background Color', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_buttons_background_css; ?>" id="acp_buttons_background_css" name="acp_buttons_background_css" placeholder="<?php _e('Example: #0000ff', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="acp_buttons_background_hover_css"><?php _e('Button Hover Background Color', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_buttons_background_hover_css; ?>" id="acp_buttons_background_hover_css" name="acp_buttons_background_hover_css" placeholder="<?php _e('Example: #00ff00', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>        
        <tr valign="top">
            <th scope="row">
                <label for="acp_buttons_text_color_css"><?php _e('Button Text Color', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_buttons_text_color_css; ?>" id="acp_buttons_text_color_css" name="acp_buttons_text_color_css" placeholder="<?php _e('Example: #f0000f', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_buttons_hover_text_color"><?php _e('Button Hover Text Color', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_buttons_hover_text_color; ?>" id="acp_buttons_hover_text_color" name="acp_buttons_hover_text_color" placeholder="<?php _e('Example: #000000', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_buttons_font_css"><?php _e('Button Text Font', 'advanced-content-pagination'); ?>:  </label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text" value="<?php echo $this->optionsSerialized->acp_buttons_font_css; ?>" id="acp_buttons_font_css" name="acp_buttons_font_css" placeholder="<?php _e('Example: Times New Roman, Times, serif', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_active_button_border_css"><?php _e('Active Button Border', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td colspan="3">
                <input type="text" class="regular-text" value="<?php echo $this->optionsSerialized->acp_active_button_border_css; ?>" id="acp_active_button_border_css" name="acp_active_button_border_css" placeholder="<?php _e('Example: 1px solid #ff0000', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_active_button_background_css"><?php _e('Active Button Background Color', 'advanced-content-pagination'); ?>:</label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_active_button_background_css; ?>" id="acp_active_button_background_css" name="acp_active_button_background_css" placeholder="<?php _e('Example: #0000ff', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_active_button_text_color_css"><?php _e('Active Button Text Color', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_active_button_text_color_css; ?>" id="acp_active_button_text_color_css" name="acp_active_button_text_color_css" placeholder="<?php _e('Example: #0000ff', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="acp_load_container_css"><?php _e('Loading Background Color', 'advanced-content-pagination'); ?>: </label>
            </th>
            <td>
                <input type="text" class="regular-text acp-color-picker" value="<?php echo $this->optionsSerialized->acp_load_container_css; ?>" id="acp_load_container_css" name="acp_load_container_css" placeholder="<?php _e('Example: #cccccc', 'advanced-content-pagination'); ?>"/>
            </td>
        </tr>
    </table>
</div>