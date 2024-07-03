<?php
$servername = "192.168.1.160";
$port = 3306;
$username = "jdonohue";
$password = "Annapolis92!";
$dbname = "golf_stats_tracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
