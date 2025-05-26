<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "scoring_system";

// connect to the database
$conn = new mysqli($host, $username, $password, $database);

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>