<?php 
$first = $_GET['num1'];
$second = $_GET['num2'];
$op = $_GET['operation'];

if($first == "" || $second == ""){
   echo "Invalid Input. Check if you put both numbers correctly";
}
else {
    if($op == '+'){
        $result = $first + $second;
        echo "$first + $second = $result";
    }
    else if($op == '-'){
        $result = $first - $second;
        echo "$first - $second = $result";
    }
    else if($op == '*'){
        $result = $first * $second;
        echo "$first * $second = $result";
    }
    else if($op == '/'){
        if($second == 0){
            echo "invalid second input";
        }
        else{
        $result = $first / $second;
        echo "$first / $second = $result";  
        }
    }
}
?>