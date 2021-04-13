<?php

/*
 * Plugin Name: Pagination For Posts
 * Description: Creates fully customizable pagination buttons for post and page content with five different layouts
 * Version: 1.5.4
 * Author: gVectors Team (A. Chakhoyan, G. Zakaryan, H. Martirosyan)
 * Author URI: http://www.gvectors.com/
 * Plugin URI: http://www.gvectors.com/advanced-content-pagination/
 * Text Domain: advanced-content-pagination
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit();
}

define('ACP_DS', DIRECTORY_SEPARATOR);
define('ACP_DIR_PATH', dirname(__FILE__));
define('ACP_DIR_NAME', basename(ACP_DIR_PATH));


include_once 'acp-css.php';
include_once 'options' . ACP_DS . 'acp-options.php';
include_once 'options' . ACP_DS . 'acp-options-serialized.php';

class ACPCore {

    private $acpOptions;
    private $optionsSerialized;
    private $acpCss;
    private $shortcodeContent;
    private $pattern = '|\[nextpage[^\[\]]*\](.+?)\[/nextpage\]|is';
    private $openPattern = '|\[nextpage[^\[\]]*\]|is';
    private $page;
    private $queryPage;
    private $currentPage = 0;
    private $html_text; // used in button layouts do not remove
    private $loadingType;
    private $shorcodesArray = array();

    public function __construct() {
        add_action('plugins_loaded', array(&$this, 'load_acp_text_domain'));
        $this->optionsSerialized = new ACPOptionsSerialized();
        $this->acpOptions = new ACPOptions($this->optionsSerialized);
        $this->acpCss = new ACPFrontendStyle($this->optionsSerialized);
        $this->loadingType = intval($this->optionsSerialized->acp_plugin_pagination_type);

        add_action('init', array(&$this, 'add_buttons_and_ext_plugin'));
        add_action('admin_footer', array(&$this, 'add_dialog'));

        add_action('admin_menu', array(&$this, 'add_plugin_options_page'), -125);
        add_action('admin_enqueue_scripts', array(&$this, 'admin_page_styles_scripts'));
        add_action('wp_enqueue_scripts', array(&$this->acpCss, 'frontend_styles'));
        add_action('wp_enqueue_scripts', array(&$this, 'front_end_styles_scripts'));
        add_filter('the_excerpt', array(&$this, 'do_nextpage_shortcode_in_excerpt'));

        add_action('add_meta_boxes', array($this, 'add_acp_meta_box'));
        add_action('save_post', array($this, 'save_acp_metadata'));

        add_action('wp_ajax_pp_with_ajax', array(&$this, 'pagination_with_ajax'));
        add_action('wp_ajax_nopriv_pp_with_ajax', array(&$this, 'pagination_with_ajax'));

        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin", array(&$this, 'acp_add_plugin_settings_link'));

        if (intval($this->optionsSerialized->acp_paging_on_off) === 1) {
            if (function_exists('add_shortcode')) {
                add_shortcode('nextpage', array(&$this, 'nextpage_shortcode'));
            }
        }
        // Add nextpage shortcode to the Visual Composer
        add_action('vc_before_init', array(&$this, 'acp_shordcode_to_vc'));
    }

    public function load_acp_text_domain() {
        load_plugin_textdomain('advanced-content-pagination', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Adds a box to the main column on the Post and Page edit screens.
     */
    public function add_acp_meta_box($post_type) {
        $post_types = apply_filters('acp_metabox_post_types', array('post', 'page'));
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                    'some_meta_box_name'
                    , __('ACP Settings', 'advanced-content-pagination')
                    , array($this, 'render_acp_meta_box_content')
                    , $post_type
                    , 'side'
                    , 'high'
            );
        }
    }

    /**
     * Prints the meta box content.
     */
    public function render_acp_meta_box_content($post) {
        wp_nonce_field('acp_inner_custom_box', 'acp_inner_custom_box_nonce');
        $acp_loading_type = esc_attr(get_post_meta($post->ID, '_acp_loading_type', true));
        $acp_button_style = esc_attr(get_post_meta($post->ID, '_acp_button_style', true));
        $this->acp_loading_type_metabox_html($acp_loading_type);
        $this->acp_button_style_metabox_html($acp_button_style);
    }

    /**
     * When the post is saved, saves our custom data.
     */
    public function save_acp_metadata($post_id) {
        if (!isset($_POST['acp_inner_custom_box_nonce']))
            return $post_id;
        $nonce = $_POST['acp_inner_custom_box_nonce'];
        if (!wp_verify_nonce($nonce, 'acp_inner_custom_box'))
            return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }
        $acp_button_style = sanitize_text_field($_POST['acp_button_style']);
        update_post_meta($post_id, '_acp_button_style', $acp_button_style);
        $acp_loading_type = sanitize_text_field($_POST['acp_loading_type']);
        update_post_meta($post_id, '_acp_loading_type', $acp_loading_type);
    }

    /*
     * return excerpt from shortcodes
     */

    public function do_nextpage_shortcode_in_excerpt($excerpt) {
        if (has_excerpt()) {
            return '<p>' . $excerpt . '</p>';
        }
        $excerpt = get_the_content();
        $excerpt_count = $this->optionsSerialized->acp_excerpts_count;
        if ($this->optionsSerialized->acp_do_shortcodes_excerpts == '2') {
            return do_shortcode(wp_trim_words($excerpt, $excerpt_count));
        } else {
            $excerpt = preg_replace($this->openPattern, '', $excerpt);
            $excerpt = str_replace('[/nextpage]', '', $excerpt);
            $excerpt = strip_shortcodes($excerpt);
            return '<p>' . wp_trim_words($excerpt, $excerpt_count) . '</p>';
        }
    }

    /**
     * Scripts and styles registration on administration pages
     */
    public function admin_page_styles_scripts() {
        wp_register_style('acp-cp-index-css', plugins_url(ACP_DIR_NAME . '/assets/third-party/colorpicker/css/index.css'));
        wp_enqueue_style('acp-cp-index-css');
        wp_register_style('acp-cp-compatibility-css', plugins_url(ACP_DIR_NAME . '/assets/third-party/colorpicker/css/compatibility.css'));
        wp_enqueue_style('acp-cp-compatibility-css');
        wp_register_script('acp-cp-colors-js', plugins_url(ACP_DIR_NAME . '/assets/third-party/colorpicker/js/colors.js'), array('jquery'));
        wp_enqueue_script('acp-cp-colors-js');
        wp_register_script('acp-cp-colorpicker-js', plugins_url(ACP_DIR_NAME . '/assets/third-party/colorpicker/js/jqColorPicker.min.js'), array('jquery'));
        wp_enqueue_script('acp-cp-colorpicker-js');
        wp_register_script('acp-cp-index-js', plugins_url(ACP_DIR_NAME . '/assets/third-party/colorpicker/js/index.js'), array('jquery'));
        wp_enqueue_script('acp-cp-index-js');
        wp_register_style('acp-easy-responsive-tabs-css', plugins_url(ACP_DIR_NAME . '/assets/third-party/easy-responsive-tabs/css/easy-responsive-tabs.min.css'), true);
        wp_enqueue_style('acp-easy-responsive-tabs-css');
        wp_register_script('acp-easy-responsive-tabs-js', plugins_url(ACP_DIR_NAME . '/assets/third-party/easy-responsive-tabs/js/easy-responsive-tabs.js'), array('jquery'), '1.0.0', true);
        wp_enqueue_script('acp-easy-responsive-tabs-js');
        wp_register_style('acp-options-css', plugins_url(ACP_DIR_NAME . '/assets/css/options.css'));
        wp_enqueue_style('acp-options-css');
        wp_register_script('acp-options-js', plugins_url(ACP_DIR_NAME . '/assets/js/options.js'), array('jquery'));
        wp_localize_script('acp-options-js', 'acpjs', array('options' => $this->getJsVars()));
        wp_enqueue_script('acp-options-js');
        wp_enqueue_media();
        wp_register_script('upload-window', plugins_url(ACP_DIR_NAME . '/assets/js/mediawindow.js'), array('jquery', 'media-upload', 'thickbox'));
        wp_enqueue_script('upload-window');
    }

    private function getJsVars() {
        $jsArr = array();
        $jsArr['button_img'] = plugins_url(ACP_DIR_NAME . '/assets/img/select.png');
        $jsArr['default_title'] = __('Please insert your text here!', 'advanced-content-pagination');
        $jsArr['dialog_title'] = __('Add New Page - Advanced Content Pagination Shortcode', 'advanced-content-pagination');
        $jsArr['msgConfirmResetOptions'] = __('Do you really want to reset options?', 'advanced-content-pagination');
        return $jsArr;
    }

    /**
     * Styles and scripts registration to use on front page
     */
    public function front_end_styles_scripts() {
        if (is_singular() || is_front_page()) {
            global $post;
            $loading_type = esc_attr(get_post_meta($post->ID, '_acp_loading_type', true));
            if ($loading_type) {
                $this->loadingType = intval($loading_type);
            }

            $btn_visual_style = intval($this->optionsSerialized->acp_buttons_visual_style);
            $current_post_button_style = esc_attr(get_post_meta($post->ID, '_acp_button_style', true));
            if ($current_post_button_style) {
                $btn_visual_style = intval($current_post_button_style);
            }
            wp_register_script('acp-frontend-js', plugins_url(ACP_DIR_NAME . '/assets/js/frontend.js'), array('jquery'));
            wp_enqueue_script('acp-frontend-js');
            if ($this->loadingType === 2) {
                wp_register_script('acpjs-js', plugins_url(ACP_DIR_NAME . '/assets/js/acpjs.js'), array('jquery'));
                wp_localize_script('acpjs-js', 'acpjs', array('url' => admin_url('admin-ajax.php'), 'options' => $this->getJsVars()));
                wp_enqueue_script('acpjs-js');
            }

            if ($this->optionsSerialized->acp_buttons_prev_next || $btn_visual_style === 4) {
                wp_register_style('acp-prevnext-css', plugins_url(ACP_DIR_NAME . '/assets/css/prevnext.min.css'));
                wp_enqueue_style('acp-prevnext-css');
                if ($this->loadingType === 2) {
                    wp_register_script('acp-prevnext-js', plugins_url(ACP_DIR_NAME . '/assets/js/prevnext.js'), array('jquery'));
                    wp_enqueue_script('acp-prevnext-js');
                }
            } else {
                wp_register_style('acp-jcarousel-css', plugins_url(ACP_DIR_NAME . '/assets/third-party/jcarousel/jcarousel.min.css'));
                wp_enqueue_style('acp-jcarousel-css');
                wp_register_script('acp-jcarousel-min-js', plugins_url(ACP_DIR_NAME . '/assets/third-party/jcarousel/jquery.jcarousel.min.js'), array('jquery'));
                wp_enqueue_script('acp-jcarousel-min-js');
                if ($this->optionsSerialized->acp_buttons_is_arrow_fixed) {
                    wp_register_script('acp-jcresp-fixed-js', plugins_url(ACP_DIR_NAME . '/assets/js/jcresp-fixed.js'), array('jquery'));
                    wp_localize_script('acp-jcresp-fixed-js', 'acpJcarousel', array('wrapType' => $this->optionsSerialized->acp_jcarousel_wrapping));
                    wp_enqueue_script('acp-jcresp-fixed-js');
                } else {
                    wp_enqueue_script('acp-jcresp-js', plugins_url(ACP_DIR_NAME . '/assets/js/jcresp.js'), array('jquery'));
                    wp_localize_script('acp-jcresp-js', 'acpJcarousel', array('wrapType' => $this->optionsSerialized->acp_jcarousel_wrapping));
                }
            }
        }
    }

    /**
     * register options page for plugin
     */
    public function add_plugin_options_page() {
        add_submenu_page('options-general.php', 'AP Pagination', 'AP Pagination', 'manage_options', 'acp_options', array(&$this->acpOptions, 'options_form'));
    }

    /**
     * the function which will be called every time on finding shortcode in post content
     * @global type $post the current post with pagination
     * @param type $atts the shortcode attributes
     * @param type $content the shortcode content
     * @return type generated html content
     */
    public function nextpage_shortcode($atts, $content) {

        if (is_singular() || is_front_page()) {
            global $post;
            $loading_type = esc_attr(get_post_meta($post->ID, '_acp_loading_type', true));
            if ($loading_type) {
                $this->loadingType = intval($loading_type);
            }
            $btn_visual_style = intval($this->optionsSerialized->acp_buttons_visual_style);
            $current_post_button_style = esc_attr(get_post_meta($post->ID, '_acp_button_style', true));
            if ($current_post_button_style) {
                $btn_visual_style = intval($current_post_button_style);
            }
            $pages_count;
            $this->queryPage = get_query_var('page') ? get_query_var('page') : 1;
            $this->page++;
            extract(shortcode_atts(array(
                'title' => __('Title', 'advanced-content-pagination')
                            ), $atts), EXTR_OVERWRITE);

            $link;
            $anchor = '';

            if ($this->page == 1) {
                $link = get_permalink();
            } else {
                $link = get_permalink() . '&page=' . $this->page;
            }

            $link = _wp_link_page($this->page);
            $pattern = '\[(\[?)(nextpage)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';

            $link = substr_replace($link, $anchor . '">', -2);
            if (preg_match_all('/' . $pattern . '/s', $post->post_content, $matches) && array_key_exists(2, $matches) && in_array('nextpage', $matches[2])) {
                $pages_count = 0;
                foreach ($matches[2] as $match) {
                    if ($match == 'nextpage') {
                        $pages_count++;
                    }
                }
            }
            $html = '';
            $active_item = '';

            if ($this->currentPage === $this->queryPage - 1) {
                $this->shortcodeContent = $content;
                $active_item = ' active';
                $link = '';
            }
            if ($btn_visual_style === 4) {
                $this->shorcodesArray[] = array(
                    'title' => $title,
                    'shortcode_content' => ($pages_count == 1) ? $content : $this->shortcodeContent,
                    'curr_page' => $this->currentPage,
                    'url_page_number' => $this->page,
                    'link' => $link
                );
                if ($pages_count === count($this->shorcodesArray)) {
                    $html = $this->build_only_prev_next_pagination_html($this->shorcodesArray, $this->loadingType);
                }
            } else {
                if (!$this->optionsSerialized->acp_buttons_prev_next) {
                    if ($pages_count == 1) {
                        $html = $this->build_pagination_html($this->currentPage, $pages_count, $active_item, $this->page, $link, trim($title), do_shortcode($content));
                    } else {
                        $html = $this->build_pagination_html($this->currentPage, $pages_count, $active_item, $this->page, $link, trim($title), do_shortcode($this->shortcodeContent));
                    }
                } else {
                    $this->shorcodesArray[] = array(
                        'title' => $title,
                        'shortcode_content' => ($pages_count == 1) ? $content : $this->shortcodeContent,
                        'curr_page' => $this->currentPage,
                        'url_page_number' => $this->page,
                        'link' => $link
                    );

                    if ($pages_count === count($this->shorcodesArray)) {
                        $html = $this->build_prev_next_pagination_html($this->shorcodesArray);
                    }
                }
            }
            $this->currentPage++;
            return $html;
        } else {
            return do_shortcode($content);
        }
    }

    /**
     * @param type $curr_page the i-th shortcode in post content
     * @param type $pages_count the shortcodes count in post content
     * @param type $active_item the active page 
     * @param type $page the i-th page
     * @param type $link the pages link
     * @param type $title the shortcode title attribute
     * @param type $shortcode_content the shortcode content
     * @return type HTML the generated html
     */
    private function build_pagination_html($curr_page, $pages_count, $active_item, $page, $link, $title, $shortcode_content) {
        $html = '';
        global $post;
        $loading_type = esc_attr(get_post_meta($post->ID, '_acp_loading_type', true));
        if ($loading_type) {
            $this->loadingType = intval($loading_type);
        }
        $btn_visual_style = intval($this->optionsSerialized->acp_buttons_visual_style);
        $current_post_button_style = esc_attr(get_post_meta($post->ID, '_acp_button_style', true));
        if ($current_post_button_style) {
            $btn_visual_style = intval($current_post_button_style);
        }
        $acp_wp_shortcode_pagination_view = intval($this->optionsSerialized->acp_wp_shortcode_pagination_view);

        $acp_paging_buttons_location = intval($this->optionsSerialized->acp_paging_buttons_location);


        if ($acp_wp_shortcode_pagination_view === 1) {
            $btn_visual_style = -1;
        }

        // check pagination loading type if 1 reload page else type = AJAX loading
        if ($this->loadingType === 1) {
            // ==================================================================================================             
            if ($btn_visual_style === 1) { // Buttons with only title
                include 'buttons-layouts/page-reload/button_layout_1.php';
            }
            // ================================================================================================== 
            else if ($btn_visual_style === 2) { // Buttons with page number and title
                include 'buttons-layouts/page-reload/button_layout_2.php';
            }
            // ================================================================================================== 
            else {
                include 'buttons-layouts/page-reload/button_layout_3.php';
            }
        }
        // =============================== Pagination HTML for AJAX ==================================== 
        else {
            // ==================================================================================================             
            if ($btn_visual_style === 1) { // Buttons with only title
                include 'buttons-layouts/ajax-load/button_layout_1_js.php';
            }
            // ================================================================================================== 
            else if ($btn_visual_style === 2) { // Buttons with page number and title
                include 'buttons-layouts/ajax-load/button_layout_2_js.php';
            }
            // ================================================================================================== 
            else { // Buttons with page number only
                include 'buttons-layouts/ajax-load/button_layout_3_js.php';
            }
        }
        return $html;
    }

    private function build_prev_next_pagination_html($shortcodes_array) {
        $html = '';
        $btn_visual_style = intval($this->optionsSerialized->acp_buttons_visual_style);
        $acp_wp_shortcode_pagination_view = intval($this->optionsSerialized->acp_wp_shortcode_pagination_view);
        $acp_paging_buttons_location = intval($this->optionsSerialized->acp_paging_buttons_location);

        global $post;
        $loading_type = esc_attr(get_post_meta($post->ID, '_acp_loading_type', true));
        if ($loading_type) {
            $this->loadingType = intval($loading_type);
        }
        $current_post_button_style = esc_attr(get_post_meta($post->ID, '_acp_button_style', true));
        if ($current_post_button_style) {
            $btn_visual_style = intval($current_post_button_style);
        }

        if ($acp_wp_shortcode_pagination_view === 1) {
            $btn_visual_style = -1;
        }

        // check pagination loading type if 1 reload page else type = AJAX loading
        if ($this->loadingType === 1) {


            $current_query_page = $this->queryPage - 1;

            if ($current_query_page == 0) {
                $prev = count($shortcodes_array) - 1;
                $next = 1;
            } else if ($current_query_page == count($shortcodes_array) - 1) {
                $prev = count($shortcodes_array) - 2;
                $next = 0;
            } else {
                $prev = $current_query_page - 1;
                $next = $current_query_page + 1;
            }

            $prev_shortcode_array = $shortcodes_array[$prev];
            $next_shortcode_array = $shortcodes_array[$next];
            $current_shortcode_array = $shortcodes_array[$current_query_page];

            // ================================================================================================== 
            if ($btn_visual_style === 1) {
                include 'buttons-layouts/page-reload/prev-next/button_layout_1.php';
            }
            // ================================================================================================== 
            else if ($btn_visual_style === 2) {
                include 'buttons-layouts/page-reload/prev-next/button_layout_2.php';
            }
            // ==================================================================================================  
            else {
                include 'buttons-layouts/page-reload/prev-next/button_layout_3.php';
            }
        }
        // =============================== Pagination HTML for AJAX ==================================== 
        else {
            // ================================================================================================== 
            if ($btn_visual_style === 1) {
                include 'buttons-layouts/ajax-load/prev-next/button_layout_1_js.php';
            }
            // ================================================================================================== 
            else if ($btn_visual_style === 2) {
                include 'buttons-layouts/ajax-load/prev-next/button_layout_2_js.php';
            }
            // ================================================================================================== 
            else {
                include 'buttons-layouts/ajax-load/prev-next/button_layout_3_js.php';
            }
        }
        return $html;
    }

    public function build_only_prev_next_pagination_html($shortcodes_array, $loading_type) {
        $html = '';
        $acp_paging_buttons_location = intval($this->optionsSerialized->acp_paging_buttons_location);

        if ($loading_type === 1) {
            $current_query_page = $this->queryPage - 1;
            if ($current_query_page == 0) {
                $prev = count($shortcodes_array) - 1;
                $next = 1;
            } else if ($current_query_page == count($shortcodes_array) - 1) {
                $prev = count($shortcodes_array) - 2;
                $next = 0;
            } else {
                $prev = $current_query_page - 1;
                $next = $current_query_page + 1;
            }

            $prev_shortcode_array = $shortcodes_array[$prev];
            $next_shortcode_array = $shortcodes_array[$next];
            $current_shortcode_array = $shortcodes_array[$current_query_page];
            include 'buttons-layouts/page-reload/button_layout_4.php';
        } else {
            include 'buttons-layouts/ajax-load/button_layout_4_js.php';
        }

        return $html;
    }

    /**
     * load shortcode content via ajax
     */
    public function pagination_with_ajax() {
        $response = __('No Content', 'advanced-content-pagination');
        if (isset($_POST['acp_pid']) && !empty($_POST['acp_pid'])) {
            $acp_pid = $_POST['acp_pid'];
            $acp_currpage = $_POST['acp_currpage'];
            $acp_shortcode = $_POST['acp_shortcode'];
            if ($acp_shortcode == 'acp_shortcode') {
                // advanced pagination shortcode
                $response = $this->acp_pagination_ajax($acp_pid, $acp_currpage);
            }
        }
        echo $response;
        exit;
    }

    /**
     * @param type $id the post id
     * @param type $curr_page the i-th page
     * @return type HTML 
     */
    public function acp_pagination_ajax($id, $curr_page) {
        $post = get_post($id);
        $content = $post->post_content;
        preg_match_all($this->pattern, $content, $matches, PREG_SET_ORDER);
        for ($i = 0; $i < count($matches); $i++) {
            $shortcode_content_array[] = $matches[$i][1];
        }
        $shortcode_content = $shortcode_content_array[$curr_page - 1];
        $shortcode_content = do_shortcode($shortcode_content);
        $shortcode_content = str_replace(']]>', ']]&gt;', $shortcode_content);
        return wpautop($shortcode_content);
    }

    /**
     * register editor plugin javascript if current user can edit posts      
     */
    function add_buttons_and_ext_plugin() {
        if ($this->optionsSerialized->acp_paging_on_off && $this->optionsSerialized->acp_wp_shortcode_pagination_view == 2) {
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }

            if (get_user_option('rich_editing') == 'true') {
                add_filter('mce_buttons', array(&$this, 'add_mce_buttons'), -1);
                add_filter('mce_external_plugins', array(&$this, 'add_mce_external_plugins'), -1);
            }
        }
    }

    /**
     * add button on wordpress post editor panel
     */
    function add_mce_buttons($buttons) {
        global $wp_version;
        if (version_compare($wp_version, '3.9', '>=')) {
            array_push($buttons, 'dialog');
        } else {
            array_push($buttons, '|', 'dialog_button');
        }
        return $buttons;
    }

    /**
     * register editor plugin
     * @return registered plugins array
     */
    function add_mce_external_plugins() {
        global $wp_version;
        if (version_compare($wp_version, '3.5', '>=') && version_compare($wp_version, '3.9', '<')) {
            $plugin_array['ACPPlugin'] = plugins_url(ACP_DIR_NAME . '/assets/js/tinymce/acp-tinymce_3.8.3.js');
        } elseif (version_compare($wp_version, '3.9', '>=') && version_compare($wp_version, '4.4', '<')) {
            $plugin_array['ACPPlugin'] = plugins_url(ACP_DIR_NAME . '/assets/js/tinymce/acp-tinymce_3.9.js');
        } elseif (version_compare($wp_version, '4.4', '>=')) {
            $plugin_array['ACPPlugin'] = plugins_url(ACP_DIR_NAME . '/assets/js/tinymce/acp-tinymce_4.4.js');
        }
        return $plugin_array;
    }

    /**
     * 
     * @param type string button title
     * @return formatted title
     */
    public function acp_button_text($title, $length = 55) {
        if (function_exists('mb_strlen') && function_exists('mb_substr') && function_exists('mb_internal_encoding')) {
            if (mb_strlen($title, mb_internal_encoding()) > $length) {
                $title = mb_substr($title, 0, $length, mb_internal_encoding()) . '...';
            }
        } else {
            if (strlen($title) > $length) {
                $title = substr($title, 0, $length) . '...';
            }
        }
        return $title;
    }

    /**
     * the dialog html to add shortcodes
     */
    function add_dialog() {
        // the layout with title and paging number
        $button_style_2 = $this->optionsSerialized->acp_buttons_visual_style == 2;
        include 'assets/dialog.php';
    }

    /**
     * generate html loading type metabox
     */
    private function acp_loading_type_metabox_html($acp_loading_type) {
        $loading_types = array(array('id' => 'acp_loading_type_reload', 'label' => __('Reload Page', 'advanced-content-pagination'), 'value' => '1'),
            array('id' => 'acp_loading_type_ajax', 'label' => __('Ajax', 'advanced-content-pagination'), 'value' => '2'));

        if (empty($acp_loading_type)) {
            $acp_loading_type = $this->loadingType;
        }

        $meta_html = '<h4>' . __('Page Loading Type', 'advanced-content-pagination') . '</h4>';
        foreach ($loading_types as $loading_type) {
            $checked = ($loading_type['value'] == $acp_loading_type) ? 'checked="checked"' : '';
            $meta_html .= '<input id="' . $loading_type['id'] . '" type="radio" value="' . $loading_type['value'] . '" ' . $checked . ' name="acp_loading_type" />';
            $meta_html .= '<label for="' . $loading_type['id'] . '">' . $loading_type['label'] . '</label>&nbsp;&nbsp;&nbsp;';
        }

        echo $meta_html;
    }

    /**
     * generate html button  style metabox
     */
    private function acp_button_style_metabox_html($acp_button_style) {
        $button_styles = array(array('id' => 'acp_button_style_title', 'label' => __('Title', 'advanced-content-pagination'), 'value' => '1'),
            array('id' => 'acp_button_style_title_number', 'label' => __('Title & Number', 'advanced-content-pagination'), 'value' => '2'),
            array('id' => 'acp_button_style_next_prev', 'label' => __('Prev & Next', 'advanced-content-pagination'), 'value' => '4'),
            array('id' => 'acp_button_style_number', 'label' => __('Number', 'advanced-content-pagination'), 'value' => '3'));

        if (empty($acp_button_style)) {
            $acp_button_style = intval($this->optionsSerialized->acp_buttons_visual_style);
        }

        $meta_html = '<h4>' . __('Pagination Button Layout', 'advanced-content-pagination') . '</h4>';
        foreach ($button_styles as $button_style) {
            $checked = ($button_style['value'] == $acp_button_style) ? 'checked="checked"' : '';
            $meta_html .= '<input id="' . $button_style['id'] . '" type="radio" value="' . $button_style['value'] . '" ' . $checked . ' name="acp_button_style" />';
            $meta_html .= '<label for="' . $button_style['id'] . '">' . $button_style['label'] . '</label><br/>';
        }

        echo $meta_html;
    }

    // Add settings link on plugin page
    public function acp_add_plugin_settings_link($links) {
        $settings_link = '<a href="' . admin_url() . 'admin.php?page=acp_options">' . __('Settings', 'default') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function acp_shordcode_to_vc() {
        vc_map(array(
            "name" => __("Advanced Post Pagination", 'advanced-content-pagination'),
            "base" => "nextpage",
            'description' => __("Splits long content to multiple pages", 'advanced-content-pagination'),
            'icon' => plugins_url(ACP_DIR_NAME . '/assets/img/web_site.png'),
            'show_settings_on_create' => true,
            "class" => "",
            "category" => __("Content", 'js_composer'),
            "params" => array(
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __("Pagination button title", 'advanced-content-pagination'),
                    "param_name" => "title",
                    "value" => '',
                    "description" => __("Enter your pagination button title.", 'advanced-content-pagination')
                ),
                array(
                    "type" => "textarea_html",
                    "class" => "",
                    "heading" => __("subPage content", 'advanced-content-pagination'),
                    "param_name" => "content",
                    "value" => '',
                    "description" => ''
                )
            )
        ));
    }

}

new ACPCore();
?>