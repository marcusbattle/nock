<?php

class Nock_App_Proxy_API {

	protected static $single_instance = null;

	protected $nock_app_settings;
	protected $nock_api_endpoint;
	protected $nock_consumer_key;
	protected $nock_consumer_secret;

	static function init() {
		
		if ( self::$single_instance === null ) {
			self::$single_instance = new self();
		} 

		return self::$single_instance;

	}

	public function __construct() {

		$this->nock_app_settings = get_option( 'nock-app-settings', array() );
		$this->nock_api_endpoint = isset( $this->nock_app_settings['nock_api_endpoint'] ) ? untrailingslashit( $this->nock_app_settings['nock_api_endpoint'] ) : 'http://nock.battlebranding.com';
		$this->nock_consumer_key = isset( $this->nock_app_settings['nock_consumer_key'] ) ? sanitize_text_field( $this->nock_app_settings['nock_consumer_key'] ) : '';
		$this->nock_consumer_secret = isset( $this->nock_app_settings['nock_consumer_secret'] ) ? sanitize_text_field( $this->nock_app_settings['nock_consumer_secret'] ) : '';

	}

	public function hooks() {

		add_filter( 'json_url_prefix', array( $this, 'api_base_url_slug' ) );
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}

	public function api_base_url_slug( $slug ) {
		return 'api';
	}

	public function register_routes() {
		
		register_rest_route( 'nock-app/v1', '/login', array(
	        'methods' => 'POST',
	        'callback' => array( $this, 'POST_proxy_login' ),
	    ) );

		register_rest_route( 'nock-app/v1', '/networks', array(
	        'methods' => 'GET',
	        'callback' => array( $this, 'GET_proxy_networks' ),
	    ) );

		register_rest_route( 'nock-app/v1', '/statuses', array(
	        'methods' => 'GET',
	        'callback' => array( $this, 'GET_proxy_statuses' ),
	    ) );

		register_rest_route( 'social-api/v1', '/statuses/(?P<id>\d+)', array(
	        'methods' => 'GET',
	        'callback' => array( $this, 'GET_proxy_status' ),
	    ) );

	}

	public function POST_proxy_login( $data ) {
		
		$data = $this->get_json_post_data( $data );

		$params['oauth_consumer_key'] = $this->nock_consumer_key;
		$params['oauth_consumer_secret'] = $this->nock_consumer_secret;
		$params['x_auth_mode'] = 'client_auth';
		$params['x_auth_username'] = $data->get_param('username');
		$params['x_auth_password'] = $data->get_param('password');

		$args['body'] = $params;

		$response = $this->query_api( 'post', $this->nock_api_endpoint . '/wp-json/social-api/v1/oauth/access_token', $args );
		
		// Create the session for the user
		if ( isset( $response['access_token'] ) ) {
			
			$this->set_access_token( $response['access_token'] );

			return array( 'success' => true );

		}

		return array( 'success' => false );

	}

	public function GET_proxy_networks( $data ) {

		$data = $this->get_json_post_data( $data );

		$args = array(
			'body' => array(
				'access_token' => $this->get_access_token()
			)
		);

		$response = $this->query_api( 'get', $this->nock_api_endpoint . '/wp-json/social-api/v1/networks', $args );
		
		return $response;

	}

	public function GET_proxy_statuses( $data ) {

		$data = $this->get_json_post_data( $data );

		$params = array(
			'access_token' => $this->get_access_token()
		);
		
		$response = $this->query_api( 'get', $this->nock_api_endpoint . '/wp-json/social-api/v1/statuses?' . http_build_query( $params ) );

		return $response;

	}

	public function GET_proxy_status( $data ) {

		$data = $this->get_json_post_data( $data );

		$status_id = $data->get_param('id');

		$params = array(
			'access_token' => $this->get_access_token()
		);
		
		$response = $this->query_api( 'get', $this->nock_api_endpoint . "/wp-json/social-api/v1/statuses/{$status_id}?" . http_build_query( $params ) );

		return $response;

	}

	public function query_api( $method = 'get', $url, $args = array() ) {

		$remote_func = 'wp_remote_' . strtolower( $method );
		$result = $remote_func( $url, $args );
		
		if ( $result['response']['code'] !== 200 ) {
			return array( 'errors' => $result['response'] );
		}

		return json_decode( $result['body'], true );

	}

	public function get_json_post_data( $data ) {

		if ( array_filter( $data->get_params() ) ) {
			return $data;
		}
		
		// Get the post data for Angular JS POST/GET
		$request_data = file_get_contents("php://input");
		$request_data = json_decode( $request_data );

		if ( $request_data ) {

			foreach ( $request_data as $key => $value ) {
				$data->set_param( $key, $value );
			}

			return $data;

		}

		return false;

	}

	public function set_access_token( $access_token ) {

		setcookie( 'nock_access_token', $access_token, time() + 3600, site_url( '/', 'relative' ), '', false, false );

		return $this->get_access_token();

	}

	public function get_access_token() {

		if ( isset( $_COOKIE['nock_access_token'] ) ) {
			return $_COOKIE['nock_access_token'];
		}

		return false;

	}
	
}

add_action( 'after_setup_theme', array( Nock_App_Proxy_API::init(), 'hooks' ) );