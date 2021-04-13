<?php
/**
 * Created by PhpStorm.
 * User: fazlur
 * Date: 6/11/18
 * Time: 1:39 PM
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}



class AdminSettings{

    public function __construct()
    {
        add_action('admin_menu',array($this,'sammadhan_store_consumer_setting_menu'));
        add_action( 'admin_init',array( $this,'samadhan_consumer_key_settings' ));
    }

    public function samadhan_consumer_key_settings() {
        add_option( 'SAMADHAN_STORE_CONSUMER_KEY', 'Enter Store Consumer Key');
        add_option( 'SAMADHAN_STORE_CONSUMER_SECRET', 'Enter Store Consumer Secret Key');
        add_option( 'SAMADHAN_STORE_API_ENDPOINT', 'Enter Store API Endpoint');


        register_setting( 'samadhan_consumer_key_form_group', 'SAMADHAN_STORE_CONSUMER_KEY', '' );
        register_setting( 'samadhan_consumer_key_form_group', 'SAMADHAN_STORE_CONSUMER_SECRET', '' );
        register_setting( 'samadhan_consumer_key_form_group', 'SAMADHAN_STORE_API_ENDPOINT', '' );


    }


    public function sammadhan_store_consumer_setting_menu()
    {
        add_menu_page('Samadhan Store Consumer ', 'SMDN Store Consumer Connect', 'manage_options', 'samadhan_consumer_setup', array($this,'samadhan_store_consumer_key_settings'),'','40');
        add_submenu_page( 'samadhan_consumer_setup', 'SMDN Store Consumer Key Settings', 'SMDN Store ConsumerKey Settings', 'manage_options', 'samadhan_consumer_setup',array($this,'samadhan_store_consumer_key_settings'));


    }



    public  function samadhan_store_consumer_key_settings(){
        ?>
        <div style=" background-color:#fff; margin-right:14px; margin-top:14px; padding-left:14px; border-left:  4px solid #00a0d2; border-top: 1px solid #00a0d2;border-right: 1px solid #00a0d2;border-bottom: 1px solid #00a0d2;">

            <form method="post" action="options.php">
                <?php settings_fields( 'samadhan_consumer_key_form_group' ); ?>
                <div>

                    <div >
                        <h3>Consumer Key Settings :</h3>

                    </div>
                </div>


                <table style="margin-left: 8%;">

                    <tr>
                        <th scope="row" style="width: auto%; text-align: left; "><label for="SAMADHAN_STORE_CONSUMER_KEY">Store Consumer Key : </label></th>
                        <td style="text-align: left; ">
                            <input style="height:35px;" type="text" size="80"  id="SAMADHAN_STORE_CONSUMER_KEY" name="SAMADHAN_STORE_CONSUMER_KEY" value="<?php echo esc_html( sprintf( __('%s', 'textdomain' ), get_option( 'SAMADHAN_STORE_CONSUMER_KEY' ) ) ); ?>" />
                        </td>
                    </tr>
                    <tr >
                        <th scope="row" style="width: auto%; text-align: left; "><label for="SAMADHAN_STORE_CONSUMER_SECRET">Store Consumer Secret Key : </label></th>
                        <td  style="text-align: left; ">
                            <input  style="height:35px;" type="text" size="80"  id="SAMADHAN_STORE_CONSUMER_SECRET" name="SAMADHAN_STORE_CONSUMER_SECRET"  value="<?php echo esc_html( sprintf( __('%s', 'textdomain' ), get_option( 'SAMADHAN_STORE_CONSUMER_SECRET' ) ) ); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="width: auto%; text-align: left; "><label for="SAMADHAN_STORE_API_ENDPOINT">Store API Endpoint : </label></th>
                        <td style="text-align: left; ">
                            <input style="height:35px;" type="text" size="80"  id="SAMADHAN_STORE_API_ENDPOINT" name="SAMADHAN_STORE_API_ENDPOINT" value="<?php echo esc_html( sprintf( __('%s', 'textdomain' ), get_option( 'SAMADHAN_STORE_API_ENDPOINT' ) ) ); ?>" />
                        </td>
                    </tr>

                </table>

                <?php  submit_button(); ?>
            </form>
        </div>
        <?php
    }



}

new AdminSettings();