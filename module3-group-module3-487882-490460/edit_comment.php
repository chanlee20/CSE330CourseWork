<?php 
session_start();
require 'connect.php'; 
$comment_id = $_GET['comment_id'];
$_SESSION['comment_id'] = $comment_id;
?>

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
    <form action = "edit_comment_push.php" method = "POST"> 
        New Comment: <input type = "text" id = "comment" name = "comment"> <br>
        <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
        <input type = "submit" value = "submit">
    </form>
</body>
</html>