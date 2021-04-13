<?php
class PODServiceLib
{
    private static function authorized()
    {
        return true;

       // $consumer_key =  get_option('SAMADHAN_POD_API_KEY');
        /*
        $nonce ='';
        if ( isset( $_REQUEST['_wpnonce'] ) ) {
            $nonce = $_REQUEST['_wpnonce'];
        } elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
            $nonce = $_SERVER['HTTP_X_WP_NONCE'];
        }
        return wp_verify_nonce( $nonce, 'wp_rest' );
        */
    }

    /*******************Event Section***********************/

    public static function get_post_meta()
    {
        if (self::authorized()) {

            $blog_id = 3;
            //global $wpdb;

            //$postdata = file_get_contents("php://input");
            //$metaQuery = json_decode($postdata);

            $single = false;
            $post_id = $_REQUEST['post_id'];
            $key = $_REQUEST['key'];
            if (isset($_REQUEST['single'])) {
                $single = $_REQUEST['single'];
            }

            return rest_ensure_response(PODServiceLib::get_blog_meta($post_id, $key, $single , $blog_id));
            //return rest_ensure_response(get_post_meta($post_id, $key, $single));
        }
    }



    public static function get_blog_meta($post_id, $key, $single , $blog_id){
        global $wpdb;
        $table=$wpdb->get_blog_prefix(3) . 'postmeta';
        return $wpdb->get_var( $wpdb->prepare("SELECT meta_value  FROM $table where post_id = $post_id and meta_key='$key'"));
    }
	
	
	
	public static function get_order_count()
		{
		if (self::authorized()) {

		//$postdata = file_get_contents("php://input");
		//$metaQuery = json_decode($postdata);

		$from_date = $_REQUEST['from_date'];
		$to_date = $_REQUEST['to_date'];

		global $wpdb;
		
		$table_name=$wpdb->get_blog_prefix(3) . 'posts';
		$result = $wpdb->get_results( "SELECT * FROM $table_name where post_type='shop_order' and post_date BETWEEN '$from_date' and '$to_date';" );
		//$result = $wpdb->get_results( "SELECT * FROM wallbuilders.wp_posts where post_type='shop_order' and post_date BETWEEN '2021-01-31' and '2021-5-09'" );

		return rest_ensure_response(array("total"=>count($result)));
		}
		}
	
	
	
}

