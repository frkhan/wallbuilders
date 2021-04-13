<?php

class page_break {

    public function init_page_break() {
    
        add_action( 'admin_head', 'add_tinymce' );
                
        function page_break_css() {
            wp_enqueue_style('page_break_css', plugins_url('css/page-break-css.css', __FILE__));
        }

        add_action('admin_enqueue_scripts', 'page_break_css');
    
        //ADDS THE TINYMCE BUTTON
        function add_tinymce() {
            add_filter( "mce_external_plugins", "page_break_add_buttons" );
            add_filter( "mce_buttons", "page_break_register_buttons" );

        }
        
        function page_break_add_buttons( $plugin_array ) {
            $plugin_array['pagebreak'] = plugins_url("js/mce.js" , __file__);
            
            return $plugin_array;
        }
        function page_break_register_buttons( $buttons ) {
            array_push( $buttons, 'pagebreak' );
            return $buttons;
        }
        //end
    }
    
    
    //INITIALIZING THE BUTTON
    public function init() {
        add_action( 'init', array( $this, 'init_page_break' ) );
    }

}
