<?php

/**
 * Redirects non-logged in users to the home page
 * @return [type]           [description]
 */
function redirect_non_logged_in_users() {

    if ( ! is_home() && ! is_user_logged_in() ) {
    	
    	wp_redirect( home_url(), 301 );
    	exit;

    }

}

add_action( 'template_redirect', 'redirect_non_logged_in_users' );


function force_login( $template ) {

    if( is_home() && ! is_user_logged_in() ) {
    	
    	$login_page = locate_template( array( 'templates/login.php' ) );
    	
    	if ( $login_page ) {
    		return $login_page;
    	}

    }

    return $template;

}

add_action( 'template_include', 'force_login' );


function society_styles_and_scripts() {
	
	wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
	wp_enqueue_style( 'fjord-font', 'https://fonts.googleapis.com/css?family=Arvo' );
	wp_enqueue_style( 'bevan-font', 'https://fonts.googleapis.com/css?family=Bevan' );
	wp_enqueue_style( 'society-front', get_template_directory_uri() . '/css/society-front.css' );

	wp_enqueue_script( 'angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js', array('jquery'), '1.4.7', true );
	wp_enqueue_script( 'angular-resource', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-resource.min.js', array('jquery'), '1.4.7', true );
	wp_enqueue_script( 'angular-route', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-route.min.js', array('jquery'), '1.4.7', true );

	wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array('jquery'), '3.3.5', true );
	wp_enqueue_script( 'social', get_template_directory_uri() . '/js/social.js', array( 'jquery', 'angular-resource', 'angular-route' ), '0.1.0', true );
	wp_enqueue_script( 'society-front', get_template_directory_uri() . '/js/society.js', array('jquery'), '0.1.0', true );

	$local_vars = array(
		'views' => get_template_directory_uri() . '/views'
	);

	wp_localize_script( 'social', 'social', $local_vars );

}

add_action( 'wp_enqueue_scripts', 'society_styles_and_scripts' );

/**
 * Hide the admin bar on all front facing pages
 */
add_filter('show_admin_bar', '__return_false');


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


