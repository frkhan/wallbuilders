<style>#TB_window{ background:#F0F0F0}</style>
<div id="acp_dialog" style="display:none;">
    <div class="shortcode_dialog" style="padding:0px 20px 20px 20px;">    
        <h2 style="font-weight:normal; padding-bottom:10px;"><?php _e('Pagination Button Components', 'advanced-content-pagination'); ?> </h2>
        <div class="acp_title_wrap">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:160px; vertical-align:top; padding-top:5px;"><label class="shortcode_title" for="shortcode_title" style="font-size:13px; font-weight:bold;color:#333333"><?php _e('Button Title:', 'advanced-content-pagination'); ?></label></td>
                    <td>
                        <input id="shortcode_title" class="shortcode_title" type="text"  style="border:#cccccc 1px solid; padding:3px 3px; width:350px; font-size:16px;" maxlength="100" />
                        <br />
                        <span style="font-size:12px; font-style:italic; color:#555555; cursor:help;"><?php _e('maximum characters', 'advanced-content-pagination'); ?> 
                            <span title="Pagination Button Layout #1 with image title and description">L1:15</span> , 
                            <span title="Pagination Button Layout #2 with title and description">L2:24</span> , 
                            <span title="Pagination Button Layouts #3, #4 and #5">L3,L4,L5:43</span>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="submit_container" style="text-align:right; padding-right:50px; display:block; padding-top:15px;">
            <button id="insert_shorcode" class="insert_shortcode button button-primary button-large"><?php _e('Insert Page', 'advanced-content-pagination'); ?></button>
        </div>

        <div class="button_layout_wrapper">
            <div style="margin:10px auto; font-size: 15px; font-weight: bold; text-align: center">
                <?php _e('Current button layout on website', 'advanced-content-pagination'); ?>
            </div>

            <div class="acp_button_layout">                    
                <style type="text/css">

                    .acp_button_layout {
                        border: <?php echo $this->optionsSerialized->acp_buttons_border_css; ?>;
                        background: <?php echo $this->optionsSerialized->acp_buttons_background_css; ?>;
                        color: <?php echo $this->optionsSerialized->acp_buttons_text_color_css; ?> !important;
                        margin: 25px auto;
                        width: 200px;
                        text-align: center;
                        overflow: hidden;
                        height: 50px;
                    }

                    .acp_button_number {
                        width: 50px;
                        height: 50px;
                        float: left;
                        margin-right: 3px;
                        background-color: #777;
                        font-family: arial;
                        font-weight: bold;
                        line-height: 50px;
                        color: #fff;
                        font-size: 16px;
                    }

                    .acp_button_title {
                        font-size: <?php echo $this->optionsSerialized->acp_buttons_title_size_css; ?>;
                        overflow: hidden;
                    }

                    .align_left {
                        width: 50px;
                        height: 50px;
                    }
                </style>
                <?php if ($button_style_2) { ?>
                    <div class="acp_button_number">
                        <span class="align_left">1</span>
                    </div>
                <?php } ?>
                <div class="acp_button_title"></div>                        
            </div>
        </div>
    </div>
</div>