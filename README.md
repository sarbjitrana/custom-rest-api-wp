Custom REST APIs for user signup, login, logout, password reset, and token refresh in WordPress, you can follow these steps:

1. **User Signup Endpoint**: Create a custom endpoint to handle user registration.
2. **User Login Endpoint**: Implement a custom endpoint for user authentication and token generation.
3. **User Logout Endpoint**: Develop a custom endpoint to invalidate access tokens and log users out.
4. **Password Reset Endpoint**: Create a custom endpoint to handle password reset requests.
5. **Token Refresh Endpoint**: Implement a custom endpoint to refresh expired access tokens.

Here's a general outline of how you can implement each endpoint:

### 1. User Signup Endpoint:

```php
function custom_user_signup_endpoint() {
    // Handle POST request to create a new user account
    // Validate input fields such as username, email, and password
    // Use wp_create_user() or wp_insert_user() to create a new user
    // Return success or error response
}
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/signup', array(
        'methods' => 'POST',
        'callback' => 'custom_user_signup_endpoint',
    ));
});
```

### 2. User Login Endpoint:

```php
function custom_user_login_endpoint() {
    // Handle POST request to authenticate users and generate access tokens
    // Validate username/email and password
    // Use wp_signon() to authenticate user
    // Generate and return access token upon successful authentication
}
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/login', array(
        'methods' => 'POST',
        'callback' => 'custom_user_login_endpoint',
    ));
});
```

### 3. User Logout Endpoint:

```php
function custom_user_logout_endpoint() {
    // Handle POST request to invalidate access tokens and log users out
    // Perform token invalidation logic
    // Return success or error response
}
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/logout', array(
        'methods' => 'POST',
        'callback' => 'custom_user_logout_endpoint',
    ));
});
```

### 4. Password Reset Endpoint:

```php
function custom_password_reset_endpoint() {
    // Handle POST request to reset the user's password
    // Validate user input such as email/username and new password
    // Use wp_password_change_notification() to send password reset notification
    // Return success or error response
}
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/password-reset', array(
        'methods' => 'POST',
        'callback' => 'custom_password_reset_endpoint',
    ));
});
```

### 5. Token Refresh Endpoint:

```php
function custom_token_refresh_endpoint() {
    // Handle POST request to refresh expired access tokens
    // Validate refresh token and generate new access token
    // Return success or error response
}
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/token-refresh', array(
        'methods' => 'POST',
        'callback' => 'custom_token_refresh_endpoint',
    ));
});
```


### APIs Documentation

Here's a brief overview of the API documentation, showing how the endpoints integrate with WordPress:

#### User Signup Endpoint
```json
POST /wp-json/custom/v1/signup
{
    "username": "exampleuser",
    "email": "user@example.com",
    "password": "securepassword"
}
```
- **Response:**
  - `user_id`: The ID of the newly created user.

#### User Login Endpoint
```json
POST /wp-json/custom/v1/login
{
    "username": "exampleuser",
    "password": "securepassword"
}
```
- **Response:**
  - `token`: Access token for authenticated sessions.

#### User Logout Endpoint
```json
POST /wp-json/custom/v1/logout
```
- **Response:**
  - `message`: Confirmation message.

#### Password Reset Endpoint
```json
POST /wp-json/custom/v1/password-reset
{
    "username_or_email": "user@example.com"
}
```
- **Response:**
  - `message`: Password reset confirmation.

#### Token Refresh Endpoint
```json
POST /wp-json/custom/v1/token-refresh
{
    "token": "existingtoken"
}
```
- **Response:**
  - `token`: New access token.

