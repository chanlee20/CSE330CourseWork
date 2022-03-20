<?php 

session_start();
require 'connect.php'; 


$og_pwd = $_POST['og_password'];
$new_pwd = $_POST['new_password'];
$username = $_POST['username'];

$stmt = $mysqli->prepare("select password from user_info where username = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();

$stmt->bind_result($inserted_pwd);
$stmt->fetch();
$stmt->close();

if(password_verify($og_pwd, $inserted_pwd)){
    
    $hash = password_hash($new_pwd, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("update user_info set password = '$hash' where username = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->close();

}
else {
    echo "invalid password match";
}

header("Location: index.php");


?>