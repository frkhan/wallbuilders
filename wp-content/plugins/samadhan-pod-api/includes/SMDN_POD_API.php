<?php


class SMDN_POD_API
{

    function __construct()
    {
        add_action('rest_api_init',array($this,'samadhan_pod_api_init'));

    }

    function samadhan_pod_api_init()
    {

        /************ Get Function***************/
        register_rest_route( 'samadhan_pod/v1', '/get_post_meta', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'PODServiceLib::get_post_meta'
        ) );
		
		register_rest_route( 'samadhan_pod/v1', '/get_order_count', array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'PODServiceLib::get_order_count'
		) );

    }


}
new SMDN_POD_API();