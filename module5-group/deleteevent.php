<?php
ini_set("session.cookie_httponly", 1);

session_start();

require 'connect.php';
header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$token = $json_obj['token'];

if(!hash_equals($_SESSION['token'], $token)){
  echo json_encode(array(
    "success" => false,
    "message" => $token
  ));
  die("Request forgery detected");
}

$eventnumber = $json_obj['eventnumber'];

$stmt = $mysqli->prepare("DELETE FROM events WHERE event_id=$eventnumber");

if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  echo json_encode(array("success" => false, "message" => "Failed to delete"));
exit;
  exit;
} else {
    $stmt->bind_param('d', $eventnumber);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array("success"=> true, "message" => "Successfully deleted"));
}
?>