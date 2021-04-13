<?php
namespace Samadhan;

use WC_Countries;
use WP_User;

class UserCRUD
{



    public static function is_unauthorized()
    {

        $user = wp_get_current_user();
        $allowed_roles = array( 'staff', 'administrator', 'shop_manager' );
        if ( array_intersect( $allowed_roles, $user->roles ) ) {
            return false;
        }

    }

    public static function unauthorized_message(){

        return "<div style='text-align: center'><h2>You are not authorized!</h2></div>";
    }

    // ******************* User CRUD  ***********************//


    public  static function save_user_data($userdata,$userMetaData,$userRoll=''){

        if (!email_exists($userdata['user_email'])) {

               $register_user_id = wp_insert_user($userdata);
                $get_user_role = new WP_User( $register_user_id );
                $get_user_role->add_role($userRoll);
                wp_set_password( $userdata['user_email'], $register_user_id );

                foreach ($userMetaData as $metaKey=>$metaValue){
                    add_user_meta($register_user_id,$metaKey,$metaValue);
                }
                if($register_user_id){
                    $message="Successfully Added";
                }

        }else{
                $message="Already User Email Exists ";
        }

        return $message;
    }

    public  static function get_all_user_list_by_user_role($user_role,$search='',$page='',$perPage=''){
        global $wpdb;

               $results= $wpdb->get_results("SELECT
                            u1.ID,
                            u1.user_email
                        FROM wp_users u1
                        INNER JOIN wp_usermeta m8 ON (m8.user_id = u1.id AND m8.meta_key = '{$wpdb->prefix}capabilities')
            
                        WHERE
                             u1.user_email like'%$search%'
                             and  m8.meta_value like'%$user_role%'
                             ORDER BY  u1.ID DESC 
                        LIMIT {$page},{$perPage} 
                        
                        ");
               $tatalCount= $wpdb->get_results("select * from {$wpdb->base_prefix}users u join {$wpdb->base_prefix}usermeta um on u.id=um.user_id where um.meta_key='{$wpdb->prefix}capabilities' and um.meta_value like'%$user_role%' ");

        return array('results'=>$results,'total'=>count($tatalCount));
    }

    public  static function edit_user_data($user_id){
        global $wpdb;
        $results= $wpdb->query("select * from {$wpdb->base_prefix}users u join {$wpdb->base_prefix}usermeta um on u.id=um.user_id where um.meta_key='{$wpdb->prefix}capabilities' and um.user_id=$user_id");
        return $results;
    }

    public  static function delete_user_data($user_id){
        global $wpdb;

         $results= $wpdb->query("delete from {$wpdb->base_prefix}users  where ID=$user_id");
                   $wpdb->query("delete from  {$wpdb->base_prefix}usermeta where user_id=$user_id");
        return $results;

    }

    public  static function update_user_data($user_id,$userMetaData){

        global $wpdb;

        if (email_exists($user_id['user_email'])) {

            $register_user_id = wp_update_user($user_id);
            $get_user_role = new WP_User( $register_user_id );

            wp_set_password( $user_id['user_email'], $register_user_id );

            foreach ($userMetaData as $metaKey=>$metaValue){
                update_user_meta($register_user_id,$metaKey,$metaValue);
            }
            if($register_user_id){
                $message="Updated Successfully";
            }

        }else{
            $message="Already User Updated ";
        }

        return $message;



    }

    public static  function samadhan_get_country_states()
    {

        $WC_Countries = new WC_Countries();
        $states= $WC_Countries->get_states( 'US' );
        // var_dump($states);
        return   rest_ensure_response(array('states'=>$states));

    }


}

new UserCRUD();
