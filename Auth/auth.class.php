<?php


class Auth {

  private $credentials;
  public $config;
  public $lang;
  public $cookieUsername;
  public $cookieHash;

  /*
  * Initialize
  * @param string $username
  * @param string $password (http://php.net/manual/de/function.password-hash.php)
  * @param array $config
  */
  public function __construct($username, $password, $config) {
    $defaultConfig = array(
      'base_url' => 'http://change_me.local', // no trailing slash
      'page_title' => 'Page Title',
      'site_key' => 'change_me_secret_hash', // secret random hash, > 10 chars
      'site_timezone' => 'Europe/Berlin', // timezone (http://php.net/manual/de/function.date-default-timezone-set.php)
      'auth_prefix' => 'authLogin', // used for cookie and session name
      'rememberme_expire' => '+25 Weeks', // strtotime (http://php.net/manual/en/function.strtotime.php)
      'login_url' => 'login.php' // relative to base_url
    );

    //$this->credentials = $credentials;
    $this->credentials = array("username" => $username, "password" => $password);
    $this->config = array_merge($defaultConfig, $config);
    $this->lang = $lang;

    $this->cookieUsername = $this->config['auth_prefix']."_username";
    $this->cookieHash = $this->config['auth_prefix']."_hash";

    if (version_compare(phpversion(), '5.5.0', '<')) {
      require("password_hash.php");
    }

    session_start();
    date_default_timezone_set($this->config['site_timezone']);

    if(strlen($this->config['site_key']) <= 10) {
      die("Error: Config param 'site_key' must be >10");
    }

  }


  /*
  * Logs a user in
  * @param string $username
  * @param string $password
  * @param bool $remember
  * @return array $return
  */
  public function login($username, $password, $remember = 0) {
    $return['error'] = true;

    if ($username != $this->credentials['username']) {
      $return['message'] = "Benutzername oder Kennwort falsch!";
      return $return;
    }

    if (!password_verify($password, $this->credentials['password'])) {
      $return['message'] = "Benutzername oder Kennwort falsch!";
      return $return;
    }

    setcookie($this->cookieUsername, $this->credentials['username'], strtotime("+ 1 year"));

    if($remember) {
      setcookie($this->cookieHash, $this->getHash(), strtotime($this->config['rememberme_expire']));
    }

    $_SESSION[$this->cookieHash] = $this->getHash();

    $return['error'] = false;
    $return['message'] = "Erfolgreich eingelogged.";

    return $return;
  }


  /*
  * Logout a user out
  * @return bool $return
  */
  public function logout($hash) {
    if($_SESSION[$this->cookieHash] == $hash) {
      session_destroy();

      if(isset($_COOKIE[$this->cookieHash])) {
        setcookie($this->cookieHash, "", time() - 3600);
      }
      return true;
    }
    return false;
  }


  /*
  * Login a user using remember me cookie
  * @return bool $return
  */
  public function rememberMeLogin() {
    if($this->getHash() == $_COOKIE[$this->cookieHash]) {
      $_SESSION[$this->cookieHash] = $this->getHash();
      return true;
    }
    return false;
  }


  /*
  * Returns Hash used for rememberme Login
  * @return bool $return
  */
  private function getHash() {
    return sha1($this->config['site_key'] . $this->credentials['username'] . $this->credentials['password']);
  }


  /* ----------------------------
  * Checks
  * ---------------------------- */

  /*
  * Checks if a user is loggedin
  * @return bool $return
  */
  public function is_user_logged_in() {
    if(isset($_SESSION[$this->cookieHash])) {
      if($this->getHash() == $_SESSION[$this->cookieHash]) {
        return true;
      }
    }
    return false;
  }

  /*
  * Check Login and redirect if needed
  * @return bool true OR http redirect
  */
  public function login_check() {
    if(!$this->is_user_logged_in()) {
      $this->redirect_login();
      exit;
    }
    return true;
  }



  /* ----------------------------
  * Get's
  * ---------------------------- */

  /*
  * Return URL to base page
  * @return string config base_url
  */
  public function get_base_url() {
    return $this->config['base_url'];
  }

  /*
  * Return URL to login page
  * @return string login url
  */
  public function get_login_url() {
    return $this->config['base_url'].$this->config['login_url'];
  }

  /*
  * Return URL to login page with logout parameter
  * @return logout url
  */
  public function get_logout_url() {
    return $this->config['base_url'].$this->config['login_url']."?logout=".$this->getHash();
    exit;
  }



  /* ----------------------------
  * Redirects
  * ---------------------------- */

  /*
  * Redirect to base_url
  */
  public function redirect_base_url() {
    header('location: ' . $this->config['base_url']);
    exit;
  }

  /*
  * Redirect to login page
  */
  public function redirect_login() {
    header('location: ' . $this->get_login_url());
    exit;
  }


}