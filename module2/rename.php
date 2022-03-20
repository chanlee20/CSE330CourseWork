<?php
session_destroy();
session_start();

$username = $_SESSION['username'];
$oldfilename  = $_GET['oldfilename'];
$newfilename = $_GET['newfilename'];

$oldpath = sprintf("/srv/module2/%s/%s", $username, $oldfilename);
$newpath = sprintf("/srv/module2/%s/%s", $username, $newfilename);

if (rename($oldpath,$newpath)) {
    header("Location: homepage.php");
	exit;

} else {
  echo "failed to rename";
  exit;
}


<<<<<<< HEAD
?>
=======
?>
>>>>>>> 9de35cec42646e1b59f8a1f440e6c2ae9699eb67
