<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>
<body>
    <form enctype="multipart/form-data" action="upload.php" method="POST">
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
           Choose a file to upload: <input name="uploadedfile" type="file" id="uploadfile_input" />
        </p>
        <p>
            <input type="submit" value="Upload File" />
        </p>
    </form>

    <form action = "view.php" method = "GET">
        The File to View: <input type = "text" name = "openFile" id = "openFile"> <br>
        <input type="submit" value="View File" /> <br> <br>
    </form>

    <form action = "delete.php" method = "GET">
        The File to Delete: <input type = "text" name = "delFile" id = "delFile"> <br>
        <input type="submit" value="Delete File" /> <br> <br>
    </form>

    <form action = "rename.php" method = "GET">
        Name of File to Rename: <input type = "text" name = "oldfilename" id = "oldfilename"> <br>
        Rename to: <input type = "text" name = "newfilename" id = "newfilename"> <br>
        <input type="submit" value="Rename" /> <br> <br>
    </form>

    <form action = "filesize.php" method = "GET">
        To check size of file, enter file name: <input type = "text" name = "sizeName" id = "sizeName"> <br>
        <input type="submit" value="Check Size" /> <br> <br>
    </form>

    <form enctype="multipart/form-data" action="share.php" method="POST">
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
           Choose a file to share: <input name="duplicate" type="file" id="duplicate" /> <br>
           To whom: <input name = "shared_user" type = "text" id = "shared_user"/>
        </p>
        <p>
            <input type="submit" value="Share File" />
        </p>
    </form>

    <form action = "login.html" method = "GET">
        <input type = "submit" value = "logout"/> <br>
    </form>

</body>
<?php 
session_start();

$username = $_SESSION['username'];
$list = array();

    if( !preg_match('/^[\w_\-]+$/', $username) ){   
        echo "Invalid username";
        exit;
    }
    echo $username; 
    echo "'s files: ";
    echo "<br>";
    $directory = sprintf("/srv/module2/%s", $username);

    if($h = opendir($directory)) {
        while(($f = readdir($h)) !== false){
            if($f == "." || $f == ".."){
                continue;
            }
            else {
            echo $f;
            echo "<br>";
            }
        }
        closedir($h);
    }

    
?>
</html>
