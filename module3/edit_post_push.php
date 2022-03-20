<?php 

    session_start();
    require 'connect.php'; 

    $title = $_POST['title'];
    $content = $_POST['content'];
    $link = $_POST['url'];
    $post_id = $_SESSION['post_id'];    

    $token_POST = $_POST['token'];
    $token_SESSION = $_SESSION['token'];


    if(!hash_equals($token_SESSION, $token_POST)){
        die("Request forgery detected");
    }
    
    $stmt = $mysqli->prepare("update post_info set title = '$title', content = '$content', link = '$link' where post_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $stmt->close();
    unset($_SESSION['post_id']);
    header("Location: homepage.php");


?>