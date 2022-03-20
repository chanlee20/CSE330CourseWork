<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chan/Liz News Forum Page</title>
</head>
<body>
    <h1> Chan/Liz News Forum</h1>

</body>
</html>
<?php
session_start(); 
require 'connect.php'; 
$post_id = htmlentities($_GET['post_id']);

$_SESSION['post_id'] = $post_id;

$stmt = $mysqli->prepare("select title, author, author_id, content, link from favorites where post_id =  ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i', $post_id);
$stmt->execute();

$stmt->bind_result($title, $author, $author_id, $content, $link);
$stmt->fetch();

$_SESSION['title'] = $title;
$_SESSION['author'] = $author;
$_SESSION['author_id'] = $author_id;
$_SESSION['content'] = $content;
$_SESSION['link'] = $link;


echo ' <h3> Title: '.$title.' by '.$author.' </h3> <br>';
echo ' <p> '.$content.' </p>';
echo ' <p> Link: '.$link.'</p>';




?>
