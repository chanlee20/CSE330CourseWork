<?php


$mysqli = new mysqli('localhost', 'chanlee20', '', 'module3');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}


?>
