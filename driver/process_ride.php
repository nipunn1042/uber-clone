<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: ../login.php");
    exit();
}

$driver_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ride_id'])) {
    $ride_id = $_POST['ride_id'];

    if (isset($_POST['accept'])) {
        // Check if another driver already accepted
        $checkQuery = "SELECT * FROM ride_requests WHERE ride_id = ? AND status = 'Accepted'";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $ride_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Another driver has already accepted this ride.";
            header("Location: driverHome.php");
            exit();
        }

        // Update ride status & assign driver
        $conn->begin_transaction();
        try {
            // Insert into ride_requests
            $insertQuery = "INSERT INTO ride_requests (ride_id, driver_id, status) VALUES (?, ?, 'Accepted')";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ii", $ride_id, $driver_id);
            $stmt->execute();

            // Update rides table
            $updateQuery = "UPDATE rides SET status = 'Accepted', driver_id = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ii", $driver_id, $ride_id);
            $stmt->execute();

            $conn->commit();
            $_SESSION['success'] = "Ride accepted successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Error processing ride request.";
        }

    } elseif (isset($_POST['reject'])) {
        // Insert a rejection into ride_requests
        $rejectQuery = "INSERT INTO ride_requests (ride_id, driver_id, status) VALUES (?, ?, 'Rejected')";
        $stmt = $conn->prepare($rejectQuery);
        $stmt->bind_param("ii", $ride_id, $driver_id);
        $stmt->execute();

        $_SESSION['success'] = "Ride rejected successfully!";
    }
}

header("Location: driverHome.php");
exit();
?>
