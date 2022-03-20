<?php
session_destroy();
session_start();

$h = fopen("/srv/uploads/users.txt", "r");
$username = $_GET['username'];

while(!feof($h)){
    $name = trim(fgets($h));
    if($name == $username){
        header("location: homepage.php");
        $_SESSION['username'] = $username;
        exit;
    }
}
echo "invalid username";


fclose($h);
?>