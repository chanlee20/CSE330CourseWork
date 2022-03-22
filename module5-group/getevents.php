<?php
require 'connect.php';
ini_set("session.cookie_httponly", 1);

session_start();

header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];



// getting events from mysql events table and putting them into an aray
$stmt = $mysqli->prepare("SELECT title, event_loc, date, time, username, event_id FROM events where user_id = ?");
if(!$stmt){
	echo json_encode(array(
        "success" => false,
        "message" => "failed"
    ));
    exit;
}
$stmt -> bind_param('i', $user_id);
$stmt->execute();

$stmt->bind_result($title, $eventlocation, $date, $time, $username, $event_id);

$events = array();
$titlea = array();
$datea = array();
$timea = array();
$usernamea = array();
$user_ida = array();
$event_ida = array();
$locationa = array();

while($stmt->fetch()){
    $event = array("eventtitle"=>$title, "eventlocation"=>$eventlocation, "eventdate"=>$date, "eventtime" => $time, "username" =>$username);
    array_push($events,$event);
    array_push($titlea,$title);
    array_push($locationa,$eventlocation);
    array_push($datea,$date);
    array_push($timea ,$time);
    array_push($event_ida ,$event_id);
    // array_push($username,$username);
    // array_push($user_id,$user_id);
    
}

// number of events
$alength = sizeof($events);

// echo json_encode(array(
//     "success" => false,
//     "message" => $alength
// ));
// exit;

// if(isset($_POST['viewevents'])) {
//     print_r($events);
//     print_r($eventtitle);
//     print_r($eventdate);
//     print_r($eventtime);

//     echo "array length = " + (string) $alength-1;
// }

echo json_encode(array("success" => true, "totalevents" => $alength, "data" => $events,"eventtitle"=>$titlea, "eventlocation" => $locationa, "eventdate"=>$datea, "eventtime" => $timea, "username" =>$usernamea, "event_id"=>$event_ida));
exit;

echo json_encode(array("success" => false, "message" => "Failure to get events"));
exit;

// PUT EVENT ONTO CALENDAR




?>