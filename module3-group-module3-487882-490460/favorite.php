<?php 
    session_destroy();
    session_start();
    require 'connect.php'; 

    // $_SESSION['title'] = $title;
    // $_SESSION['author'] = $author;
    // $_SESSION['author_id'] = $author_id;
    // $_SESSION['content'] = $content;
    // $_SESSION['link'] = $link;
    $post_id = $_SESSION['post_id'];
    $title = $_SESSION['title'];
    $author = $_SESSION['author'];
    $author_id = $_SESSION['author_id'];
    $content = $_SESSION['content'];
    $link = $_SESSION['link'];

    $stmt = $mysqli->prepare("insert into favorites(title, author, author_id, content, link, post_id) values (?,?,?,?,?,?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param("ssissi", $title, $author, $author_id, $content, $link, $post_id);
    $stmt->execute();
    $stmt->close();

    header("Location: homepage.php");
    
?>
