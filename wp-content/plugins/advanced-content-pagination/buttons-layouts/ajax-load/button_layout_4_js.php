<?php

if (!defined('ABSPATH')) {
    exit();
}
$wrapper_html = '<div id="acp_wrapper" class="acp_wrapper">';
$end_wrapper_html = '</div><div style="display:none;"><input type="hidden" value="' . $this->optionsSerialized->acpPhraseNext . '" id="acp_only_next" /><input type="hidden" value="' . $this->optionsSerialized->acpPhrasePrevious . '" id="acp_only_prev" /></div>';
$wrapper_buttons = '<ul class="paging_btns" id="acp_paging_menu">';
$end_wrapper_buttons = '</ul>';
$loader_container = '<div class="loader_container"><img class="loader" src="' . plugins_url(ACP_DIR_NAME . '/assets/img/ajax-loader_200x200_trans_blue.gif') . '" /></div>';
$page_content = '<div id="acp_content" class="acp_content">' . $shortcodes_array[0]['shortcode_content'] . '</div><input id="acp_post" type="hidden" value="' . get_the_ID() . '" /><input id="acp_shortcode" type="hidden" value="acp_shortcode" />';
$buttons = '';

$pages_count = count($shortcodes_array);
foreach ($shortcodes_array as $shortcode_array) {
    $shortcode_title = $shortcode_array['title'];
    $curr_page = $shortcode_array['curr_page'];
    $page = $shortcode_array['url_page_number'];


    $title = $this->acp_button_text($shortcode_title, 25);


    if ($page == 2) {
        $next_button = '<li class="button_style acp_next_page only_prev_next item' . $page . '" id="item' . $page . '"><a href="' . '#' . $page . '"><div class="acp_title" title="' . $title . '">' . $this->optionsSerialized->acpPhraseNext . '</div></a></li>';
    } else if ($page == $pages_count) {
        $buttons .= '<li style="display:none;" class="button_style acp_previous_page only_prev_next item' . $page . '" id="item' . $page . '"><a href="' . '#' . $page . '"><div class="acp_title" title="' . $title . '">' . $this->optionsSerialized->acpPhrasePrevious . '</div></a></li>';
    } else {
        $buttons .= '<li class="button_style acp_default_invisible_btn_ajax item' . $page . '" id="item' . $page . '"><a href="' . '#' . $page . '"><div class="acp_title" title="' . $title . '">' . $title . '</div></a></li>';
    }
}

$html .= $wrapper_html;
if ($acp_paging_buttons_location === 1) {
    $html .= $wrapper_buttons . $buttons . $next_button . $end_wrapper_buttons . $loader_container . $page_content;
} else if ($acp_paging_buttons_location === 2) {
    $html .= $loader_container . $page_content . $wrapper_buttons . $buttons . $next_button . $end_wrapper_buttons;
} else {
    $html .= $wrapper_buttons . $buttons . $next_button . $end_wrapper_buttons . $loader_container . $page_content . $wrapper_buttons . $buttons . $next_button . $end_wrapper_buttons;
}
$html .= $end_wrapper_html;
?>