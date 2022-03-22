<?php 
ini_set("session.cookie_httponly", 1);

session_start();

require 'connect.php'; 

header("Content-Type: application/json");
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


$user_guess = $json_obj['username'];
$pass_guess = $json_obj['password'];


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
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    echo json_encode(array(
        "success" => true,
        "message" => $_SESSION['token'],
        "token" => $_SESSION['token']
    ));
    exit;

}
else{
    echo json_encode(array(
        "success" => false,
        "message" => $user_guess
    ));
    exit;
}


?>