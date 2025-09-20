<?php
// Database connection settings
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "train&gain";
$port       = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set character encoding (important for special characters)
$conn->set_charset("utf8");
