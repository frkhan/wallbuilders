<?php
add_action('admin_init', ['Samadhan_AC_Meta_Box', 'custom_settings'] );
add_action('admin_menu', ['Samadhan_AC_Meta_Box', 'options_page']);

 abstract class Samadhan_AC_Meta_Box {


     public static function custom_settings() {
         add_option( 'smdn_scheduler', '6');
         add_option( 'smdn_ac_api_url', '');
         add_option( 'smdn_ac_api_key', '');
         /*
         add_option( 'SMDN_PCFFL_Monthly_Subscription_Product_ID', '0');
         add_option( 'SMDN_PCFFL_Complete_Product_ID', '0');
         add_option( 'SMDN_PCFFL_Monthly_Subscription_Plan_slug', 'pcffl-monthly-subscribers');
        */

         register_setting( 'samadhan_options_group', 'smdn_scheduler', '' );
         register_setting( 'samadhan_options_group', 'smdn_ac_api_url', '' );
         register_setting( 'samadhan_options_group', 'smdn_ac_api_key', '' );

         /*
         register_setting( 'samadhan_options_group', 'SMDN_PCFFL_Monthly_Subscription_Product_ID', '' );
         register_setting( 'samadhan_options_group', 'SMDN_PCFFL_Complete_Product_ID', '' );
         register_setting( 'samadhan_options_group', 'SMDN_PCFFL_Monthly_Subscription_Plan_slug', '' );
        */

     }


     public static function options_page() {
         add_options_page('Course Release Active Campaign Setting', 'Course Release', 'manage_options', 'samadhan_manage_options', ['Samadhan_AC_Meta_Box','samadhan_options_metabox']);
         add_submenu_page('samadhan-ac-settings','Course Release AC Setting','Setting','manage_options','samadhan_manage_options', ['Samadhan_AC_Meta_Box','samadhan_options_metabox']);
       //  add_action('admin_enqueue_scripts', ['Samadhan_AC_Meta_Box','meta_box_scripts']);
     }


     public static function samadhan_options_metabox(){
         ?>
         <div>
             <h3>Setup Schedule for Course Release and Active Campaign Connectivity</h3>
             <form method="post" action="options.php">
                 <?php settings_fields( 'samadhan_options_group' ); ?>

                 <table>
                     <tr valign="top">
                         <th scope="row" style="width: auto%; text-align: left; "><label for="smdn_scheduler">Active Campaign API url</label></th>
                         <td style="text-align: left; ">
                             <input type="text"  size="50" id="smdn_ac_api_url" name="smdn_ac_api_url" value="<?php echo esc_html( sprintf( __('%s', 'textdomain' ), get_option( 'smdn_ac_api_url', '' ))); ?>" >
                         </td>
                     </tr>
                     <tr valign="top">
                         <th scope="row" style="width: auto%; text-align: left; "><label for="smdn_scheduler">Active Campaign API key</label></th>
                         <td style="text-align: left; ">
                             <input type="text"  size="100" id="smdn_ac_api_key" name="smdn_ac_api_key" value="<?php echo esc_html( sprintf( __('%s', 'textdomain' ), get_option( 'smdn_ac_api_key', '' ))); ?>" >
                         </td>
                     </tr>
                     <tr valign="top">
                         <th scope="row" style="width: auto%; text-align: left; "><label for="smdn_scheduler">Auto Processing Scheduler (in minutes)    </label></th>
                         <td style="text-align: left; ">
                             <input type="text"  size="50" id="smdn_scheduler" name="smdn_scheduler" value="<?php echo esc_html( sprintf( __('%s', 'textdomain' ), get_option( 'smdn_scheduler', 6 ))); ?>" >
                         </td>
                     </tr>
                 </table>
                 <?php  submit_button(); ?>


             </form>
             <!--
             <hr/>
             <table>
                 <tr valign="top">
                     <td style="text-align: left; ">
                         <div>
                             <input type="button" id="fap-update-data" class="button button-primary" value="Manual Process Course Release Now">
                             <span id="btn-update-practitioner" class="spinner"></span>
                             <p id="update-practitioner-status" class="hidden"></p>
                         </div>
                     </td>
                 </tr>
             </table>
             -->
         </div>
         <?php
     }

     public static function meta_box_scripts()
     {
         // get current admin screen, or null
         $screen = get_current_screen();
         // verify admin screen object
         if (is_object($screen)) {
             // enqueue only for specific post types
             if ($screen->id == 'settings_page_samadhan_manage_options') {
                 // enqueue script
                 wp_enqueue_script('samadhan_meta_box_script', plugin_dir_url(__FILE__) . 'js/admin.js', ['jquery']);
                 // localize script, create a custom js object
                 wp_localize_script(
                     'samadhan_meta_box_script',
                     'samadhan_meta_box_obj',
                     [
                         'root'=>esc_url_raw(rest_url()),
                         'nonce'=>wp_create_nonce('wp_rest')
                     ]
                 );
             }
         }
     }





 }

