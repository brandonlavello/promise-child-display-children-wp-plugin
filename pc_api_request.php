<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
class Pc_API_Request {

    private $api_url;

    public function __construct($url) {
        $this->api_url = $url;
    }    
    
    function __destruct() {
        // echo nl2br ("In Destructor\n");
    }

    public function get_data($query_type,$query_where,$query_order,$query_response_attributes) {

        //build Query string from query arguments

        $query_string = '{"query":"query {' . $query_type;
        $query_string .= '(where:{';

        foreach($query_where as $key => $value) {
        $query_string .= "$key:$value";
        }

        $query_string .= '} ';


        $query_string .= 'order:{ ';
            
        foreach($query_order as $key => $value) {
            $query_string .= "$key:$value";
        }

        $query_string .= '})';

        $query_string .= "{" . $query_response_attributes . "}";

        $query_string .= '}"}';

        $args = array(
        'headers' => array(
            'content-type' => 'application/json'
        ),
        'httpversion' => '1.1',
        'method' => 'POST',
        'body' => $query_string
        );
        $request = wp_remote_post( 'https://graphql.promisechild.org/graphql/', $args);

        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            return false;
        }

        //Handle received data --  decode JSON, extract child from results.
        // Then use Setters to set child obj variables to data from API
        // If key does not exist for child, set default "" value to prevent errors.
        $response = json_decode(wp_remote_retrieve_body($request),true);
        
        return $response;
    }
}
