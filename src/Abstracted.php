<?php
namespace Kizilare\Api\Togglr;

class Abstracted
{
    const API_URL = 'https://www.toggl.com/api/v8/';

    protected $api_key;

    protected $workspace_id;

    final public function __construct( $apikey )
    {
        $this->api_key = $apikey;
    }

    public function setWorkspace( $workspace_id )
    {
        $this->workspace_id = $workspace_id;
    }

    /**
     * @param string $url
     * @param null   $data
     *
     * @return mixed
     */
    protected function requestApi( $url, $data = null )
    {
        $url = self::API_URL . $url;
        $curl_handler = curl_init( $url );
        curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl_handler, CURLOPT_SSL_VERIFYPEER, false );
        if ($data) {
            curl_setopt( $curl_handler, CURLOPT_POSTFIELDS, json_encode( $data ) );
        } else {
            curl_setopt( $curl_handler, CURLOPT_POST, 0 );
        }
        curl_setopt( $curl_handler, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
        curl_setopt( $curl_handler, CURLOPT_USERPWD, $this->api_key . ':api_token' );
        $response = curl_exec( $curl_handler );
        return json_decode( $response, true );
    }
}