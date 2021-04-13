<?php

namespace Samadhan;

class NewsletterAdd extends UserCRUD{

    public function __construct()
    {
        add_shortcode('smdn_signup_list','Samadhan\NewsletterAdd::get_contact_form_newsletter_list');
    }


    public static function get_contact_form_newsletter_list($atts){

        if(parent::is_unauthorized()){
            return parent::unauthorized_message();
        }

        $attribute = shortcode_atts( array(
            'role' => ' '
        ), $atts );

        $userRoll=$attribute['role'];

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;


        if(isset($_GET['perPage']) && !empty($_GET['perPage'])){
            $posts_per_page=$_GET['perPage'];
        }else{
            $posts_per_page=10;
        }



        if(isset($_POST['searchButton'] )){
            $searchName=sanitize_email($_POST['filterName']);
            $posts_per_page=$_POST['perPage'];
            $offset = 0;
            $getUsers=parent::get_all_user_list_by_user_role($userRoll,$searchName,$offset,$posts_per_page);

        }
        elseif (isset($_GET['user_id']) && !empty($_GET['user_id'])){
            $user_id=$_GET['user_id'];
            $getUsers= get_userdata($user_id );
            $userEmail=$getUsers->data->user_email;

            $message=parent::delete_user_data($user_id);
            if($message)
            {
                $show_message = $userEmail. ' '. 'Successfully Deleted';
            }
            else
            {
                $show_message = $userEmail . ' '.'Delete Unsuccessful';
            }
            $offset = ($paged - 1) * $posts_per_page;
            $getUsers=parent::get_all_user_list_by_user_role($userRoll,'',$offset,$posts_per_page);


        }
        else{
            $offset = ($paged - 1) * $posts_per_page;
            $getUsers=parent::get_all_user_list_by_user_role($userRoll,'',$offset,$posts_per_page);

        }

        /* if(isset($_GET['user_edit']) && !empty($_GET['user_edit'])){
             $user_id=$_GET['user_edit'];
             $message=EventFunctons::edit_user_data($user_id);

         }
        */

        $pagesList=[10,20,30,40,50];
        $pageOption='';
        foreach ($pagesList as $pageList){
            if($pageList==$posts_per_page){
                $selected="selected='selected";
            }else{
                $selected='';
            }
            $pageOption .="  <option $selected value='$pageList'>$pageList</option>";

        }


        $total=$getUsers['total'];
        $body='';
        $count_entity = 0;
        foreach ($getUsers['results'] as $user){
            $count_entity++;
            $body .=" <tr>
            <td >$user->user_email</td>
            <td>".get_user_meta($user->ID,'first_name',true)."</td>
            <td>".get_user_meta($user->ID,'last_name',true)."</td>
            <td>".get_user_meta($user->ID,'Title',true)."</td>
            <td>".get_user_meta($user->ID,'billing_address_1',true)."</td>
            <td>".get_user_meta($user->ID,'billing_address_2',true)."</td>
            <td>".get_user_meta($user->ID,'billing_city',true)."</td>
            <td>".get_user_meta($user->ID,'billing_state',true)."</td>
            <td>".get_user_meta($user->ID,'billing_postcode',true)."</td>
            <td>".get_user_meta($user->ID,'billing_phone',true)."</td>
            <td><a style='font-size: 25px;margin-right: 10px;' href=".home_url('/newsletter-edit/?user_id='.$user->ID)."><span class='btn btn-small btn-info '>&#9998;</span></a><a style='font-size: 25px' href=".home_url('/newsletter_list/?user_id='.$user->ID)."><span class='btn btn-small btn-danger'>&#10005;</span> </a></td>
        
            </tr>";
        }


        $outPut ="  
                                <h3 style='color: red'>$show_message</h3>
                                <form method='post' name='form' style='background: #0a4b78; float: right;width: 100%' >
                                    <div style='float: right'>
                                    <select name='perPage'>
                                    $pageOption
                                     </select>
                                    <input type='text' placeholder='Enter email' name='filterName' value='".$searchName."'>
                                    <input type='submit' id='searchButton' name='searchButton' value='Search'>
                                    </div>
                                </form>";
        $outPut .='
                    <table class="table table-hover">
                    <thead>
                    <tr style="background: #546666;color:#fff;">
                    <th >Email</th>
                    <th >First Name</th>
                    <th >Last Name</th>
                    <th >Title</th>
                    <th >Address Line 1</th>
                    <th >Address Line 2</th>
                    <th >City</th>
                    <th >State</th>
                    <th >Zip Code</th>
                    <th >Phone Number</th>
                    <th >Actions</th>
                    </tr>
                    </thead>
                    <tbody >
                    
                    '.$body.'
                    
                    </tbody>';
        $firstItemNo = ($paged-1)* $posts_per_page+1;
        $lastItemNo = $firstItemNo + $count_entity-1; // $firstItemNo + PageSize
        $totalEntries = $total;


        $totalPages = ceil($total / $posts_per_page);
        $big = 999999999;
        $pagination = paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var( 'paged' ) ),
            'total' =>  $totalPages,
            'add_args' => array(
                'perPage' => $posts_per_page,
            )
        ) );
        $outPut .="<tfoot><tr bgcolor='#d3d3d3'><td colspan='20'> Showing " .$firstItemNo  . " to " . $lastItemNo . " of " .$totalEntries. " entries  <span style='float: right;'>$pagination</span></td>
                    
                    </tr></tfoot></table>";
        return $outPut;
    }


}

new NewsletterAdd();