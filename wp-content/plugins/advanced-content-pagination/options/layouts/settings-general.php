<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <table class="wp-list-table widefat plugins"  style="margin-top:10px; border:none;">
        <tr valign="top">
            <th scope="row">
                <?php _e('Turn on/off Content Pagination', 'advanced-content-pagination'); ?> 
            </th>
            <td colspan="3">
                <label for="acp_paging_on_off">
                    <input type="checkbox" <?php checked($this->optionsSerialized->acp_paging_on_off == 1) ?> value="1" name="acp_paging_on_off" id="acp_paging_on_off" />
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Pagnation Buttons Layout', 'advanced-content-pagination'); ?></th>
            <td colspan="3">
                <fieldset>
                    <?php $acp_def_shortcode = $this->optionsSerialized->acp_wp_shortcode_pagination_view; ?>
                    <label title="default">
                        <input type="radio" value="1" <?php checked('1' == $acp_def_shortcode); ?> name="acp_wp_shortcode_pagination_view" id="shortcode_default" /> 
                        <span><?php _e('Default', 'advanced-content-pagination'); ?></span>
                    </label><br>
                    <label title="tabbed">
                        <input type="radio" value="2" <?php checked('2' == $acp_def_shortcode); ?> name="acp_wp_shortcode_pagination_view" id="shortcode_tabbed" /> 
                        <span><?php _e('Tabbed', 'advanced-content-pagination'); ?></span>
                    </label><br>                                    
                </fieldset>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Pagnation Loading Type', 'advanced-content-pagination'); ?></th>
            <td colspan="3">
                <fieldset>
                    <?php
                    $acp_plug_shortcode = $this->optionsSerialized->acp_plugin_pagination_type;
                    ?>
                    <label title="reload page">
                        <input type="radio" value="1" <?php checked('1' == $acp_plug_shortcode); ?> name="acp_plugin_pagination_type" /> 
                        <span><?php _e('Reload Page', 'advanced-content-pagination'); ?></span>
                    </label><br>
                    <label title="ajax">
                        <input type="radio" value="2" <?php checked('2' == $acp_plug_shortcode); ?> name="acp_plugin_pagination_type" /> 
                        <span><?php _e('Ajax', 'advanced-content-pagination'); ?></span>
                    </label><br>                                    
                </fieldset>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Pagination Button Location', 'advanced-content-pagination'); ?>:</th>
            <td colspan="3">
                <fieldset>
                    <?php
                    $acp_pag_buttons_loc = $this->optionsSerialized->acp_paging_buttons_location;
                    ?>
                    <label title="top">
                        <input type="radio" value="1" <?php checked('1' == $acp_pag_buttons_loc); ?>  name="acp_paging_buttons_location" /> 
                        <span><?php _e('Top', 'advanced-content-pagination'); ?></span>
                    </label><br>
                    <label title="bottom">
                        <input type="radio" value="2" <?php checked('2' == $acp_pag_buttons_loc); ?> name="acp_paging_buttons_location" /> 
                        <span><?php _e('Bottom', 'advanced-content-pagination'); ?></span>
                    </label><br>
                    <label title="both">
                        <input type="radio" value="3" <?php checked('3' == $acp_pag_buttons_loc); ?> name="acp_paging_buttons_location" /> 
                        <span><?php _e('Both', 'advanced-content-pagination'); ?></span>
                    </label><br>
                </fieldset>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Do Shortcodes In Excerpts', 'advanced-content-pagination'); ?>:</th>
            <td colspan="3">
                <fieldset>
                    <?php
                    $acp_do_shortcodes_excerpts = $this->optionsSerialized->acp_do_shortcodes_excerpts;
                    ?>
                    <label title="top">
                        <input type="checkbox" value="2" <?php checked('2' == $acp_do_shortcodes_excerpts); ?>  name="acp_do_shortcodes_excerpts" id="acp_do_shortcodes_excerpts" />
                    </label><br>
                </fieldset>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Excerpt Words Count', 'advanced-content-pagination'); ?>:</th>
            <td colspan="3">
                <fieldset>
                    <?php
                    $acp_excerpts_count = $this->optionsSerialized->acp_excerpts_count;
                    ?>
                    <label title="top">
                        <input type="text" value="<?php echo $acp_excerpts_count; ?>" name="acp_excerpts_count" id="acp_excerpts_count"/>                 
                    </label><br>
                </fieldset>
            </td>
        </tr>        
    </table>
</div>