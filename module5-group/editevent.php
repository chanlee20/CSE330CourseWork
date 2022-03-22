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
$event_id = $json_obj['eventid'];
$newtitle = $json_obj['eventtitle'];
$newdate = $json_obj['eventdate'];
$newloc = $json_obj['eventlocation'];
$newtime = $json_obj['eventtime'];
$newtime = strval($newtime);
$newtime = str_replace( ":", "", $newtime);
$newtime = intval($newtime);


$stmt = $mysqli->prepare("UPDATE events SET title = '$newtitle', event_loc = '$newloc', date= '$newdate', time=$newtime WHERE event_id= ?");


    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array("success"=> true, "message" => "Successfully edited"));
    exit;



?>