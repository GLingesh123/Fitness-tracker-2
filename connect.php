<?php

$host="localhost";
$user="lingesh";
$pass="lingesh";
$db="fitness_tracker";
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error){
    echo "Failed to connect DB".$conn->connect_error;
}
?>