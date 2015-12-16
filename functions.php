<?php


class Nock_App_Theme {

	protected $user_is_logged_in;

	protected static $single_instance = null;

	static function init() {
		
		if ( self::$single_instance === null ) {
			self::$single_instance = new self();
		} 

		return self::$single_instance;

	}

	public function __construct() {
		include_once 'includes/CMB2/init.php';
		include_once 'includes/proxy.php';
	}
	
	public function hooks() {

		add_action( 'admin_menu', array( $this, 'nock_app_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'init_styles_and_scripts' ) );
		add_action( 'template_redirect', array( $this, 'redirect_non_logged_in_users' ) );

		add_filter('show_admin_bar', '__return_false'); // Hide the admin bar on all front facing pages

		add_action( 'cmb2_admin_init', array( $this, 'nock_app_theme_metabox' ) );

	}

	public function init_styles_and_scripts() {
		
		wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
		wp_enqueue_style( 'fjord-font', 'https://fonts.googleapis.com/css?family=Arvo' );
		wp_enqueue_style( 'bevan-font', 'https://fonts.googleapis.com/css?family=Bevan' );
		wp_enqueue_style( 'societwy-front', get_template_directory_uri() . '/css/nock.css' );

		wp_enqueue_script( 'angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js', array('jquery'), '1.4.7', true );
		wp_enqueue_script( 'angular-resource', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-resource.min.js', array('jquery'), '1.4.7', true );
		wp_enqueue_script( 'angular-route', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-route.min.js', array('jquery'), '1.4.7', true );
		wp_enqueue_script( 'angular-cookies', 'https://code.angularjs.org/1.5.0-beta.2/angular-cookies.min.js', array('angular'), '1.5.0', true );

		wp_enqueue_script( 'nock', get_template_directory_uri() . '/js/nock.js', array( 'jquery', 'angular-resource', 'angular-route' ), '0.1.0', true );
		wp_enqueue_script( 'society-front', get_template_directory_uri() . '/js/society.js', array('jquery'), '0.1.0', true );

		$local_vars = array(
			'views' => get_template_directory_uri() . '/views',
		);

		wp_localize_script( 'nock', 'nock', $local_vars );

	}

	public function nock_app_settings() {
		add_options_page( 'Nock App', 'Nock App', 'manage_options', 'nock-app-settings', array( $this, 'nock_app_settings_page' ) );
	}

	public function nock_app_settings_page() {
		?>
		<div class="wrap cmb2-options-page nock-app-settings">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( '_nock_app_settings', 'nock-app-settings' ); ?>
		</div>
		<?php
	}

	public function nock_app_theme_metabox() {

		$prefix = '_nock_';

		$cmb = new_cmb2_box( array(
			'id'         => $prefix . 'app_settings',
			'hookup'     => false,
			'cmb_styles' => true,
			'show_on'    => array(
				'key'   => 'options-page',
				'value' => 'nock-app-settings'
			),
		) );

		$cmb->add_field( array(
			'name' => __( 'App Consumer Key', 'nock' ),
			'id'   => $prefix . 'consumer_key',
			'type' => 'text',
		) );

		$cmb->add_field( array(
			'name' => __( 'App Consumer Secret', 'nock' ),
			'id'   => $prefix . 'consumer_secret',
			'type' => 'text',
		) );

	}
	
	public function user_is_logged_in() {
		
		if ( isset( $_COOKIE['logged_in'] ) && isset( $_COOKIE['nock_access_token'] ) ) {
			return $_COOKIE['logged_in'];
		}

		return false;

	}

	/**
	 * Redirects non-logged in users to the home page
	 * @return [type]           [description]
	 */
	public function redirect_non_logged_in_users() {

		global $wp;

	    if ( ! is_home() ) {
	    	
	    	wp_redirect( site_url(), 301 );
	    	exit;

	    }

	}

}

add_action( 'after_setup_theme', array( Nock_App_Theme::init() , 'hooks' ) );

















function society_create_post() {

	$user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : get_current_user_id();
	$user = get_user_by( 'id', $user_id );

	if ( ! isset( $_POST['status'] ) ) {
		wp_send_json_error();
	}

	$status_args = array(
		'post_status' 	=> 'publish',
		'post_content' 	=> $_POST['status'],
		'post_type'		=> 'status'
	);

	$status_id = wp_insert_post( $status_args );

	if ( is_wp_error( $status_id ) ) {
		wp_send_json_error();
	}

	// upload any images
	$upload_dir = wp_upload_dir();
	$image_type = isset( $_POST['image']['type'] ) ? $_POST['image']['type'] : '';
	$image_data = isset( $_POST['image']['data'] ) ? str_replace( "data:{$image_type};base64,", '', $_POST['image']['data'] ) : '';
	$image_name = isset( $_POST['image']['name'] ) ? $_POST['image']['name'] : '';
	$image_modified = isset( $_POST['image']['modified'] ) && ! empty( $_POST['image']['modified'] ) ? $_POST['image']['modified'] : strtotime( date('Y-m-d H:i:s') );
	
	$filetype = wp_check_filetype( basename( $image_name ) );
	$image_name = $user->user_login . '.' . $image_modified . '.' . $filetype['ext'];
	
	$image_data = str_replace(' ', '+', $image_data );

	if ( $image_data && $image_name && $image_type ) {

		$image_path = trailingslashit( $upload_dir['path'] ) . $image_name;

		if ( ! file_exists( $image_path ) ) {

			$decoded_image = base64_decode( $image_data );
			file_put_contents( $image_path, $decoded_image );

		} 

		

		if ( $filetype ) {

			$attachment = array(
				'guid'           => $upload_dir['url'] . '/' . basename( $image_path ), 
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $image_path ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// Insert the attachment.
			$attachment_id = wp_insert_attachment( $attachment, $image_path, $status_id );

			// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $image_path );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );

		}

	}

	wp_send_json_success();

}

add_action( 'wp_ajax_post_status', 'society_create_post' );

function add_status_post_type() {
    
    $args = array(
      'public' => true,
      'label'  => 'Statuses'
    );

    register_post_type( 'status', $args );

}

add_action( 'init', 'add_status_post_type' );

function add_status_to_home( $query ) {
	
	if ( is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'status' ) );
	}

	return $query;
}

add_action( 'pre_get_posts', 'add_status_to_home' );
	
function redirect_after_login( $redirect_to, $request, $user ) {

	if ( $redirect_to == 'http://marcbook.local/social/wp-admin/' ) {
		$redirect_to = home_url();
	}

	return $redirect_to;

}

add_filter( 'login_redirect', 'redirect_after_login', 10, 3 );

function society_add_comment() {

	$status_id = isset( $_POST['status_id'] ) ? $_POST['status_id'] : 0;
	$comment = isset( $_POST['comment'] ) ? $_POST['comment'] : 0;
	$user_id = get_current_user_id();

	if ( ! $status_id ) {
		return false;
	}

	$data = array(
	    'comment_post_ID' => $status_id,
	    'comment_content' => $comment,
	    'comment_parent' => 0,
	    'user_id' => $user_id,
	    'comment_date' => $time,
	    'comment_approved' => 1,
	);

	$comment_id = wp_insert_comment( $data );

	if ( is_wp_error( $comment_id ) ) {
		wp_send_json_error();
	}

	wp_send_json_success();

}

add_action( 'wp_ajax_post_comment', 'society_add_comment' );

