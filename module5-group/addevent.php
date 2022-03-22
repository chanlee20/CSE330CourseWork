<?php 
ini_set("session.cookie_httponly", 1);

session_start();
require 'connect.php'; 




header("Content-Type: application/json");
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$eventtitle = $json_obj['eventtitle'];
$eventdate = $json_obj['eventdate'];
$eventtime = $json_obj['eventtime'];
$eventlocation = $json_obj['eventlocation'];
$event_author = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$token = $json_obj['token'];

if(!hash_equals($_SESSION['token'], $token)){
  echo json_encode(array(
    "success" => false,
    "message" => $token
  ));
  die("Request forgery detected");
}


$stmt = $mysqli->prepare("insert into events (title, event_loc, date, time, username,user_id) values (?,?,?,?,?,?)");
  
  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  
  
  $stmt->bind_param('ssssss', $eventtitle, $eventlocation, $eventdate, $eventtime, $event_author, $user_id);
  $stmt->execute();
  $stmt->close();
    
  echo json_encode(array("success" => true, "message" => "location is " + $eventlocation));
  exit;
  
  echo json_encode(array("success" => false, "message" => "Failed to create user"));
  exit;
?>
