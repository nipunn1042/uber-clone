<?php
session_start();
require '../config/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if driver_id is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];

    // Delete driver from the database
    $query = "DELETE FROM drivers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $driver_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Driver deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete driver.";
    }

    $stmt->close();
    $conn->close();
}

header("Location: driversList.php");
exit();
?>
