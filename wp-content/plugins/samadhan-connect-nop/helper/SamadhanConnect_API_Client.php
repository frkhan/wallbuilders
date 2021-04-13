<?php
/**
 * Connect API Client Class
 *
 * @author Fazlur Khan
 */
//namespace Samadhan
class Connect_API_Client {

    /**
     * API base endpoint
     */

    const API_ENDPOINT = 'api/';
    /**
     * The HASH alorithm to use for oAuth signature, SHA256 or SHA1
     */
    const HASH_ALGORITHM = 'SHA256';

    /**
     * The API URL
     * @var string
     */
    private $_api_url;

    /**
     * The WooCommerce Consumer Key
     * @var string
     */
    private $_consumer_key;

    /**
     * The WooCommerce Consumer Secret
     * @var string
     */
    private $_consumer_secret;

    /**
     * If the URL is secure, used to decide if oAuth or Basic Auth must be used
     * @var boolean
     */
    private $_is_ssl;

    /**
     * Return the API data as an Object, set to false to keep it in JSON string format
     * @var boolean
     */
    private $_return_as_object = true;

    /**
     * Default contructor
     * @param string  $consumer_key    The consumer key
     * @param string  $consumer_secret The consumer secret
     * @param string  $store_url       The URL to the WooCommerce store
     * @param boolean $is_ssl          If the URL is secure or not, optional
     */
    public function __construct( $consumer_key, $consumer_secret, $store_url, $is_ssl = false ) {
        if ( ! empty( $consumer_key ) && ! empty( $consumer_secret ) && ! empty( $store_url ) ) {
            $this->_api_url = (  rtrim($store_url,'/' ) . '/' ) . self::API_ENDPOINT;
            $this->set_consumer_key( $consumer_key );
            $this->set_consumer_secret( $consumer_secret );
            $this->set_is_ssl( $is_ssl );
        } else if ( ! isset( $consumer_key ) && ! isset( $consumer_secret ) ) {
            throw new Exception( 'Error: __construct() - Consumer Key / Consumer Secret missing.' );
        } else {
            throw new Exception( 'Error: __construct() - Store URL missing.' );
        }
    }

    /**
     * Get API Index
     * @return mixed|json string
     */
    public function get_index() {
        return $this->_make_api_call( '' );
    }

    /**
     * Create a customer
     * @param  array  $params
     * @return mixed|json string
     */
    public function create_MajorDonor( $params = array() ) {
        return $this->_make_api_call( 'MajorDonor/', $params ,'POST');
    }

    public function create_MajorDonor_History( $params = array() ) {
        return $this->_make_api_call( 'MajorDonorHistory/', $params ,'POST');
    }

    public function create_MajorDonor_Notes( $params = array() ) {
        return $this->_make_api_call( 'MajorDonorNotes/', $params ,'POST');
    }

    /**
     * Update a customer
     * @param  array  $params
     * @return mixed|json string
     */
    public function update_customer( $customer_id,$params = array() ) {
        return $this->_make_api_call( 'customers/'.$customer_id, $params ,'POST');
    }

    /**
     * Run a custom endpoint call, for when you extended the API with your own endpoints
     * @param  string $endpoint
     * @param  array  $params
     * @param  string $method
     * @return mixed|json string
     */
    public function make_custom_endpoint_call( $endpoint, $params = array(), $method = 'GET' ) {
        return $this->_make_api_call( $endpoint, $params, $method );
    }

    /**
     * Set the consumer key
     * @param string $consumer_key
     */
    public function set_consumer_key( $consumer_key ) {
        $this->_consumer_key = $consumer_key;
    }

    /**
     * Set the consumer secret
     * @param string $consumer_secret
     */
    public function set_consumer_secret( $consumer_secret ) {
        $this->_consumer_secret = $consumer_secret;
    }

    /**
     * Set SSL variable
     * @param boolean $is_ssl
     */
    public function set_is_ssl( $is_ssl ) {
        if ( $is_ssl == '' ) {
            if ( strtolower( substr( $this->_api_url, 0, 5 ) ) == 'https' ) {
                $this->_is_ssl = true;
            } else $this->_is_ssl = false;
        } else $this->_is_ssl = $is_ssl;
    }

    /**
     * Set the return data as object
     * @param boolean $is_object
     */
    public function set_return_as_object( $is_object = true ) {
        $this->_return_as_object = $is_object;
    }

