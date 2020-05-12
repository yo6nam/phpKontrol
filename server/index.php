<?php
session_start();
include('dbcon.php');
if (isset($_POST['login']))	{
	$username	= filter_input(INPUT_POST, "user", FILTER_SANITIZE_STRING);
	$password	= md5($_POST['pass']);
	$user_id	= 0;
	$name		= '';
	$psq		= $con->prepare("SELECT user_id, username, password, name FROM users WHERE username=? AND password=? LIMIT 1");
	$psq->bind_param('ss', $username, $password);
	$psq->execute();
	$psq->bind_result($user_id, $username, $password, $name);
	$psq->store_result();
	if($psq->num_rows == 1) {
			$psq->fetch();
			$_SESSION['user_id'] = $user_id;
			header('location:control.php');
	} else {
		$warn = 'Invalid username or password.';
	}
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="web/style.css">
</head>
<body>
<div class="form-wrapper">
  <form action="" method="post">
    <div class="form-item">
		<input type="text" name="user" required="required" placeholder="User" autofocus required></input>
    </div>
    <div class="form-item">
		<input type="password" name="pass" required="required" placeholder="Password" required></input>
    </div>
    <div class="button-panel">
		<input type="submit" class="button" title="Log In" name="login" value="Login"></input>
    </div>
  </form>
  <div class="reminder">
    <?php echo (isset($warn)) ? $warn : ''; ?>
  </div>
</div>
</body>
</html>
