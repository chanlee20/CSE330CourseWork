<?php
require 'connect.php';
ini_set("session.cookie_httponly", 1);

session_start();

header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


$newuser = $json_obj['newuser'];
$newpass = $json_obj['newpass'];

$stmt = $mysqli->prepare("SELECT username FROM user_info WHERE username = ?");

if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}

$stmt->bind_param('s', $newuser);
$stmt->execute();
// $stmt->bind_result($newuser);
// $stmt->fetch();
$stmt->close();




$stmt = $mysqli->prepare("insert into user_info (username, password) values (?, ?)");

if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}

$hash = password_hash($newpass, PASSWORD_DEFAULT);

$stmt->bind_param('ss', $newuser, $hash);
$stmt->execute();
$stmt->close();
  
echo json_encode(array("success" => true, "message" => $newuser));
exit;

echo json_encode(array("success" => false, "message" => "Failed to create user"));
exit;

// header("Location: homepage.html");

?>