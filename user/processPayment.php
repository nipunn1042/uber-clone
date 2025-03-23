<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ride_id'])) {
    $ride_id = $_POST['ride_id'];

    // Update payment status
    $updateQuery = "UPDATE rides SET payment_status = 'Received' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $ride_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Payment successful!";
    } else {
        $_SESSION['error'] = "Payment failed!";
    }
}

header("Location: rideHistory.php");
exit();
?>
