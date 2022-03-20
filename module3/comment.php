<?php
session_start();
require 'connect.php';



$username = $_SESSION['username'];
$comment = $_POST['comment'];
$user_id = $_SESSION['user_id'];
$post_id = $_SESSION['post_id'];
// echo $_SESSION['username'];
// echo $title;
// echo $content;
// echo $author_id;

$stmt = $mysqli->prepare("insert into comment_info(username, comment, user_id, post_id) values (?,?,?,?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param("ssii", $username, $comment, $user_id, $post_id);
$stmt->execute();
$stmt->close();

// echo $username;
// echo $comment;
// echo $user_id;
// echo $post_id;
if(isset($_SESSION['user_id'])){
header("Location: homepage.php");}
else{header("location: index.php");
}
// // break;
// echo $username;
// echo $title;
// echo $content;
// echo $author_id;

// $stmt = $mysqli->prepare("insert into post_info(title,author,author_id,content) values (?,?,?,?)");

// if(!$stmt){
// 	printf("Query Prep Failed: %s\n", $mysqli->error);
// 	exit;
// }

// $stmt->bind_param('ssis', $title,$username,$author_id,$content);
// $stmt->execute();
// $stmt->close();

// header("Location: index.php");
?>
