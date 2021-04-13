<?php
if (!defined('ABSPATH')) {
    exit();
}

if (isset($_GET['acp_reset_options']) && $_GET['acp_reset_options'] == 1 && current_user_can('manage_options')) {
    delete_option($this->optionsSerialized->acp_option_slug);
    $this->optionsSerialized->addOptions();
    $this->optionsSerialized->initOptions(get_option($this->optionsSerialized->acp_option_slug));
}
?>
<div class="wrap">    
    <div style="float:left; width:34px; height:34px; margin:10px 10px 20px 0px;">
        <img src="<?php echo plugins_url(ACP_DIR_NAME . '/assets/img/acp.gif'); ?>" style="width:34px;"/></div>
    <h1><?php _e('Advanced Content Pagination Settings', 'advanced-content-pagination'); ?></h1>
    <br style="clear:both" />           
    <form class="acp-form" action="<?php echo admin_url(); ?>admin.php?page=acp_options&updated=true" method="post" name="acp_options">
        <?php
        if (function_exists('wp_nonce_field')) {
            wp_nonce_field('acp_options_form');
        }
        include 'layouts/promo.php';
        ?> 

        <div id="acpOptionsTab">
            <ul class="resp-tabs-list acp_options_tab_id">
                <li><?php _e('General settings', 'advanced-content-pagination'); ?></li>
                <li><?php _e('Button Style', 'advanced-content-pagination'); ?></li>
                <li><?php _e('Button Layouts', 'advanced-content-pagination'); ?></li>
                <li><?php _e('Carousel Settings', 'advanced-content-pagination'); ?></li>
                <li><?php _e('Custom Styles', 'advanced-content-pagination'); ?></li>
            </ul>
            <div class="resp-tabs-container acp_options_tab_id">
                <?php
                include 'layouts/settings-general.php';
                include 'layouts/settings-button-style.php';
                include 'layouts/settings-button-layouts.php';
                include 'layouts/settings-jcarousel.php';
                include 'layouts/settings-custom-styles.php';
                ?>
            </div>
        </div>
        <table class="form-table wc-form-table">
            <tbody>
                <tr valign="top">
                    <td colspan="4">
                        <p class="submit">
                            <a id="acp-reset-options" style="float: left;" class="button button-secondary" href="<?php echo admin_url(); ?>admin.php?page=acp_options&updated=true&acp_reset_options=1"><?php _e('Reset Options', 'advanced-content-pagination'); ?></a>
                            <input style="float: right;" type="submit" class="button button-primary" name="submit" value="<?php _e('Save Changes', 'advanced-content-pagination'); ?>" />
                        </p>
                    </td>
                </tr>
            <input type="hidden" name="action" value="update" />
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var width = 0;
        var acpOptionsTabsType = 'default';
        $('#acpOptionsTab ul.resp-tabs-list.acp_options_tab_id li').each(function () {
            width += $(this).outerWidth(true);
        });

        if (width > $('#acpOptionsTab').innerWidth()) {
            acpOptionsTabsType = 'vertical';
        }
        //Horizontal Tab
        $('#acpOptionsTab').easyResponsiveTabs({
            type: acpOptionsTabsType, //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'acp_options_tab_id' // The tab groups identifier
        });
    });
</script>