<?php
$server_name="localhost";
$user_name="dppuser";
$password="dpppass";
$db_name="dpp";

$con= mysqli_connect($server_name,$user_name,$password,$db_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to prevent SQL injection
mysqli_set_charset($con, "utf8");
?>