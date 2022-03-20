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

    <a href = "post.php"> To Post a Story <br> </a>
    <p> Stories: <p>

    
    <!-- //<input type = "hidden" name = storyid value = <?php echo $story_id; ?>"" -->
    
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

    <p>Your Saved Stories:</p>
    <?php
         $stmt = $mysqli->prepare("select title, author,  post_id from favorites");
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
             echo '<a href="viewsaved.php?post_id='.$post_id.'"> '.$title.' by '.$author.' </a> <br>';
         }
    ?>
  
    <form action = "logout.php">
        <input type = "submit" value = "logout">
    </form>
</body>
</html>
