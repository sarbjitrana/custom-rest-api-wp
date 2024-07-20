<?php
/**
 * Plugin Name:     Custom Rest Api
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     custom-rest-api
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Custom_Rest_Api
 */

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// User Signup Endpoint
function custom_user_signup_endpoint( $request ) {
    $parameters = $request->get_json_params();
    $username = sanitize_text_field( $parameters['username'] );
    $email = sanitize_email( $parameters['email'] );
    $password = sanitize_text_field( $parameters['password'] );

    if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
        return new WP_Error( 'missing_fields', 'Required fields are missing', array( 'status' => 400 ) );
    }

    if ( ! is_email( $email ) ) {
        return new WP_Error( 'invalid_email', 'Invalid email address', array( 'status' => 400 ) );
    }

    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        return $user_id;
    }

    return rest_ensure_response( array( 'user_id' => $user_id ) );
}

// User Login Endpoint
function custom_user_login_endpoint( $request ) {
    $parameters = $request->get_json_params();
    $username = sanitize_text_field( $parameters['username'] );
    $password = sanitize_text_field( $parameters['password'] );

    if ( empty( $username ) || empty( $password ) ) {
        return new WP_Error( 'missing_fields', 'Required fields are missing', array( 'status' => 400 ) );
    }

    $credentials = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true,
    );

    $user = wp_signon( $credentials );

    if ( is_wp_error( $user ) ) {
        return new WP_Error( 'invalid_credentials', 'Invalid username or password', array( 'status' => 403 ) );
    }

    // Generate access token (for simplicity, using user ID as token)
    $token = base64_encode( $user->ID );

    return rest_ensure_response( array( 'token' => $token ) );
}

// User Logout Endpoint
function custom_user_logout_endpoint( $request ) {
    // Invalidating tokens logic (for simplicity, just a message)
    return rest_ensure_response( array( 'message' => 'User logged out' ) );
}

// Password Reset Endpoint
function custom_password_reset_endpoint( $request ) {
    $parameters = $request->get_json_params();
    $username_or_email = sanitize_text_field( $parameters['username_or_email'] );

    if ( empty( $username_or_email ) ) {
        return new WP_Error( 'missing_fields', 'Required fields are missing', array( 'status' => 400 ) );
    }

    $user = get_user_by( 'email', $username_or_email );
    if ( ! $user ) {
        $user = get_user_by( 'login', $username_or_email );
    }

    if ( ! $user ) {
        return new WP_Error( 'user_not_found', 'User not found', array( 'status' => 404 ) );
    }

    // Generate a new password and notify the user
    $new_password = wp_generate_password();
    wp_set_password( $new_password, $user->ID );
    wp_password_change_notification( $user );

    return rest_ensure_response( array( 'message' => 'Password reset. Check your email.' ) );
}

// Token Refresh Endpoint
function custom_token_refresh_endpoint( $request ) {
    $parameters = $request->get_json_params();
    $token = sanitize_text_field( $parameters['token'] );

    if ( empty( $token ) ) {
        return new WP_Error( 'missing_fields', 'Required fields are missing', array( 'status' => 400 ) );
    }

    // For simplicity, decode token and assume it's valid
    $user_id = base64_decode( $token );

    if ( ! $user_id || ! get_user_by( 'id', $user_id ) ) {
        return new WP_Error( 'invalid_token', 'Invalid token', array( 'status' => 403 ) );
    }

    // Generate a new token
    $new_token = base64_encode( $user_id );

    return rest_ensure_response( array( 'token' => $new_token ) );
}

// Register custom endpoints
add_action( 'rest_api_init', function () {
    register_rest_route( 'custom/v1', '/signup', array(
        'methods' => 'POST',
        'callback' => 'custom_user_signup_endpoint',
    ));

    register_rest_route( 'custom/v1', '/login', array(
        'methods' => 'POST',
        'callback' => 'custom_user_login_endpoint',
    ));

    register_rest_route( 'custom/v1', '/logout', array(
        'methods' => 'POST',
        'callback' => 'custom_user_logout_endpoint',
    ));

    register_rest_route( 'custom/v1', '/password-reset', array(
        'methods' => 'POST',
        'callback' => 'custom_password_reset_endpoint',
    ));

    register_rest_route( 'custom/v1', '/token-refresh', array(
        'methods' => 'POST',
        'callback' => 'custom_token_refresh_endpoint',
    ));
});

