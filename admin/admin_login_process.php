<?php
session_start();
require '../config/config.php'; // Ensure this points to your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin record from database
    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Set session for admin
        $_SESSION['admin'] = $admin['username'];
        
        // Redirect to admin dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password!'); window.location.href='admin_login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
