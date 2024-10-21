<?php
date_default_timezone_set('Asia/Kolkata'); // Set time zone to India/Chennai

$servername = "localhost"; // Update if necessary
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "time_capsule_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
