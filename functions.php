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
	
	wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' );
	wp_enqueue_style( 'society-front', get_template_directory_uri() . '/css/society-front.css' );

	wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array(), '3.3.5', true );
	wp_enqueue_script( 'society-front', get_template_directory_uri() . '/js/society.js', array('jquery'), '0.1.0', true );

	$local_vars = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);

	wp_localize_script( 'society-front', 'society', $local_vars );

}

add_action( 'wp_enqueue_scripts', 'society_styles_and_scripts' );

/**
 * Hide the admin bar on all front facing pages
 */
add_filter('show_admin_bar', '__return_false');


function society_create_post() {

	if ( ! isset( $_POST['status'] ) ) {
		wp_send_json_error();
	}

	$status_args = array(
		'post_status' 	=> 'publish',
		'post_content' 	=> $_POST['status'],
		'post_type'		=> 'status'
	);

	$status_id = wp_insert_post( $status_args );

	if ( ! is_wp_error( $status_id ) ) {
		wp_send_json_success();
	}

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