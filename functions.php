<?php

/**
 * Redirects non-logged in users to the home page
 * @param  [type] $template [description]
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
	wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array(), '3.3.5', true );
}

add_action( 'wp_enqueue_scripts', 'society_styles_and_scripts' );