    /**
     * Make the call to the API
     * @param  string $endpoint
     * @param  array  $params
     * @param  string $method
     * @return mixed|json string
     */
    private function _make_api_call( $endpoint, $params = array(), $method = 'GET' ) {
        // var_dump( $this->_consumer_key);
        //var_dump($params);


        $ch = curl_init();
        $header_params = [];

        // Check if we must use Basic Auth or 1 legged oAuth, if SSL we use basic, if not we use OAuth 1.0a one-legged
        if ( $this->_is_ssl ) {
            curl_setopt( $ch, CURLOPT_USERPWD, $this->_consumer_key . ":" . $this->_consumer_secret );
        } else {
            $header_params['oauth_consumer_key'] = $this->_consumer_key;
            $header_params['oauth_timestamp'] = time();
            $header_params['oauth_nonce'] = sha1( microtime() );
            $header_params['oauth_signature_method'] = 'HMAC-' . self::HASH_ALGORITHM;
            $header_params['oauth_signature'] = $this->generate_oauth_signature( $header_params, $method, $endpoint );

        }

        if ( isset( $params ) && is_array( $params ) && $method == 'GET') {
            if(isset($header_params) && strlen($header_params) > 0 ) {
                $paramString = '?' . http_build_query($header_params) . '&' . http_build_query($params);
            }
            else{
                $paramString = '?' . http_build_query($params);
            }
        }elseif ( isset( $params ) && is_array( $params ) && $method == 'POST') {
            $paramString = '?' .  http_build_query( $header_params );
        } else {
            $paramString = null;
        }


        // Set up the enpoint URL
        //curl_setopt( $ch, CURLOPT_URL, $this->_api_url . $endpoint . $paramString );
        $dev_endpoint = $this->_api_url . $endpoint ;
        //var_dump($dev_endpoint);
        curl_setopt( $ch, CURLOPT_URL, $dev_endpoint );
        /*
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        */

        if ( 'POST' === $method ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER,   array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_POST, true );
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $params ) );



           // $json_params = json_encode( $params );
           // var_dump($json_params);

            //curl_setopt( $ch, CURLOPT_POSTFIELDS,$json_params  );
        } else if ( 'DELETE' === $method ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
        }

       // var_dump($ch);
        $return = curl_exec( $ch );

       // var_dump($return);
        
        $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        if ( $this->_return_as_object ) {
            $return = json_decode( $return );
        }

        if ( empty( $return ) ) {
            $return = '{"errors":[{"code":"' . $code . '","message":"cURL HTTP error ' . $code . '"}]}';
            $return = json_decode( $return );
        }

        return $return;
    }

    /**
     * Generate oAuth signature
     * @param  array  $params
     * @param  string $http_method
     * @param  string $endpoint
     * @return string
     */
    public function generate_oauth_signature( $params, $http_method, $endpoint ) {
        $base_request_uri = rawurlencode( $this->_api_url . $endpoint );

        // normalize parameter key/values and sort them
        $params = $this->normalize_parameters( $params );
        uksort( $params, 'strcmp' );

        // form query string
        $query_params = array();
        foreach ( $params as $param_key => $param_value ) {
            $query_params[] = $param_key . '%3D' . $param_value; // join with equals sign
        }

        $query_string = implode( '%26', $query_params ); // join with ampersand

        // form string to sign (first key)
        $string_to_sign = $http_method . '&' . $base_request_uri . '&' . $query_string;

        return base64_encode( hash_hmac( self::HASH_ALGORITHM, $string_to_sign, $this->_consumer_secret, true ) );
    }

    /**
     * Normalize each parameter by assuming each parameter may have already been
     * encoded, so attempt to decode, and then re-encode according to RFC 3986
     *
     * Note both the key and value is normalized so a filter param like:
     *
     * 'filter[period]' => 'week'
     *
     * is encoded to:
     *
     * 'filter%5Bperiod%5D' => 'week'
     *
     * This conforms to the OAuth 1.0a spec which indicates the entire query string
     * should be URL encoded
     *
     * @since 0.3.1
     * @see rawurlencode()
     * @param array $parameters un-normalized pararmeters
     * @return array normalized parameters
     */
    private function normalize_parameters( $parameters ) {

        $normalized_parameters = array();

        foreach ( $parameters as $key => $value ) {

            // percent symbols (%) must be double-encoded
            $key   = str_replace( '%', '%25', rawurlencode( rawurldecode( $key ) ) );
            $value = str_replace( '%', '%25', rawurlencode( rawurldecode( $value ) ) );

            $normalized_parameters[ $key ] = $value;
        }

        return $normalized_parameters;
    }

}
