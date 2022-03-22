<?php
ini_set("session.cookie_httponly", 1);

session_start();


$mysqli = new mysqli('localhost', 'chanlee20', 'wlwl5909', 'module5');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

?>