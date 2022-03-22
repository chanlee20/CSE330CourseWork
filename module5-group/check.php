<?php

    ini_set("session.cookie_httponly", 1);

    session_start();
    if(isset($_SESSION["token"])) {
        echo (json_encode(array(
            "success" => true, 
            "message" => "success",
            "username" => $_SESSION['username']
        )));
    }
    else{
        echo json_encode(array(
            "success" => false,
            "message" => "fail")
            );
    }
    

?>