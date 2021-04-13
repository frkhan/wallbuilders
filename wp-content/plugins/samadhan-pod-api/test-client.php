<?php

add_shortcode('samadhan_pod_api_get_post_meta_test', 'samadhan_pod_api_get_post_meta_test');
function samadhan_pod_api_get_post_meta_test()
{
    $test = samadhan_pod_api_get_post_meta(3458, "response_code", true);
    var_dump($test);
}



function samadhan_pod_api_get_post_meta_dummy( $post_id,$key, $single=false ) {


    $api_url = "https://store.wallbuilders.com/wp-json/samadhan_pod/v1/get_post_meta";
    $paramString ="?post_id=" .$post_id. "&key=" . $key ;
    if ($single == true )$paramString .= "&single=true";

    $ch = curl_init();

    $api_url_with_param = $api_url .  $paramString ;
    // Set up the enpoint URL
    curl_setopt( $ch, CURLOPT_URL, $api_url_with_param );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 300 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 300 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


    $return = curl_exec( $ch );
    $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

    if($code == 200) {
        $return = json_decode( $return );
    }
    else{
        $return = '{"errors":[{"code":"' . $code . '","message":"cURL HTTP error ' . $code . '"}]}';
        $return = json_decode( $return );
    }


    curl_close($ch);
    return $return;
}