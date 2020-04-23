<?php
session_start();
include('dbcon.php');
if (isset($_POST['login']))	{
	$username	= mysqli_real_escape_string($con, $_POST['user']);
	$password	= mysqli_real_escape_string($con, md5($_POST['pass']));
	$query 		= mysqli_query($con, "SELECT * FROM users WHERE password='$password' and username='$username'");
	$row		= mysqli_fetch_array($query);
	$num_row 	= mysqli_num_rows($query);
	if ($num_row > 0) {
		$_SESSION['user_id']=$row['user_id'];
		header('location:control.php');
	}else{
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
    <?php echo $warn ?>
  </div>
</div>
</body>
</html>
