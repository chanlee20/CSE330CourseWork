<?php 

    session_start();
    require 'connect.php'; 
    
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }

    $comment = $_POST['comment'];
    $comment_id = $_SESSION['comment_id']; 
    $stmt = $mysqli->prepare("update comment_info set comment = '$comment' where comment_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $comment_id);
    $stmt->execute();
    $stmt->close();
    unset($_SESSION['comment_id']);
    header("Location: homepage.php");


?>