<?php
include("_auth.php");

$message = '';

// Login with form data
if($_POST) {
  $login = $auth->login($_POST['username_'.md5($auth->config['site_key'])], $_POST['password_'.md5($auth->config['site_key'])], $_POST['remember_me']);

  if($login['error'] == true) {
    $message = $login['message'];
  } else {
    $auth->redirect_base_url();
  }
}

// Logout
if(!empty($_GET['logout'])) {
  $logout = $auth->logout($_GET['logout']);

  if($logout['error'] == true) {
    $message = $logout['message'];
  } else {
    // redirect needed because logout takes effect on next loaded page
    $auth->redirect_base_url();
  }
}

?>
<!doctype html>
<html lang="de-DE">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $auth->config['page_title']; ?> - Login</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <style>
body {
  background: #FCFCFC;
}

.wrapper {
  margin-top: 80px;
  margin-bottom: 80px;
  max-width: 380px;
  margin-left: auto;
  margin-right: auto;
}

.form-signin {
  padding: 35px 35px;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.form-signin .form-signin-heading {
  margin-top: 0;
}

.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 1em;
}

.form-signin .checkbox {
  font-weight: normal;
  padding-left: 20px;
}

.form-signin .form-control {
  position: relative;
  font-size: 16px;
  height: auto;
  padding: 10px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

.form-signin .form-control:focus {
  z-index: 2;
}

.form-signin input[type="text"] {
  margin-bottom: -1px;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 20px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.error {
  color: #E60000;
  font-weight: bold;
  margin: 1em 0;
}

.back {
  margin-top: 10px;
  margin-left: 1em;
}
  </style>
</head>
<body>

<div class="wrapper">
  <div class="form-signin">
    <h2 class="form-signin-heading"><?php echo $auth->config['page_title']; ?> <small>Login</small></h2>
  <?php if(!$auth->is_user_logged_in()) { ?>
    <form method="post" action="<?php echo $auth->get_login_url(); ?>">
      <input type="text" class="form-control" name="username_<?php echo md5($auth->config['site_key']); ?>" placeholder="Username" autofocus required />
      <input type="password" class="form-control" name="password_<?php echo md5($auth->config['site_key']); ?>" placeholder="Password" required />
      <label class="checkbox">
        <input type="checkbox" value="1" id="remember_me" name="remember_me" checked> Remember me
      </label>
      <p><button class="btn btn-lg btn-primary btn-block" type="submit" name="dologin">Login</button></p>
      <?php if(!empty($message)) { echo '<p class="error">'.$message.'</p>'; }; ?>
    </form>
  <?php }else{ ?>
    <a href="<?php echo $auth->get_logout_url(); ?>" class="btn btn-lg btn-primary btn-block">Logout</a>
  <?php }?>
  </div>
  <p class="back"><a href="<?php echo $auth->get_base_url(); ?>">&larr; Back to <?php echo $auth->config['page_title']; ?></a></p>
</div>

</body>
</html>