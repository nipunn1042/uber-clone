<?php
$servername = "localhost"; // Change if using a different host
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$database = "uber_clone"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
