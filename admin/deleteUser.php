<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Delete user from the database
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user.";
    }

    $stmt->close();
    $conn->close();

    header("Location: usersList.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: usersList.php");
    exit();
}
?>
