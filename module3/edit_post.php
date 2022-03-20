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
    <form action = "edit_post_push.php" method = "POST"> 
        Title: <input type = "text" id = "title" name = "title"> <br>
        Content: <input type = "textarea" id = "content" name = "content"> <br>
        Link: <input type = "url" id = "url" name = "url"> <br>
        <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
        <input type = "submit" value = "submit">
    </form>
</body>
</html>