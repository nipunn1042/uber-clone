<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; 
$database = "uber_clone"; //database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
