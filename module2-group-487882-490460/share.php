<?php
session_start();

// Get the filename and make sure it is valid
$filename = basename($_FILES['duplicate']['name']);
$shared_user = $_GET['shared_user'];

if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
	echo "Invalid filename";
	exit;
}

// Get the username and make sure it is valid
$username = $_SESSION['username'];

if( !preg_match('/^[\w_\-]+$/', $username) ){
	echo "Invalid username";
	exit;
}

$h = fopen("/srv/uploads/users.txt", "r");
$foo = FALSE;
while(!feof($h)){
    $name = trim(fgets($h));
    if($name == $shared_user){
        $foo = TRUE;
    }
    
}
if($foo == FALSE){
    echo "non-existent id";
}

$full_path = sprintf("/srv/module2/%s/%s", $shared_user, $filename);
if( move_uploaded_file($_FILES['duplicate']['tmp_name'], $full_path) ){
	header("Location: homepage.php");
	exit;
}
else{
	echo "Fail to upload";
	exit;
}

?>