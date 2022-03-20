<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
</head>
<body>
    
    <h1> Chan/Liz News Forum</h1>

    <form action = "post_push.php" method = "POST">
        Title: <input type = "text" id = "title" name = "title">
        Content: <input type = "textarea" id = "content" name = "content">
        Optional link: <input type = "url" name = "url" id = "url">
        <input type="hidden" name="token" value="<?php session_start(); echo $_SESSION['token'];?>" />
        <input type = "submit" value = "submit">
    </form>
    

    <form action = "logout.php">
        <input type = "submit" value = "logout">
    </form>
</body>
</html>