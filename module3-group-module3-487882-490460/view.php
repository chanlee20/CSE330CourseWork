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

$stmt = $mysqli->prepare("select title, author, author_id, content, link from post_info where post_id =  ?");
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

if(isset($_SESSION['username'])) {
    if($_SESSION['user_id'] == $author_id){
        echo '<a href = "delete_post.php"> Delete Post </a> <br>';
        echo '<a href = "edit_post.php"> Edit Post </a> <br>';

    }
    
}


?>


<h3>Comments:</h3>
<?php
session_start(); 
require 'connect.php'; 
$post_id = htmlentities($_GET['post_id']);

$_SESSION['post_id'] = $post_id;

$stmt = $mysqli->prepare("select comment, username, user_id, comment_id from comment_info where post_id =  ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $username = $row["username"];
    $user_id = $row["user_id"];
    $comment = $row["comment"];
    $comment_id = $row["comment_id"];
    echo ' <p> '.$username.': '.$comment.'</p>';
    if(isset($_SESSION['user_id'])) {
        if($_SESSION['user_id'] == $user_id){
            echo '<a href = "delete_comment.php?comment_id='.$comment_id.'"> Delete Comment </a> <br>';
            echo '<a href = "edit_comment.php?comment_id='.$comment_id.'"> Edit Comment </a> <br>';
        }
        
    }
}

?>


<form action = "comment.php" method = "POST">
        Comment here: <input type = "text" name = "comment" id = "comment"> <br>
        <input type="submit" value="Comment" /> <br>
</form>
<form action = "favorite.php" method = "POST">
        <input type="submit" value="Save Post" /> <br>
</form>
</html>
