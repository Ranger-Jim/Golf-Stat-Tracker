<?php
$servername = "161.35.58.67";
$port = 3306;
$username = "james";
$password = "Baltimore92!";
$dbname = "golf_stats";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
