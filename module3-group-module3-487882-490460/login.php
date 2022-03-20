<?php 
session_start();
require 'connect.php'; 
$pass_guess = $_POST['password'];
$user_guess = $_POST['username'];


if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

$stmt = $mysqli->prepare("select username, password, user_id from user_info where username = ?");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $user_guess);
$stmt->execute();

$stmt->bind_result($username, $pwd_hash, $user_id);
$stmt->fetch();



if(password_verify($pass_guess, $pwd_hash)){
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $user_guess;
    header("Location: homepage.php");
}
else{
  
    echo "invalid login";
}


?>