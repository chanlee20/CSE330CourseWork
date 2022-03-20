<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chan/Liz News Forum Page</title>
</head>
<body>
    <?php 
        session_start();
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    ?>
    <h1> Chan/Liz News Forum</h1>
    <form action = "login.php" method = "POST">
        Login: <br>
        Username: <input type = "text" id = "username" name = "username"> <br>
        Password: <input type = "password" id = "password" name = "password"> <br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type = "submit" value = "submit">
    </form>
    <br>
    
    <form action = "register.php" method = "POST">
        Sign Up: <br>
        Username: <input type = "text" id = "username" name = "username"> <br>
        Password: <input type = "password" id = "password" name = "password"> <br>
        <input type = "submit" value = "submit"> <br> <br>
    </form>

    <form action = "edit_password.php" method = "POST">
        Reset Password: <br>
        Username: <input type = "text" id = "username" name = "username"> <br>
        Original Password: <input type = "password" id = "og_password" name = "og_password">
        New Password: <input type = "password" id = "new_password" name = "new_password"> <br>
        <input type = "submit" value = "submit"> <br> <br>
    </form>

    Stories: <br>

    <?php
    session_start();
    
    require 'connect.php';
    $stmt = $mysqli->prepare("select title, author,  post_id from post_info");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $title = $row["title"];
        $author = $row["author"];
        $post_id = $row["post_id"];
        echo '<a href="view.php?post_id= '.$post_id.'"> '.$title.' by '.$author.' </a> <br>';
    }
    
    ?>
</body>
</html>


