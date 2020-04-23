<?php
// Fill out the DB connection details
$con = mysqli_connect("localhost","user","pass","db");
if (mysqli_connect_errno()) {  echo "Failed to connect to MySQL: " . mysqli_connect_error(); }
?>
