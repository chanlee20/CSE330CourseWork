<?php
require 'connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("insert into user_info(username, password) values (?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $username, $hash);
$stmt->execute();
$stmt->close();

header("Location: index.php");

?>