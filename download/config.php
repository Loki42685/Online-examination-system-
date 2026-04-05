<?php
$servername = "sql201.infinityfree.com";	
$username = "if0_41181671";
$password = "Unknown42685";
$dbname = "if0_41181671_onlineexaminationsystem";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>
