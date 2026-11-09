<?php
$host = "localhost";
$user = "root"; // MySQL username
$password = ""; // MySQL password
$database = "Kooza"; // Updated database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
