<?php
include("Auth/auth.class.php");

// Password == password_hash("bar", PASSWORD_DEFAULT) http://php.net/manual/de/function.password-hash.php

$auth = new Auth('foo', '$2y$10$lCBnfi8ZhGWqvDzEvK0hN./WQmL4sVpm8FDtBynSzYHhdnvvclgmC', array(
  'base_url' => 'http://192.168.56.1:81/PHP_Auth_Boilerplate', // no trailing slash
  'page_title' => 'Demo Page',
  'site_key' => '78936h234789598q34958q', // secret random hash, > 10 chars
  'site_timezone' => 'Europe/Berlin', // timezone for php:date_default_timezone_set
  'auth_prefix' => 'authLogin_demo', // used for cookie and session name
  'rememberme_expire' => '+25 Weeks', // php:strtotime format
  'login_url' => '/login.php' // relativ to base_url
));