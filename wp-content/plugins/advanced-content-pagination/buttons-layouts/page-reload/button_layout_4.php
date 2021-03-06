<?php
if (!defined('ABSPATH')) {
    exit();
}
$prev_shortcode_title = $prev_shortcode_array['title'];
$prev_shortcode_link = $prev_shortcode_array['link'];
$prev_shortcode_url_page = $prev_shortcode_array['url_page_number'];
$prev_shortcode_title = $this->acp_button_text($prev_shortcode_title, 25);

$next_shortcode_title = $next_shortcode_array['title'];
$next_shortcode_link = $next_shortcode_array['link'];
$next_shortcode_url_page = $next_shortcode_array['url_page_number'];
$next_shortcode_title = $this->acp_button_text($next_shortcode_title, 25);

$current_shortcode_content = $current_shortcode_array['shortcode_content'];
$prevStyle = $prev_shortcode_url_page == count($shortcodes_array) ? 'display:none;' : '';
$nextStyle = $next_shortcode_url_page == 1 ? 'display:none;' : '';

$buttons = '<ul class="paging_btns" id="acp_paging_menu">';
$buttons .= '<li style="' . $prevStyle . '" class="acp_previous_page button_style" id="item' . $prev_shortcode_url_page . '">';
$buttons .= $prev_shortcode_link;
$buttons .= '<div class="acp_title" title="' . $prev_shortcode_title . '">' . $this->optionsSerialized->acpPhrasePrevious . '</div>';
$buttons .= '</a>';
$buttons .= '</li>';

$buttons .= '<li style="' . $nextStyle . '" class="acp_next_page button_style" id="item' . $next_shortcode_url_page . '">';
$buttons .= $next_shortcode_link;
$buttons .= '<div class="acp_title" title="' . $next_shortcode_title . '">' . $this->optionsSerialized->acpPhraseNext . '</div>';
$buttons .= '</a>';
$buttons .= '</li>';
$buttons .= '</ul>';

if ($acp_paging_buttons_location === 1) {
    $html .= '<div id="acp_wrapper" class="acp_wrapper">';
    $html .= $buttons;
    $html .= '<div id="acp_content" class="acp_content">' . $current_shortcode_content . '</div>';
    $html .= '</div>';
} else if ($acp_paging_buttons_location === 2) {
    $html .= '<div id="acp_wrapper" class="acp_wrapper">';
    $html .= '<div id="acp_content" class="acp_content">' . $current_shortcode_content . '</div>';
    $html .= $buttons;
    $html .= '</div>';
} else {
    $html .= '<div id="acp_wrapper" class="acp_wrapper">';
    $html .= $buttons;
    $html .= '<div id="acp_wrapper" id="acp_content" class="acp_content">' . $current_shortcode_content . '</div>';
    $html .= $buttons;
    $html .= '</div>';
}
?>