<?php
session_destroy();
session_start();

$username = $_SESSION['username'];
$filesize = $_GET['sizeName'];

$path = sprintf("/srv/module2/%s/%s", $username, $filesize);

echo "File is: " ;
echo filesize($path);
echo " bytes";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File size</title>
</head>
<body>
    <form action = "homepage.php" method = "GET">
        <input type="submit" value="Return to homepage" /> <br> 
    </form>

</body>
</html>
