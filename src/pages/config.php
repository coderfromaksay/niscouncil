<?php
// Set correct time zone
date_default_timezone_set('Asia/Oral');

// Database Configuration
$host = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "SCSite";

// Create Connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}
?>