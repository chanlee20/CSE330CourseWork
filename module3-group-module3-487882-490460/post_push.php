<?php
    session_start();

    require 'connect.php';

    

    $author = $_SESSION['username'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];
    $link = $_POST['url'];

    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    // echo $_SESSION['username'];
    // echo $title;
    // echo $content;
    // echo $author_id;
    $stmt = $mysqli->prepare("insert into post_info(title, author, author_id, content, link) values (?,?,?,?,?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param("ssiss", $title, $author, $author_id, $content, $link);
    $stmt->execute();
    $stmt->close();

    header("Location: homepage.php");
    
    ?>