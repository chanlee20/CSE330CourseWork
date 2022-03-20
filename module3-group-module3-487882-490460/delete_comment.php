<?php 
    session_start();

 

    require 'connect.php'; 



    $comment_id = htmlentities($_GET['comment_id']);
    // echo $comment_id;
    $stmt = $mysqli->prepare("delete from comment_info where comment_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $comment_id);
    $stmt->execute();
    $stmt->close();
    unset($_SESSION['comment_id']);
    echo $comment_id;
    header("Location: homepage.php");

?>
