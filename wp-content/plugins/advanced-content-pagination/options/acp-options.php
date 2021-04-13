<?php
if (!defined('ABSPATH')) {
    exit();
}

class ACPOptions {

    private $optionsSerialized;

    public function __construct($optionsSerialized) {
        $this->optionsSerialized = $optionsSerialized;
    }

    /**
     * Builds options page
     */
    public function options_form() {

        if (isset($_POST['submit'])) {

            if (function_exists('current_user_can') && !current_user_can('manage_options')) {
                die(_e('Hacker?', 'advanced-content-pagination'));
            }

            if (function_exists('check_admin_referer')) {
                check_admin_referer('acp_options_form');
            }

            $this->optionsSerialized->acp_paging_on_off = isset($_POST['acp_paging_on_off']) ? $_POST['acp_paging_on_off'] : 0;
            $this->optionsSerialized->acp_wp_shortcode_pagination_view = $_POST['acp_wp_shortcode_pagination_view'];
            $this->optionsSerialized->acp_plugin_pagination_type = $_POST['acp_plugin_pagination_type'];
            $this->optionsSerialized->acp_paging_buttons_location = $_POST['acp_paging_buttons_location'];
            $this->optionsSerialized->acp_do_shortcodes_excerpts = isset($_POST['acp_do_shortcodes_excerpts']) ? $_POST['acp_do_shortcodes_excerpts'] : 1;
            $this->optionsSerialized->acp_excerpts_count = isset($_POST['acp_excerpts_count']) ? $_POST['acp_excerpts_count'] : 55;
            $this->optionsSerialized->acp_buttons_border_css = $_POST['acp_buttons_border_css'];
            $this->optionsSerialized->acp_buttons_background_css = $_POST['acp_buttons_background_css'];
            $this->optionsSerialized->acp_buttons_background_hover_css = $_POST['acp_buttons_background_hover_css'];
            $this->optionsSerialized->acp_buttons_font_css = $_POST['acp_buttons_font_css'];
            $this->optionsSerialized->acp_buttons_text_color_css = $_POST['acp_buttons_text_color_css'];
            $this->optionsSerialized->acp_buttons_title_size_css = $_POST['acp_buttons_title_size_css'];
            $this->optionsSerialized->acp_buttons_prev_next = isset($_POST['acp_buttons_prev_next']) ? $_POST['acp_buttons_prev_next'] : 0;
            $this->optionsSerialized->acp_buttons_visual_style = $_POST['acp_buttons_visual_style'];
            $this->optionsSerialized->acpPhrasePrevious = isset($_POST['acpPhrasePrevious']) && ($phrasePrev = trim($_POST['acpPhrasePrevious'])) ? $phrasePrev : __('Previous', 'advanced-content-pagination');
            $this->optionsSerialized->acpPhraseNext = isset($_POST['acpPhraseNext']) && ($phraseNext = trim($_POST['acpPhraseNext'])) ? $phraseNext : __('Next', 'advanced-content-pagination');
            $this->optionsSerialized->acp_buttons_hover_text_color = isset($_POST['acp_buttons_hover_text_color']) ? $_POST['acp_buttons_hover_text_color'] : '#000000';
            $this->optionsSerialized->acp_buttons_is_arrow_fixed = isset($_POST['acp_buttons_is_arrow_fixed']) ? $_POST['acp_buttons_is_arrow_fixed'] : '0';
            $this->optionsSerialized->acp_active_button_border_css = $_POST['acp_active_button_border_css'];
            $this->optionsSerialized->acp_active_button_background_css = $_POST['acp_active_button_background_css'];
            $this->optionsSerialized->acp_active_button_text_color_css = $_POST['acp_active_button_text_color_css'];
            $this->optionsSerialized->acp_load_container_css = isset($_POST['acp_load_container_css']) ? $_POST['acp_load_container_css'] : 'rgba(174,174,174,0.7)';
            $this->optionsSerialized->acp_custom_css = isset($_POST['acp_custom_css']) ? $_POST['acp_custom_css'] : '';
            $this->optionsSerialized->acp_jcarousel_wrapping = isset($_POST['acp_jcarousel_wrapping']) ? $_POST['acp_jcarousel_wrapping'] : 'circular';
            $this->optionsSerialized->acp_arrows_bg = isset($_POST['acp_arrows_bg']) ? $_POST['acp_arrows_bg'] : '#333333';
            $this->optionsSerialized->acp_arrows_hover_bg = isset($_POST['acp_arrows_hover_bg']) ? $_POST['acp_arrows_hover_bg'] : '#000000';
            $this->optionsSerialized->acp_arrows_color = isset($_POST['acp_arrows_color']) ? $_POST['acp_arrows_color'] : '#ffffff';
            $this->optionsSerialized->acp_arrows_hover_color = isset($_POST['acp_arrows_hover_color']) ? $_POST['acp_arrows_hover_color'] : '#ffffff';

            $this->optionsSerialized->updateOptions();
        }
        include_once 'html-options.php';
    }

}

?>