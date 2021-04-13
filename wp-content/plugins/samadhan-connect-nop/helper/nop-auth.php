<?php


namespace Samadhan;

class  nopAuth
{
    function __construct() {
        add_shortcode('test_password',   array('Samadhan\nopAuth','sha_test'));
        add_filter('check_password',array('Samadhan\nopAuth','nop_check_password') ,10,4);
    }
    public static function sha_test()
    {
        $check = false;
        $password = 'learning';
        $hash = '*';
        $user_id = 78;// 10637
        $hash = nopAuth::get_nop_hash($user_id);
       var_dump($hash);
        if(isset($hash) ) {
            $stored_hash = $hash->NopPassword;
            $salted_password = $password . $hash->PasswordSalt;
            $nopHash = \strtoupper(\sha1($salted_password, false));
            var_dump( $nopHash );
            var_dump( $stored_hash);
        }
    }

    public static function nop_check_password($check, $password, $hash, $user_id)
    {
        if ($check == true) return $check;
        $hash = nopAuth::get_nop_hash($user_id);
        if(isset($hash) ) {
            $stored_hash = $hash->NopPassword;
            $salted_password = $password . $hash->PasswordSalt;
            $nopHash = \strtoupper(\sha1($salted_password, false));
            return $nopHash === $stored_hash;
        }
        return $check;
    }

    public static function get_nop_hash($user_id){

        global $wpdb;
        $the_user = get_user_by( 'id', $user_id );
        //echo $the_user->user_email;

        $nopHash = $wpdb->get_row( $wpdb->prepare("SELECT NopPassword,PasswordSalt FROM wp_nopCustomerPassword WHERE email = '%s' ORDER BY CreatedOnUtc DESC LIMIT 1 ", $the_user->user_email ));
        //var_dump($nopHash);
        return $nopHash;


    }
}

new \Samadhan\nopAuth();
