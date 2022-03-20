<?php 
    session_start();

    
    
    require 'connect.php'; 

   

    $post_id = $_SESSION['post_id'];  

    $stmt = $mysqli->prepare("delete from post_info where post_info.post_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $stmt->close();
    header("Location: homepage.php");
    
?>