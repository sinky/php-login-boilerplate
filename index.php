<?php
include("_auth.php");

// Login with rememberme cookie (optional)
$auth->rememberMeLogin();

// if not logged in, redirect to login page (functions: is_user_logged_in and redirect_login)
$auth->login_check();

// return login state true/false
if($auth->is_user_logged_in()) {
  echo "<p>User logged in</p>";
}

// return login url
echo '<p><a href="'.$auth->get_login_url().'">to Login Page</a></p>';

// return logout url
echo '<p><a href="'.$auth->get_logout_url().'">Logout</a></p>';