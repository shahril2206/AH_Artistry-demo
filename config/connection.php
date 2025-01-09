<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "ah_artistry";

// create connection
$conn = mysqli_connect($server, $username, $password, $database);

// if cannot connect
if(!$conn){
    die("Connection failed: ".  mysqli_connect_error());
}
?>
